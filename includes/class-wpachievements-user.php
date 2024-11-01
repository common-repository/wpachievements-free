<?php
/**
 * User handling class
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

/**
* WPA User Class
*/
class WPAchievements_User {

  /**
   * Get quest progress
   *
   * @static
   * @access  public
   * @param   int $quest_id Quest Post ID
   * @return  array Array of quest steps (true|false per step)
   */
  public static function get_quest_progress( $quest_id ) {
    global $wpdb;

    $user_id = get_current_user_id();

    if ( ! $user_id ) {
      return array();
    }

    $steps_gained = array();

    $quest_steps = (array) get_post_meta( $quest_id, '_quest_details', true );

    foreach ( $quest_steps as $key => $step ) {
      // Init the step gained var
      $steps_gained[ $key ] = false;
      $post_id_query = ( ! empty( $step['associated_id'] ) ) ? "AND postid = '{$step['associated_id']}'" : '';

      // Get user activities for this step
      if ( 'wpachievements_achievement' == $step['type'] ) {
        $activities_count = $wpdb->get_var( "SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type LIKE '%{$step['type']}%' AND postid = '{$step['ach_id']}' AND uid = '{$user_id}'" );
      }
      else {
        $activities_count = $wpdb->get_var( "SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type= '{$step['type']}' AND uid = '{$user_id}' {$post_id_query}" );
      }

      if ( $activities_count && $activities_count >= $step['occurrences'] ) {
        $steps_gained[ $key ] = true;
      }
    }

    return $steps_gained;
  }

  /**
   * Add or deduct user points
   *
   * @static
   * @access  public
   * @param   int $user_id User ID
   * @param   int $points  Ammout of points to add or deduct (negative number)
   * @param   string $activity Reason for point increase/deduction
   * @return  void
   */
  public static function handle_points( $args = array() ) {

    $defaults = array(
      'activity'          => '',
      'user_id'           => NULL,
      'post_id'           => NULL,
      'points'            => 0,
      'current_user_rank' => '',
      'reference'         => '',
      'log_entry'         => '',
    );

    $args = wp_parse_args( $args, $defaults );

    if ( is_multisite() ) {
      switch_to_blog(1);
    }

    $user_points = intval( get_user_meta( $args['user_id'], 'achievements_points', true ) ) + intval( $args['points'] );

    // Handle negative user points
    if ( ( $user_points < 0 ) && ( 'no' == wpachievements_get_site_option( 'wpachievements_negative_points', 'no' ) ) ) {
      $user_points = 0;
    }

    update_user_meta( $args['user_id'], 'achievements_points', $user_points );

    do_action( 'wpachievements_after_handle_points', $args );

    if ( is_multisite() ) {
      restore_current_blog();
    }
  }

  /**
   * Get user points based on rank type
   *
   * @param int $user_id
   * @return int Points
   */
  public static function get_points( $user_id ) {

    if ( 'Points' == wpachievements_get_site_option( 'wpachievements_rank_type' ) ) {
      $points = intval( get_user_meta( $user_id, 'achievements_points', true ) );
    }
    else {
      $points = intval( get_user_meta( $user_id, 'achievements_count', true ) );
    }

    $points = apply_filters( 'wpachievements_get_user_points', $points, $user_id );

    if( empty($points) ){ $points = 0; }

    return $points;
  }

  /**
   * Get rank by user ID
   *
   * @static
   * @access  public
   * @param   int $user_id User ID
   * @return  string       User Rank
   */
  public static function get_rank( $user_id ) {

    $ranks = (array) wpachievements_get_site_option( 'wpachievements_ranks_data' );
    ksort( $ranks );
    $ranks = array_reverse( $ranks, 1 );

    $points = self::get_points($user_id);

    foreach ( $ranks as $rank_points => $rank_name ) {
      if ( $points >= $rank_points ) {
        if ( is_array( $rank_name ) ) {
          return $rank_name[0];
        }
        else {
          return $rank_name;
        }
      }
    }

    // Rank not found
    return false;
  }

  /**
   * Update gained achievements
   *
   * @static
   * @access  public
   * @param   int $user_id     User ID
   * @param   array $achievement Array of achievement details
   * @return  void
   */
  public static function update_gained_achievements( $user_id, $achievement ) {

    $user_achievements = get_user_meta( $user_id, 'achievements_gained', true );

    $achievement_id = $achievement['id'];

    if ( is_array( $user_achievements ) ) {
      // Delete the same achievement first
      if ( array_key_exists( $achievement_id, $user_achievements ) ) {
        unset( $user_achievements[ $achievement_id ] );
      }

      $user_achievements[] = $achievement_id;
    }
    else {
      $user_achievements = array();
      $user_achievements[] = $achievement_id;
    }

    update_user_meta( $user_id, 'achievements_' . $achievement_id . '_gained', time() );
    update_user_meta( $user_id, 'achievements_gained', $user_achievements );
    update_user_meta( $user_id, 'achievements_count', sizeof( $user_achievements ) );

    update_post_meta( $achievement_id, '_user_gained_' . $user_id, $user_id );

    $ach_meta   = (array) get_user_meta( $user_id, 'wpachievements_got_new_ach', true );
    $ach_meta[] = array(
      'title' => $achievement['title'],
      'text'  => $achievement['description'],
      'image' => $achievement['img'],
    );

    update_user_meta( $user_id, 'wpachievements_got_new_ach', $ach_meta );
  }

  /**
   * Updated gained quests
   *
   * @param int $user_id
   * @param array $quest
   */
  public static function update_gained_quests( $user_id, $quest ) {

    $user_quests   = (array) get_user_meta( $user_id, 'quests_gained', true );
    $user_quests[] = $quest['id'];

    update_user_meta( $user_id, 'quests_gained', $user_quests );
    update_post_meta( $quest['id'], '_user_gained_' . $user_id, $user_id );

    $userquestss = get_user_meta( $user_id, 'quests_gained', true );
    $size        = sizeof($userquestss);

    update_user_meta($user_id, 'quests_count', $size);

    $quest_meta   = (array) get_user_meta( $user_id, 'wpachievements_got_new_quest', true );
    $quest_meta[] = array(
      'title' => $quest['title'],
      'text'  => $quest['description'],
      'image' => $quest['img'],
    );

    update_user_meta( $user_id, 'wpachievements_got_new_quest', $quest_meta );
  }

  /**
   * Check if a user has gained a specific achievement
   *
   * @static
   * @access  public
   * @param   int  $user_id        User ID
   * @param   int  $achievement_id Achievement Post ID
   * @return  boolean
   */
  public static function has_achievement( $user_id, $achievement_id ) {

    $user_achievements = (array) get_user_meta( $user_id, 'achievements_gained', true );

    if ( in_array( $achievement_id, $user_achievements ) ) {
      return true;
    }

    return false;
  }

  /**
   * Check if a user has gained a specific achievement
   *
   * @static
   * @access  public
   * @param   int  $user_id   User ID
   * @param   int  $quest_id  Quest Post ID
   * @return  boolean
   */
  public static function has_quest( $user_id, $quest_id ) {

    $user_achievements = (array) get_user_meta( $user_id, 'quests_gained', true );

    if ( in_array( $quest_id, $user_achievements ) ) {
      return true;
    }

    return false;
  }

  /**
   * Check if a user has gained a specific achievement
   *
   * @static
   * @access  public
   * @param   int  $user_id   User ID
   * @param   string  $rank   Rank to check
   * @return  boolean
   */
  public static function has_rank( $user_id, $rank ) {

    if ( self::get_rank( $user_id ) == $rank ) {
      return true;
    }

    return false;
  }
}
