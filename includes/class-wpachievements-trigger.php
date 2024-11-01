<?php
/**
 * Handle activities
 */
class WPAchievements_Trigger {

  public static function init() {
    add_filter( 'wpachievements_is_active_trigger', array( __CLASS__, 'custom_is_active_trigger' ) );
  }

  /**
  * Record new activities and trigger achievements
  * Parameters
  *   activity:     Unique activity trigger
  *   user_id:      WordPress user ID
  *   post_id:      Post where the activity has been triggered
  *   points:       Points awarded with this activity
  *   force_award:  Usually points are awarded only for the first time a user
  *                 triggers an activity. Use this flag to award points for the
  *                 same activity again.
  *
  * @param   array $args   Array of parameters. See default parameter
  * @return  void
  */
  public static function new_activity( $args = array() ) {
    global $wpdb;

    $defaults = array(
      'activity'    => '',
      'user_id'     => NULL,
      'post_id'     => NULL,
      'points'      => 0,
      'force_award' => false,
    );

    $args = wp_parse_args( $args, $defaults );

    WPAchievements()->logger()->add( 'log', __CLASS__ . ' - ' . __FUNCTION__ . " - activity_trigger: {$args['activity']}, user: {$args['user_id']}, post_id: {$args['post_id']}, points: {$args['points']}, force_award: {$args['force_award']}" );

    if ( ! is_user_logged_in() && ! $args['user_id'] || ! $args['activity'] ) {
      return;
    }

    $args['user_id'] = ($args['user_id']) ? $args['user_id'] : get_current_user_id();

    // Check if this trigger / activity is used somewhere. If not, don't track it!
    if ( ! $args['points'] && ! self::is_active( $args['activity'] ) ) {
      return;
    }

    // Check user has received points for this activity already
    if ( ! $args['force_award'] && self::was_awarded( $args['activity'], $args['user_id'], $args['post_id'] ) ) {
      // Overwrite points for already awarded activities
      $args['points'] = apply_filters( 'wpachievements_points_for_not_valid_activity', 0, $args['activity'], $args['user_id'], $args['post_id'] );
    }

    $current_user_rank = WPAchievements_User::get_rank( $args['user_id'] );

    do_action( 'wpachievements_before_new_activity', $args['activity'], $args['user_id'], $args['post_id'], $args['points'] );

    // Insert activity into the table
    $wpdb->query( $wpdb->prepare( "INSERT INTO ".WPAchievements()->get_table()." (uid, type, rank, data, points, postid, timestamp) VALUES
		( '%d', '%s', '%s', '%s', '%d', '%d', '%s' )", $args['user_id'], $args['activity'], $current_user_rank, '', $args['points'], $args['post_id'], time() ) );

    // Handle points for the activity
    WPAchievements_User::handle_points( array(
            'activity'          => $args['activity'],
            'user_id'           => $args['user_id'],
            'post_id'           => $args['post_id'],
            'points'            => $args['points'],
            'current_user_rank' => $current_user_rank,
            'reference'         => 'new_activity',
            'log_entry'         => WPAchievements()->achievement()->get_description( $args['activity'], $args['points'], 1),
          ) );

    do_action( 'wpachievements_after_new_activity', $args['activity'], $args['user_id'], $args['post_id'], $args['points'], $current_user_rank );
  }

  /**
   * Check if a trigger is active (used in achievements or quests)
   *
   * @param string $activity
   * @return boolean
   */
  public static function is_active( $activity ) {
    global $wpdb;

    $activity = apply_filters( 'wpachievements_is_active_trigger', $activity );

    // Check if the trigger is used within achievements
    $results = $wpdb->get_var( "SELECT `meta_value` FROM {$wpdb->prefix}postmeta WHERE ( `meta_key` LIKE '_achievement_type' AND `meta_value` LIKE '{$activity}' ) OR (`meta_key` LIKE '_quest_details' AND `meta_value` LIKE '%{$activity}%')");

    if ( ! $results ) {
      // Trigger not active
      WPAchievements()->logger()->add( 'log', __CLASS__ . ' - ' . __FUNCTION__ . " - activity_trigger is not in use: {$activity}" );
      return false;
    }

    return true;
  }

  /**
   * Check if user has already been awarded for an activity
   * If so, then set the points to 0 but still track the activity.
   *
   * @param string $activity
   * @param int $user_id
   * @param int $post_id
   * @return boolean
   */
  public static function was_awarded( $activity, $user_id, $post_id ) {
    global $wpdb;

    $activity_already_logged = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM ".WPAchievements()->get_table()." WHERE type = '%s' AND  uid = '%d' AND postid = '%d'", $activity, $user_id, $post_id ) );

    if ( $activity_already_logged ) {
      return true;
    }

    return false;
  }

  /**
   * Filter the is_active_trigger for custom achievements activities
   *
   * @param string $activity
   * @return string
   */
  public static function custom_is_active_trigger( $activity ) {

    // Check if this is a custom activity trigger
    if ( strpos( $activity, 'custom_trigger_' ) !== FALSE ) {
      // Custom trigger activities have a suffix.
      // For this check we don't need it.
      $activity = 'custom_trigger';
    }

    return $activity;
  }
}

WPAchievements_Trigger::init();
