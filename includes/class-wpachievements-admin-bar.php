<?php
/**
 * Show details on the admin bar
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class WPAchievements_Admin_Bar {

  public static function init() {
    add_action( 'admin_bar_menu', array( __CLASS__, 'admin_bar' ), 1000 );
  }

  public static function admin_bar() {
    self::points();

    if ( "Disable" != wpachievements_get_site_option( 'wpachievements_rank_status' ) ) {
      self::rank();
    }
  }

  /**
   * Show the point history in admin top bar
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function points() {
    global $wpdb, $wp_admin_bar;

    if ( ! is_user_logged_in() || ! is_admin_bar_showing() ) {
      return;
    }

    $user_id = get_current_user_id();

    if( is_multisite() ) {
      switch_to_blog(1);
    }

    $points = intval( get_user_meta( $user_id, 'achievements_points', true ) );

    if ( is_multisite() ) {
      restore_current_blog();
    }

    $wp_admin_bar->add_menu( array(
      'id'      => 'wpachievements_points_menu',
      'parent'  => 'top-secondary',
      'title'   =>  apply_filters( 'wpachievements_admin_bar_points_menu_title', sprintf( _n( "%d Point", "%d Points", $points, 'wpachievmenets' ), $points ), $user_id ),
    ) );

    $wp_admin_bar->add_menu( array(
      'id' => 'wpachievements_points_menu_inner_1',
      'parent' => 'wpachievements_points_menu',
      'title' => '<strong>'.__('Recent Activity', 'wpachievements').'</strong>',
      'meta' => array(
        'class' => 'recent_point_activity_head'
      )
    ) );

    $overwrite = apply_filters('wpachievements_overwrite_admin_bar_points_history', false );

    if ( $overwrite ) {
      do_action( 'wpachievements_admin_bar_points_history', $user_id );
    }
    else {
      // Display default point activities

      // Get 5 last activities
      $activities = $wpdb->get_results( "SELECT * FROM ".WPAchievements()->get_table()." WHERE uid = $user_id AND points != '' AND points != 0 ORDER BY id DESC LIMIT 5" );

      $count=0;

      foreach ( $activities as $activity ) {
        $count++;
        $text = WPAchievements()->achievement()->get_description( $activity->type, $activity->points, 'a ', $activity->data );

        $wp_admin_bar->add_menu( array(
          'id' => 'wpachievements_points_menu_inner_'.$count,
          'parent' => 'wpachievements_points_menu',
          'title' => '<strong>'.sprintf( _n( "%d Point", "%d Points", $points, 'wpachievmenets' ), $activity->points ).'</strong><i> '.$text.'</i><span>'.$count.'</span>',
          'meta' => array(
            'class' => 'recent_point_activity'
          )
        ) );
      }
    }
  }

  /**
   * Show the current rank and the points remained for the next level
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function rank() {
    global $wp_admin_bar;

    if ( ! is_user_logged_in() || ! is_admin_bar_showing() ) {
      return;
    }

    $user_id = get_current_user_id();
    $ranktype = wpachievements_get_site_option( 'wpachievements_rank_type' );

    $ranks = (array) wpachievements_get_site_option( 'wpachievements_ranks_data' );
    ksort($ranks);

    $points = WPAchievements_User::get_points($user_id);

    if ( $ranktype == 'Achievements' ) {
      $text = _n( "%s Achievement until next rank!", "%s Achievements until next rank!", $points, "wpachievements" );
    }
    else {
      $text = _n( "%s Point until next rank!", "%s Points until next rank!", $points, "wpachievements" );
    }

    $count=0;
    $nrm= __( 'You have reached the highest rank!', 'wpachievements' );
    $menu_id = 'custom_ranks_menu_lim';
    $rank_number = count( $ranks );

    foreach( $ranks as $p => $r ) {
      $count++;
      if( $points < $p ) {
        $rank_number = $count-1;
        $menu_id = 'custom_ranks_menu';
        $nrm = sprintf( $text, number_format($p - $points) );
        break;
      }
    }

    $wp_admin_bar->add_menu( array(
      'id' => $menu_id,
      'parent' => 'top-secondary',
      'title' => sprintf( __( 'Rank %d: %s', 'wpachievements' ) , $rank_number, WPAchievements_User::get_rank($user_id) )
    ) );

    $wp_admin_bar->add_menu( array(
      'id' => 'custom_ranks_menu_inner',
      'parent' => $menu_id,
      'title' => '<strong>'.$nrm.'</strong>',
      'meta' => array( 'class' => 'custom_ranks_head' )
    ) );
  }
}

WPAchievements_Admin_Bar::init();
