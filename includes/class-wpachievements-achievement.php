<?php
/**
 * Handle Achievements
 *
 */
class WPAchievements_Achievement {

  public function __construct() {
    add_action( 'wpachievements_after_new_activity', array( $this, 'maybe_award' ), 10, 3 );
    add_action( 'wp_ajax_wpa_auto_custom_trigger', array( $this, 'custom_achievement_trigger' ) );

    add_action( 'wp_ajax_nopriv_wpa_handle_activity_codes', array( $this, 'handle_activity_codes' ) );
    add_action( 'wp_ajax_wpa_handle_activity_codes', array( $this, 'handle_activity_codes' ) );
    add_action( 'wp_ajax_wpa_award_achievement', array( $this, 'ajax_award') );
  }

  /**
  * Award achievement for a certain activity
  *
  * @param string $activity Activity trigger
  * @param int $user_id
  * @param int $post_id
  * @return void
  */
  public function maybe_award( $activity, $user_id, $post_id ) {
    global $wpdb, $blog_id;

    if ( is_multisite() ) {
      $current_blog = $blog_id;
      switch_to_blog(1);
    }

    // Check if this is check for a custom achievement
    $query_activity = ( strpos( $activity, 'custom_trigger_' ) === FALSE ) ? $activity : 'custom_trigger';

    $query = array(
      'meta_query' => array (
        array(
          'key' => '_achievement_type',
          'value' => $query_activity,
        ),
      )
    );

    // Check for custom trigger. In this case we need to extend the query
    if ( 'custom_trigger' == $query_activity ) {
      $query['meta_query']['relation'] = 'AND';
      $query['meta_query'][] = array(
        'key' => '_achievement_trigger_id',
        'value' => str_replace( 'custom_trigger_', '', $activity ),
      );
    }

    // Get all achievements that are earned based on given acitivity trigger
    $achievements = WPAchievements_Query::get_achievements( $query );

    if ( ! $achievements ) {
      return;
    }

    // Get all gained user achievements
    $user_achievements = (array) get_user_meta( $user_id, 'achievements_gained', true );
    $user_rank_points = wpachievements_rankToPoints( WPAchievements_User::get_rank( $user_id ) );

    foreach( $achievements as $achievement ) {
      $required_rank_points = wpachievements_rankToPoints( get_post_meta( $achievement->ID, '_achievement_rank', true ) );

      if ( $user_rank_points < $required_rank_points ) {
        continue;
      }

      $required_occurrences = (int) get_post_meta( $achievement->ID, '_achievement_occurrences', true );

      // Check if user has already gained this achievement
      if ( in_array( $achievement->ID, $user_achievements ) ) {

        // Check if this is a recurring achievement
        if ( ! get_post_meta( $achievement->ID, '_achievement_recurring', true ) ) {
          // Not a recurring achievement.. skip here
          continue;
        }

        // Last time user got this achievement
        $timestamp = get_user_meta( $user_id, "achievements_{$achievement->ID}_gained", true );
      }
      else {
        // The user did not gain this achievement yet

        // Get the publish date of the achievement, because we count ony activities after the achievement has been published
        $timestamp = strtotime( get_the_date( '', $achievement->ID ) );
      }

      // How many times did the user trigger this activity after the defined timestamp
      $activity_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type = '%s' AND uid = %d AND timestamp > '%s'", $activity, $user_id, $timestamp ) );

      if ( $activity_count < $required_occurrences ) {
        // Not enough activities... skip here
        continue;
      }

      if ( is_multisite() ) {
        $limit_to_blog = get_post_meta( $achievement->ID, '_achievement_blog_limit', true );

        if ( $limit_to_blog && ( $current_blog != $limit_to_blog ) ) {
          continue;
        }
      }

      if ( 'activity_code_achievement' == $activity ) {
        if ( $post_id != $achievement->ID ) {
          continue;
        }
      }

      $proceed = apply_filters( 'wpachievements_proceed_new_activity', true, $activity, $achievement->ID, $post_id, $user_id );

      if ( ! $proceed ) {
        continue;
      }

      $associated_post_id = get_post_meta( $achievement->ID, '_achievement_associated_id', true );

      if ( $associated_post_id ) {
        if (  $post_id != $associated_post_id ) {
          continue;
        }

        $activities_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type = '%s' AND uid = '%d' AND postid = '%d'", $activity, $user_id, $associated_post_id ) );

        if ( $activities_count < $required_occurrences ) {
          continue;
        }
      }

      $this->award_achievement( $activity, $achievement->ID, $user_id, $post_id );
    }

    if ( is_multisite() ) {
      restore_current_blog();
    }
  }

  /**
   * Award an achievement to user
   *
   * @param string $activity
   * @param int $achievement_id
   * @param int $user_id
   * @param int $post_id
   */
  public function award_achievement( $activity, $achievement_id, $user_id, $post_id ) {
    global $wpdb;

    WPAchievements()->logger()->add( 'log', __CLASS__ . ' - ' . __FUNCTION__ . " - Achievement ID: {$achievement_id}, user: {$user_id}, activity_trigger: {$activity}" );

    // Get required details
    $achievement = array(
      'id' => $achievement_id,
      'title' => get_the_title( $achievement_id ),
      'description' => get_post_field('post_content', $achievement_id), /*$achievement_content,*/
      'points' => intval( get_post_meta( $achievement_id, '_achievement_points', true ) ),
      'rank' => get_post_meta( $achievement_id, '_achievement_rank', true ),
      'trigger' => "wpachievements_achievement_{$activity}",
      'occurences' => get_post_meta( $achievement_id, '_achievement_occurrences', true ),
      'img' => get_post_meta( $achievement_id, '_achievement_image', true ),
    );

    do_action( 'wpachievements_before_new_achievement', $achievement['trigger'], $user_id, $post_id, $achievement['points'], $achievement );

    // Add activity to database
    $wpdb->query( $wpdb->prepare( "INSERT INTO ".WPAchievements()->get_table()." (uid, type, rank, data, points, postid, timestamp) VALUES
      ( '%d', '%s', '%s', '%s', '%d', '%d', '%s' )", $user_id, $achievement['trigger'], wpachievements_getRank( $user_id ), $achievement['title'] .': ' . $achievement['description'], $achievement['points'], $achievement_id, time() ) );

    WPAchievements_User::handle_points( array(
      'activity'          => $achievement['trigger'],
      'user_id'           => $user_id,
      'post_id'           => $post_id,
      'points'            => $achievement['points'],
      'current_user_rank' => WPAchievements_User::get_rank( $user_id ),
      'reference'         => 'wpachievements_achievement',
      'log_entry'         => 'for Achievement: '. $achievement['title'],
    ) );

    // Update gained user achievements
    WPAchievements_User::update_gained_achievements( $user_id, $achievement );

    do_action( 'wpachievements_after_new_achievement', $user_id, $achievement_id, $achievement );
  }

  /**
   * Trigger a custom achievements. This can be triggered instantly or by a button
   *
   * @param string $trigger_id
   * @return void
   */
  public function custom_achievement_trigger( $trigger_id = false ) {

    if ( ! is_user_logged_in() ) {
      return;
    }

    $trigger_id = ( $trigger_id ) ? $trigger_id : filter_input( INPUT_POST, 'wpa_trigger_id' );

    if ( $trigger_id ) {
      // User has clicked on
      WPAchievements_Trigger::new_activity( array(
        'activity'    => 'custom_trigger_' . $trigger_id,
        'user_id'     => get_current_user_id(),
      ) );
    }
  }

  /**
   * Handle activity code verification
   *
   */
  public function handle_activity_codes() {
    global $wpdb;

    $activity_code = esc_sql( filter_input( INPUT_POST, 'activity_code' ) );

    if ( ! $activity_code ) {
      wp_die( json_encode( '<div class="activation_code_message error">' . __("Acitivity Code can't be empty!", 'wpachievements') . '</div>' ) );
    }

    if ( ! is_user_logged_in() ) {
      wp_die( json_encode( '<div class="activation_code_message error">' . __("Please log in first!", 'wpachievements') . '</div>' ) );
    }

    $current_user = wp_get_current_user();
    $points = (int) wpachievements_get_site_option('wpachievements_post_view_points');

    // Get the post by activity code
    $query_args = array (
      'meta_query'  => array (
        array (
          'key' => '_achievement_activity_code',
          'value' => $activity_code
        )
      )
    );

    $achievements = WPAchievements_Query::get_achievements( $query_args );

    if ( ! $achievements ) {
      // Achievement not found.. Invalid Code
      wp_die( json_encode( '<div class="activation_code_message error">' . __("Invalid Acitivity Code!", 'wpachievements') . '</div>' ) );
    }

    foreach ( $achievements as $achievement ) {
      $post_id =  $achievement->ID;
      break;
    }

    $activities = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type='wpachievements_achievement_activity_code_achievement' AND uid=%d AND postid=%d AND points > 0", $current_user->ID, $post_id ) );

    if ( empty( $activities ) ) {
      WPAchievements_Trigger::new_activity( array(
        'activity'    => 'activity_code_achievement',
        'user_id'     => '',
        'post_id'     => $post_id,
        'points'      => $points,
      ) );
      $output = WPAchievements_Notification::show_if_necessary( array(), array( 'wpachievements-check' => $current_user->ID ) );
      wp_die( json_encode( $output['wpachievements-check'] . '<div class="activation_code_message">' . __("Code activated successfully!", 'wpachievements') . '</div>' ) );
    }
    else {
      // The use has already activated this code
      wp_die( json_encode( '<div class="activation_code_message error">' . __("You have already used this activity code!", 'wpachievements') . '</div>' ) );
    }
  }

  /**
  * Get the achievement short description (Reason for receiving..)
  *
  * @param string $type Activity trigger
  * @param int $points
  * @param int|string $times
  * @param mixed $data
  * @return string
  */
  public function get_description( $type, $points = 0, $times = 1, $data = '' ) {

    if ( strpos( $type, 'wpachievements_achievement' ) !== false ) {
      $type = 'wpachievements_achievement';
    }
    elseif ( strpos( $type, 'wpachievements_quest' ) !== false ) {
      $type = 'wpachievements_quest';
    }

    switch( $type ) {
      case 'dailypoints': {
        $text = sprintf( __('for visiting us %s time(s)', 'wpachievements'), $times );
      } break;

      case 'admin': {
        $text = __('(Points adjusted by Admin)', 'wpachievements');
      } break;

      case 'register': {
        $text = __('for registering with us', 'wpachievements');
      } break;

      case 'comment': {
        if ( $points < 0) {
          $text = sprintf( __('for removing %s comment(s)', 'wpachievements'), $times);
        }
        else{
          $text = sprintf( __('for adding %s comment(s)', 'wpachievements'), $times);
        }
      } break;

      case 'post': {
        $post_text = ( $times > 1 ) ? WPACHIEVEMENTS_POST_TEXT."'s" : WPACHIEVEMENTS_POST_TEXT;

        if ( $points < 0) {
          $text = sprintf( __('for removing %s %s', 'wpachievements'), $times, $post_text);
        }
        else {
          $text = sprintf( __('for adding %s %s', 'wpachievements'), $times, $post_text);
        }
      } break;

      case 'wpachievements_achievement' : {
        $achieve = explode(":",$data);
        $text = sprintf( __('for Achievement: %s', 'wpachievements'), $achieve['0']);
      } break;

      case 'wpachievements_removed': {
        $text = __('(Admin Removed Achievement)', 'wpachievements');
      } break;

      case 'wpachievements_added': {
        $text = __('(Admin Added Achievement)', 'wpachievements');
      } break;

      case 'custom_achievement': {
        $achieve = explode(":",$data);
        $text = sprintf( __('for Achievement: %s', 'wpachievements'), $achieve['0']);
      } break;

      case 'wpachievements_changed': {
        $text = __('(Admin Modified Achievement)', 'wpachievements');
      } break;

      case 'wpachievements_quest_removed': {
        $text = __('(Admin Removed Quest)', 'wpachievements');
      } break;

      case 'wpachievements_quest_added': {
        $text = __('(Admin Added Quest)', 'wpachievements');
      } break;

      case 'wpachievements_quest_changed': {
        $text = __('(Admin Modified Quest)', 'wpachievements');
      } break;

      case 'wpachievements_quest' : {
        $achieve = explode(":",$data);

        if (  isset($achieve['0']) && $achieve['0'] != ' ' && $achieve['0'] != '' ) {
          $text = sprintf( __('for Quest: %s', 'wpachievements'), $achieve['0']);
        }
        else {
          $text = __('for unlocking a Quest', 'wpachievements');
        }
      } break;

      case 'fb_loggin': {
        $text = __('for logging in with Facebook', 'wpachievements');
      } break;

      default: {
        $text = __('for being awesome!', 'wpachievements');
      } break;
    }

    return apply_filters( 'wpachievements_activity_description', $text, $type, $points, $data );
  }

  /**
   * Get all users who have gained an achievement
   *
   * @access public
   * @param  int $post_id Post ID / Achievement Post ID
   * @return array        Array of User IDs
   */
  public function get_users( $post_id ) {
    global $wpdb;

    $users = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_user_gained_%' AND post_id = '%d'", $post_id ), ARRAY_N );

    if ( $users ) {
      $users = array_reduce( $users, 'array_merge', array() );
    }
    else {
      $users = array();
    }

    return $users;
  }

  /**
   * Ajax callback to award achievements
   *
   * @access public
   * @return void
   */
  public function ajax_award() {

    $achievement_id = intval( filter_input( INPUT_POST, 'post_id' ) );
    $user_id = intval( filter_input( INPUT_POST, 'user_id' ) );

    if ( ! $achievement_id || ! $user_id ) {
      wp_die( __("Can't award achievement", 'wpachievements' ) );
    }

    $user_data = get_userdata( $user_id );

    if ( $achievement_id && $user_id && $user_data ) {
      $this->award_achievement( 'manually_awarded', $achievement_id, $user_id, '' );

      wp_die( " <a href='".get_edit_user_link( $user_id )."' title='".__("Edit")."'>".$user_data->user_nicename."</a>" );
    }

    wp_die( __("Can't award achievement", 'wpachievements' ) );
  }
}
