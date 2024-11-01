<?php
/**
 * Stats Ajax Callbacks
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class WPAchievements_Admin_Stats_Ajax {

  protected static $_instance = null;

  /**
   * Main Instance
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return Main instance
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * The Constructor
   *
   * @access public
   * @return void
   */
  public function __construct() {
    add_action( 'wp_ajax_wpachievements_stats_get_widget_content', array( $this, 'get_widget_content' ) );
  }

  /**
   * Get the widget content
   *
   * @access  public
   * @return  void
   */
  public function get_widget_content() {

    // Get the requested widget
    $widget = filter_input( INPUT_POST, 'widget' );

    $widget_file = WPAchievements()->plugin_path() . '/includes/admin/stats-widgets/class-wpachievements-admin-widget-' . $widget . '.php';


    if ( file_exists( $widget_file ) ) {
      include_once( $widget_file );
    }

    wp_die();
  }
}

WPAchievements_Admin_Stats_Ajax::instance();