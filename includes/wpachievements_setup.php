<?php
/**
 * Deprecated - Those functions and hooks are not used anymore
 * The file will be removed in the future.
 * !DON'T EDIT ANYMORE!
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function wpachievements_achievement_id_by_title($ach_title) {
  global $wpdb;

  if ( is_multisite() ) {
    switch_to_blog(1);
  }

  $achievement_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'wpachievements' AND post_status = 'publish'", $ach_title ));

  if ( is_multisite() ) {
    restore_current_blog();
  }

  if ( $achievement_id ) {
    return $achievement_id;
  }

  return null;
 }

/**
 *****************************************
 *    C O N V E R T   O L D   D A T A    *
 *****************************************
 */

function wpachievements_sort_array($x){
   return $x[0];
}

add_action('wpachievements_user_admin_load', 'wpachievements_data_conversion', 1);
add_action('wpachievements_user_profile_load', 'wpachievements_data_conversion', 1);
add_action('wp_footer', 'wpachievements_data_conversion', 1);
function wpachievements_data_conversion($user_id){
   if( is_user_logged_in() ){
    if( empty($user_id) ){
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;
    }
    if( get_user_meta( $user_id, 'achievements_data_converted', true ) != 'done' ){
     if( is_multisite() ){

       global $wpdb;

       if($wpdb->get_var("SHOW TABLES LIKE ".WPAchievements()->get_table() ) != WPAchievements()->get_table() ) {
         $sql =
          "CREATE TABLE " . WPAchievements()->get_table() . " (
          id bigint(20) NOT NULL AUTO_INCREMENT,
          uid bigint(20) NOT NULL,
          type VARCHAR(256) NOT NULL,
          rank TEXT NOT NULL,
          data TEXT NOT NULL,
          points bigint(20) NOT NULL,
          postid bigint(20) NOT NULL,
          UNIQUE KEY id (id)
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
       }

       $blog_table = $wpdb->get_blog_prefix(1).'blogs';
       $old_blog = $wpdb->blogid;

       $site_blog_ids = $wpdb->get_results( "SELECT blog_id FROM $blog_table" ); // get all subsite blog ids

       $add_points = 0;
       $points = 0;
       $activities = array();
       $achievements = array();
       foreach( $site_blog_ids as $blog_id ){
         switch_to_blog( $blog_id->blog_id );

         $ach_table = $wpdb->get_blog_prefix($blog_id->blog_id).'achievements';
         array_push( $activities, $wpdb->get_results( $wpdb->prepare("SELECT type,rank,data,points,postid FROM $ach_table WHERE uid=%d", $user_id) ) );

         $wpdb->delete(
           $ach_table,
           array(
             'uid' => $user_id
           )
         );

         $points = $points + (int)get_blog_option( $wpdb->blogid, $user_id.'_achievements_points', true );
         delete_blog_option( $wpdb->blogid, $user_id.'_achievements_points' );

         array_push( $achievements, get_blog_option( $blog_id->blog_id, $user_id.'_achievements_gained' ) );
         delete_blog_option( $wpdb->blogid, $user_id.'_achievements_gained' );

         switch_to_blog( $old_blog );
       }

       $ach_table = $wpdb->get_blog_prefix(1).'wpachievements_activity';

       $count=0;
       if( is_array($activities) && !empty($activities) ){
         $activities = array_filter($activities);
         foreach( $activities as $activity ){
          if( array_key_exists($count, $activity) ){
           if( strpos($activity[$count]->type,'wpachievements_achievement_') === false ){
            $type = $activity[$count]->type;
            $rank = $activity[$count]->rank;
            $data = $activity[$count]->data;
            $points = $activity[$count]->points;
            $postid = $activity[$count]->postid;
            $wpdb->insert(
              $ach_table,
              array(
                'uid' => $user_id,
                'type' => $type,
                'rank' => $rank,
                'data' => $data,
                'points' => $points,
                'postid' => $postid
              ),
              array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%d'
              )
            );
            $add_points = $add_points + $activity[$count]->points;
            $count++;
           }
          }
         }
       }

       if( is_array($achievements) && !empty($achievements) ){
         $achievements = array_filter($achievements);
         $achievements = array_map('wpachievements_sort_array', $achievements);
         $achievements = array_values($achievements);
         $achievements_data = get_blog_option(1,'wpachievements_achievements_data');
         if( (!empty($achievements[0]) && $achievements[0] != '') && (!empty($achievements_data) && $achievements_data != '')){
          $newachievements = array();

           if( is_array($achievements[0]) ){
             $gainedachievements = array();
             $gainedachievements = call_user_func_array('array_merge',$achievements);
             $newachievements = array_unique($gainedachievements);
           }
           else{
             $newachievements = array_unique($achievements);
           }

           foreach( $newachievements as $new_activity ){

            $cur_ach = $achievements_data[$new_activity];
            $type = str_replace (" ", "", $cur_ach[0]);
            $type = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $type);
            $type = strtolower($type);
            $type = 'wpachievements_achievement_'.$type;
            $rank = '';
            $data = $cur_ach[0].': '.stripslashes($cur_ach[1]);
            $points = $cur_ach['2'];

            $wpdb->insert(
             $ach_table,
              array(
                'uid' => $user_id,
                'type' => $type,
                'rank' => $rank,
                'data' => $data,
                'points' => $points
              ),
              array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%d'
              )
            );
            $add_points = $add_points + $points;
           }
           update_user_meta( $user_id, 'achievements_gained', $newachievements );
         }
       }

       if( $add_points != 0 ){
         $current_points = (int)get_user_meta( $user_id, 'achievements_points', true );
         if( empty($current_points) || $current_points < $points ){
           update_user_meta( $user_id, 'achievements_points', $add_points );
         }
       }

       update_user_meta( $user_id, 'achievements_data_converted', 'done' );

     }
    }
   }
}

add_action('wp_footer', 'wpachievements_achievements_conversion');
add_action('admin_head', 'wpachievements_achievements_conversion');
function wpachievements_achievements_conversion(){
  $convert = wpachievements_get_site_option('wpachievements_achievements_converted');
  if( $convert != 'done' ){

    $achievements = wpachievements_get_site_option('wpachievements_achievements_data');
    if(!empty($achievements) || $achievements != ''){
      foreach($achievements as $achievement){

        $new_achievement = array(
          'post_title'    => $achievement[0],
          'post_content'  => stripslashes($achievement[1]),
          'post_type'     => 'wpachievements',
          'post_status'   => 'publish',
          'post_author'   => 1
        );
        if(is_multisite()){
          switch_to_blog(1);
        }
        $achievement_id = wp_insert_post( $new_achievement );


        update_post_meta($achievement_id, '_achievement_rank', $achievement[3]);

        update_post_meta($achievement_id, '_achievement_type', $achievement[4]);
        update_post_meta($achievement_id, '_achievement_occurrences', $achievement[5]);

        update_post_meta($achievement_id, '_achievement_associated_id', $achievement[7]);

        update_post_meta($achievement_id, '_achievement_points', $achievement[2]);
        update_post_meta($achievement_id, '_achievement_woo_points', $achievement[8]);

        update_post_meta($achievement_id, '_achievement_image', $achievement[6]);

        if(is_multisite()){
          restore_current_blog();
        }
      }
      if(is_multisite()){
        $achievements = update_option(1,'wpachievements_achievements_converted', 'done');
      } else{
        $achievements = update_option('wpachievements_achievements_converted', 'done');
      }
     }
   }


   $args = array(
     'meta_query' => array(
       array(
         'key' => '_achievement_type',
         'value' => 'post',
       )
     )
   );
   $achievements = WPAchievements_Query::get_achievements( $args );
   if( $achievements ){
     foreach( $achievements as $achievement ){
       update_post_meta( $achievement->ID, '_achievement_type', 'post_added' );
     }
   }
}

add_action('wpachievements_user_admin_load', 'wpachievements_new_data_conversion', 2);
add_action('wpachievements_user_profile_load', 'wpachievements_new_data_conversion', 2);
add_action('wp_footer', 'wpachievements_new_data_conversion', 2);
function wpachievements_new_data_conversion($user_id){
   if( is_user_logged_in() ){
    if( empty($user_id) ){
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;
    }
    $convert = wpachievements_get_site_option('wpachievements_achievements_converted');
    if( get_user_meta( $user_id, 'achievements_new_data_converted', true ) != 'done' && $convert == 'done' ){
      $userachievements = get_user_meta( $user_id, 'achievements_gained', true );
      if( $userachievements ){
        $achievements_data = wpachievements_get_site_option('wpachievements_achievements_data');
        if( $achievements_data ){
          foreach( $userachievements as $userachievement ){
            if( array_key_exists($userachievement, $achievements_data) ){
              $ach_ID = wpachievements_achievement_id_by_title( $achievements_data[$userachievement][0] );
              $newachievements[] = $ach_ID;
            }
          }
          update_user_meta( $user_id, 'achievements_gained', $newachievements );
        }
      }
      update_user_meta( $user_id, 'achievements_new_data_converted', 'done' );
    }
   }
}

add_action('bp_before_member_header_meta', 'wpachievements_bb_new_data_conversion');
function wpachievements_bb_new_data_conversion(){
   global $bp;
   $convert = wpachievements_get_site_option('wpachievements_achievements_converted');
   if( get_user_meta( $bp->displayed_user->id, 'achievements_new_data_converted', true ) != 'done' && $convert == 'done' ){
     $userachievements = get_user_meta( $bp->displayed_user->id, 'achievements_gained', true );
     if( $userachievements ){
       $achievements_data = wpachievements_get_site_option('wpachievements_achievements_data');
       if( $achievements_data ){
         foreach( $userachievements as $userachievement ){
           if( array_key_exists($userachievement, $achievements_data) ){
             $ach_ID = wpachievements_achievement_id_by_title( $achievements_data[$userachievement][0] );
             $newachievements[] = $ach_ID;
           }
         }
         update_user_meta( $bp->displayed_user->id, 'achievements_gained', $newachievements );
       }
     }
     update_user_meta( $bp->displayed_user->id, 'achievements_new_data_converted', 'done' );
   }
}

add_action('wpachievements_user_admin_load', 'wpachievements_update_needed_data', 2);
add_action('wpachievements_user_profile_load', 'wpachievements_update_needed_data', 2);
add_action('wp_footer', 'wpachievements_update_needed_data', 2);
function wpachievements_update_needed_data(){
  global $wpdb;

  $args = array(
    'meta_query' => array(
      array(
        'key' => '_achievement_postid',
        'compare' => 'NOT EXISTS',
        'value' => ''
      )
    )
  );
  $achievements = WPAchievements_Query::get_achievements( $args );
  if( $achievements ){
    foreach( $achievements as $achievement ){
      update_post_meta( $achievement->ID, '_achievement_recurring', 0 );
      update_post_meta( $achievement->ID, '_achievement_postid', $achievement->ID );
    }
  }

  if( !in_array("timestamp", $wpdb->get_col( "DESC " . WPAchievements()->get_table(), 0 )) ) {
    $wpdb->query( "ALTER TABLE ".WPAchievements()->get_table()." ADD timestamp varchar(200) NULL" );
  }
}
