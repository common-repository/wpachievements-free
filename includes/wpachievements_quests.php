<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 *******************************
 *   C H E C K   Q U E S T S   *
 *******************************
 */
 function wpachievements_check_quests($type='', $uid='', $postid='' ){

   WPAchievements()->logger()->add( 'log', __FUNCTION__ . " - Activity: {$type}, user: {$uid}" );

  if(is_user_logged_in() || !empty($uid) && !empty($type)){

    if(is_multisite()){
      global $blog_id;
      $curBlog = $blog_id;
      switch_to_blog(1);
    }

    global $wpdb;
    $current_user = wp_get_current_user();
    if(empty($uid)){$uid=$current_user->ID;}

    $userquests = get_user_meta( $uid, 'quests_gained', true );
    $usersrank = wpachievements_getRank($uid);
    $usersrank_lvl = wpachievements_rankToPoints($usersrank);

    global $oldtype;
    $oldtype = '';

    if( strpos($type,'wpachievements_achievement_') !== false ){
      global $oldtype;
      $oldtype = $type;
      $type = 'wpachievements_achievement';
    }

    if( !empty($userquests) ){
      $args = array(
        'post_type' => 'wpquests',
        'post_status' => 'publish',
        'post__not_in' => $userquests,
        'posts_per_page' => -1,
        'meta_query' => array(
          array(
            'key' => '_quest_details',
            'value' => $type,
            'compare' => 'LIKE'
          )
        )
      );
    } else{
      $args = array(
        'meta_query' => array(
          array(
            'key' => '_quest_details',
            'value' => $type,
            'compare' => 'LIKE'
          )
        )
      );
    }

    $quests = WPAchievements_Query::get_quests( $args );
    if( $quests ){
      foreach( $quests as $quest ){
        $quest_ID = $quest->ID;

        $quest_details = get_post_meta( $quest_ID, '_quest_details', true );
        foreach( $quest_details as $quest_item ){
          $type = $quest_item['type'];

          if( $type == 'wpachievements_achievement' ){
            $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type LIKE %s AND postid=%d AND uid=%d", '%'.$type.'%', $quest_item['ach_id'], $uid) );
          } else{
            $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type='%s' AND uid=%d", $type, $uid ) );
          }

          if( $activities_count == 0 || ($activities_count != 0 && $activities_count < $quest_item['occurrences']) ){
            $quest_gained = '';
            break;
          }

          if(is_multisite()){
            $blog_limit = $quest_item['blog_limit'];
            if( !empty($blog_limit) ){
              if( $curBlog != $blog_limit ){
                $quest_gained = '';
                break;
              }
            }
          }

          $quest_activity_count = $quest_item['occurrences'];
          $quest_postid = $quest_item['associated_id'];

          if( $type == 'cp_bp_group_joined' ){
            $quest_group = $quest_item['associated_title'];
            if( !empty($quest_group) && $quest_group != '' ){
              if( !empty($postid) && $postid != '' ){
                $group = groups_get_group( array( 'group_id' => $postid ) );
                if( !empty($group) && $group != '' ){
                  if( $group->name != $quest_group ){
                    $quest_gained = '';
                    break;
                  }
                }
              }
            }
          } elseif( !empty($quest_postid) && $quest_postid != '' ){
            if( $postid == $quest_postid ){
              $this_activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type='%s' AND uid=%d AND postid=%d", $type, $uid, $quest_postid) );
              if( $this_activities_count < $quest_activity_count ){
                $quest_gained = '';
                break;
              }
            } else{
              $quest_gained = '';
              break;
            }
          }

          if( $type == 'ld_quiz_perfect' ){
            $quest_first_try_only = $quest_item['ld_first_attempt_only'];
            if( $postid && ($quest_first_try_only == 'enabled' || $quest_first_try_only == 'Enabled') ){
              $attempt_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE (type='ld_quiz_pass' OR type='ld_quiz_fail' OR type='ld_quiz_perfect') AND uid=%d AND postid='%d'", $uid,$postid) );

              if( !empty($attempt_count) && $attempt_count > 1 ){
                $quest_gained = '';
                break;
              }
            }
          }

          if( ($type == 'wc_order_complete' || $type == 'wc_user_spends') && !empty($postid) ){
            $woo_order_limit = $quest_item['woo_order_limit'];
            if( $woo_order_limit > 0 ){
              $order = new WC_Order($postid);
              $order_total = $order->get_order_total();
              if( empty($order_total) || ($order_total < $woo_order_limit) ){
                $quest_gained = '';
                break;
              }
            }
          }

          $quest_gained = 'true';
        }

        $quest_rank = get_post_meta( $quest_ID, '_quest_rank', true );
        $quest_rank_lvl = wpachievements_rankToPoints($quest_rank);
        if( $usersrank_lvl < $quest_rank_lvl ){
          $quest_gained = '';
        }

        if( $quest_gained == 'true' ){
          $type = 'wpachievements_quest';

          $quest = array(
            'id' => $quest_ID,
            'title' => get_the_title( $quest_ID ),
            'description' => get_post_field('post_content', $quest_ID),
            'points' => get_post_meta( $quest_ID, '_quest_points', true ),
            'rank' => get_post_meta( $quest_ID, '_quest_rank', true ),
            'trigger' => 'wpachievements_quest',
            'img' => get_post_meta( $quest_ID, '_quest_image', true ),
          );

          do_action( 'wpachievements_before_new_quest', $uid, $quest_ID );

          WPAchievements()->logger()->add( 'log', __FUNCTION__ . " - Quest solved: {$quest_ID}, user: {$uid}" );

          $wpdb->query( $wpdb->prepare( "INSERT INTO ".WPAchievements()->get_table()." (uid, type, rank, data, points, postid) VALUES
		( '%d', '%s', '%s', '%s', '%d', '%d' )", $uid, $type, $usersrank, $quest['title'] . ': ' . $quest['description'], $quest['points'], $postid ) );

          WPAchievements_User::handle_points( array(
            'activity'          => 'wpachievements_quest',
            'user_id'           => $uid,
            'post_id'           => $postid,
            'points'            => $quest['points'],
            'current_user_rank' => WPAchievements_User::get_rank( $uid ),
            'reference'         => 'wpachievements_quest',
            'log_entry'         => 'for Quest: '. $quest['title'],
          ) );

          WPAchievements_User::update_gained_quests( $uid, $quest );

          do_action( 'wpachievements_after_new_quest', $uid, $quest['id'], $quest, $postid );
        }
      }
    }

    if(is_multisite()){
      restore_current_blog();
    }
  }
 }
 add_action('wpachievements_after_new_activity', 'wpachievements_check_quests', 1, 3);
 add_action('wpachievements_before_custom_achievement', 'wpachievements_check_quests', 1, 2);
 //add_action('wpachievements_after_new_custom_achievement', 'wpachievements_check_quests', 1, 3);
 add_action('wpachievements_after_new_achievement', 'wpachievements_check_quests', 1, 3);

/**
 ***********************************************************************
 *   W P A C H I E V E M E N T S   Q U E S T   D E S C R I P T I O N   *
 ***********************************************************************
 */
function quest_Desc($type='',$times='') {
  global $wpdb;

  if ( strpos( $type,'wpachievements_achievement_' ) !== false ) {
    $triggerID = substr($type, 27);
    $ach_title = $wpdb->get_var( $wpdb->prepare("SELECT post_title FROM $wpdb->posts WHERE ID = %s", $triggerID) );
  }

  switch($type) {
   case 'dailypoints': { $text = sprintf( __('Visit us %s time(s)', 'wpachievements'), $times ); } break;
   case 'register': { $text = __('Register with us', 'wpachievements'); } break;
   case 'comment': { $text = sprintf( __('Add %s comment(s)', 'wpachievements'), $times); } break;
   case 'post': {
      $post_text = ( $times > 1 ) ? WPACHIEVEMENTS_POST_TEXT."'s" : WPACHIEVEMENTS_POST_TEXT;
      $text = sprintf( __('Add %s %s', 'wpachievements'), $times, $post_text );
    } break;
   case 'fb_loggin': { $text = __('Log in with Facebook', 'wpachievements'); } break;
   case 'custom_achievement': { $text = __('Manually awarded by admin', 'wpachievements'); } break;
   case 'activity_code_achievement': { $text = __('Enter Activity Code', 'wpachievements'); } break;
   case strpos($type,'wpachievements_achievement_') !== false: { $text = sprintf( __('Gain the achievement "%s"', 'wpachievements'), $ach_title); } break;
   default: $text = ''; break;
  }
  return apply_filters('wpachievements_quest_description', $text,$type,$times );
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_default_admin_triggers', 1, 10);
 function achievement_default_admin_triggers($trigger){

   switch($trigger){
     case 'custom_achievement': { $trigger = __('Manually Awarded', 'wpachievements'); } break;
     case strpos($trigger,'wpachievements_achievement_') !== false: { $trigger = __('The user gains an achievement', 'wpachievements'); } break;
     case is_numeric($trigger): { $trigger = __('The user gains an achievement', 'wpachievements'); } break;
   }

   return $trigger;

 }

/**
 *************************************************************************
 *   W P A C H I E V E M E N T S   A C H I E V E M E N T   N O T I C E   *
 *************************************************************************
 */
add_filter( 'heartbeat_received', 'wpa_quest_respond_to_browser', 10, 2 );
function wpa_quest_respond_to_browser( $response, $data ) {
  if ( isset( $data['wpachievements-quest-check'] ) ) {
    $umeta = (array) get_user_meta( $data['wpachievements-quest-check'], 'wpachievements_got_new_quest' );

    if ( is_array( $umeta ) && !empty($umeta) ) {
      $html = '';
      if( function_exists('wpachievements_fb_share_achievement_filter') ) {
        $html = wpachievements_fb_share_achievement_filter('quest');
      }

      delete_user_meta( $data['wpachievements-quest-check'], 'wpachievements_got_new_quest' );

      $pop_col = strtolower( wpachievements_get_site_option( 'wpachievements_pcol' ) );
      $pop_time = strtolower( wpachievements_get_site_option('wpachievements_ptim' ) );

      if( empty($pop_col) ){
        $pop_col = '#333333';
      }
      if( strpos($pop_col,'#') === false ){
        $pop_col = '#'.$pop_col;
      }

      foreach($umeta as $quests){
        if ( is_array($quests) && ! empty( $quests ) ) {
          foreach($quests as $thisquest){
            if ( isset( $thisquest['title'] ) && isset( $thisquest['text'] ) && isset( $thisquest['image'] )  ) {
              $html .= '<script type="text/javascript">
              jQuery.smallBox({
                title: "'. $thisquest['title'] .'",
                content: "'. str_replace( '"', '\'', $thisquest['text'] ) .'",
                color: "'. $pop_col .'",';

                if( $pop_time > 0 ){
                  if( $pop_time < 1000 ){
                    $html .= 'timeout: "'.$pop_time.'000",';
                  } else{
                    $html .= 'timeout: "'.$pop_time.'",';
                  }
                }
                $html .='
                img: "'. $thisquest['image'] .'",
                icon: "'. WPACHIEVEMENTS_URL . '/includes/popup/img/medal.png",
                extra_type: "quest"
              });jQuery("#wp-admin-bar-wpachievements_points_menu").load("'. home_url('').' #wp-admin-bar-wpachievements_points_menu > *");</script>';
            }
          }
        }
      }

      if( function_exists('wpachievements_twr_share_achievement_return') ) {
        $html .= wpachievements_twr_share_achievement_return();
      }

      $response['wpachievements-quest-check'] = $html;
    }
  }

  return $response;
}

/**
 *********************************************************************
 *   W P A C H I E V E M E N T S   A C H I E V E M E N T   L I S T   *
 *********************************************************************
 */
 function wpa_quest_achievement_list(){
   $html='';
   $achievements = WPAchievements_Query::get_achievements();
   if( $achievements ){
     foreach( $achievements as $achievement ){
       $ach_ID = $achievement->ID;
       $ach_title = $achievement->post_title;
       $html .= '<option value="'.$ach_ID.'">'.$ach_title.'</option>';
     }
   }
   return $html;
 }

 /**
 *********************************************************************
 *   W P A C H I E V E M E N T S   Q U E S T  L I S T   *
 *********************************************************************
 */
function wpa_quest_list( $selected_ID='' ) {

  $html='';
  $quests = WPAchievements_Query::get_quests();

  if( $quests ) {
    foreach( $quests as $quest ){
      $selected = ( $selected_ID === $quest->ID ) ? ' selected' : '';
      $html .= '<option value="'.$quest->ID.'"'.$selected.'>'.$quest->post_title.'</option>';
    }
  }

  return $html;
}

/**
 * Retrieve quest steps
 *
 * @param   int $post_id Quest post id
 * @return  array Array of formatted quest steps
 */
function wpa_quest_steps( $post_id ) {

  $quest_steps = get_post_meta( $post_id, '_quest_details', true );

  if ( ! $quest_steps ) {
    return array();
  }

  $formatted_steps = array();

  foreach ( $quest_steps as $step ) {
    if ( $step['type'] == 'wpachievements_achievement' ) {
      $formatted_steps[ $step['type'] ] = sprintf( __('Unlock achievement: %s', 'wpachievements'), get_the_title( $step['ach_id'] ) );
    }
    else {
      $formatted_steps[ $step['type'] ] = quest_Desc( $step['type'], $step['occurrences'] );
    }
  }

  return $formatted_steps;
}