<?php
/**
 * Plugin Name: WPAchievements Free
 * Plugin URI:  https://wpachievements.net
 * Description: Achievements, Quest and Ranks Plugin for WordPress
 * Version:     1.2.0
 * Author:      Powerfusion
 * Author URI:  http://wpachievements.net
 * License:     GPLv3 or later (license.txt)
 * Text Domain: wpachievements
 * Domain Path: /lang
 */

if ( ! class_exists( 'WPAchievements' ) ) :
final class WPAchievements {

  protected static $_instance = null;

  /**
   * @var WPAchievements_Logger
   */
  var $logger;

  /**
   *
   * @var WPAchievements_Achievement
   */
  var $achievement;

  /**
   *
   * @var string Activity table name.
   */
  protected $table;

  /**
   * Main Instance
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @see WPAchievements()
   * @return Main instance
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * Cloning is forbidden.
   *
   * @access public
   */
  public function __clone() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '8.3.1' );
  }

  /**
   * Unserializing instances of this class is forbidden.
   *
   * @access public
   */
  public function __wakeup() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '8.3.1' );
  }

  /**
   * Constructor.
   *
   * @access  public
   * @return  void
   */
  public function __construct() {

    // Define required constants
    $this->define_constants();

    // Include required files
    $this->includes();

    // Init hooks
    $this->init_hooks();
  }

  /**
   * Include required core files used in admin and on the frontend.
   *
   * @access public
   * @return void
   */
  public function includes() {

    require_once( 'includes/class-wpachievements-autoloader.php' );

    require_once( 'includes/wpachievements-core-functions.php' );
    require_once( 'includes/class-wpachievements-posttypes.php' );
    require_once( 'includes/class-wpachievements-scripts.php' );

    require_once( 'includes/class-wpachievements-trigger.php' );
    require_once( 'includes/wpachievements_quests.php');
    require_once( 'includes/wpachievements_ranks.php');
    require_once( 'includes/wpachievements_content_lock.php');
    require_once( 'includes/class-wpachievements-admin-bar.php' );
    require_once( 'includes/class-wpachievements-tracker.php' );

    require_once( 'includes/social/facebook/setup.php' );
    require_once( 'includes/social/twitter/setup.php' );

    require_once( 'includes/class-wpachievements-notification.php' );

    // Include only on admin page
    if ( $this->is_request( 'admin' ) ) {
      require_once( 'includes/admin/wpachievements-admin.php' );

      // Include ajax handler only when doing an ajax request
      if ( defined( 'DOING_AJAX' ) ) {
        require_once( 'includes/admin/class-wpachievements-admin-stats-ajax.php' );
      }
    }
  }

  /**
   * Init required actions and filters
   *
   * @access  private
   * @return  void
   */
  private function init_hooks() {
    register_activation_hook( __FILE__, array( $this, 'install' ) );
    register_uninstall_hook( __FILE__, array(  __CLASS__, 'uninstall' ) );

    add_action( 'plugins_loaded', array( $this, 'init' ) );
    add_action( 'init', array( 'WPAchievements_Shortcodes', 'init' ) );
    add_action( 'widgets_init',array( $this, 'register_widgets' ) );
  }

  /**
   * Init when WordPress initializes
   *
   * @access  public
   * @return  void
   */
  public function init() {
    // Load localization
    load_plugin_textdomain( 'wpachievements', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');

    $this->achievement();

    $this->init_modules();
  }

  /**
   * Define required constants
   *
   * @access  private
   * @return  void
   */
  private function define_constants() {

    define( 'WPACHIEVEMENTS_PATH', plugin_dir_path(__FILE__) );
    define( 'WPACHIEVEMENTS_URL', plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) ) );

    // Defines for plugin support
    define( 'WPACHIEVEMENTS_POST_TEXT', __('Post', 'wpachievements') );
  }

  /**
   * Init default module integrations
   *
   * @access  private
   * @return  void
   */
  private function init_modules() {

    $modules = array(
      'wordpress',
    );

    foreach ( $modules as $module ) {
      $module_path = $this->plugin_path() . '/includes/modules/' . $module . '.php';

      if ( file_exists( $module_path ) ) {
        require_once( $module_path );
      }
    }
  }

  /**
   * Register and include widgets
   *
   * @access  public
   * @return  void
   */
  public function register_widgets() {
    require_once( 'includes/widgets/widget_leaderboard.php' );
    require_once( 'includes/widgets/widget_my_achievements.php' );
    require_once( 'includes/widgets/widget_my_quests.php' );
    require_once( 'includes/widgets/widget_my_rank.php' );
    require_once( 'includes/widgets/widget_activity_code.php' );
    require_once( 'includes/widgets/widget_quest_progress.php' );

    do_action( 'wpachievements_widgets_registered' );
  }

  /**
   * Get Logging Class. Load on demand.
   *
   * @access public
   * @return WPAchievements_Logger
   */
  public function logger() {
    if ( empty( $this->logger ) ) {
      $this->logger = new WPAchievements_Logger();
    }

    return $this->logger;
  }

  /**
   * Instance of the WPAchievements_Achievement class
   *
   * @return WPAchievements_Achievement
   */
  public function achievement() {
    if ( ! is_a( $this->achievement, 'WPAchievements_Achievement' ) ) {
      $this->achievement = new WPAchievements_Achievement();
    }

    return $this->achievement;
  }

  /**
  * Retrieve the activity table name
  *
  * @return  string  Table name
  */
  public function get_table() {
    global $wpdb;

    if ( ! $this->table ) {
      if ( is_multisite() ) {
        $this->table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
      }
      else {
        $this->table = $wpdb->prefix.'achievements';
      }
    }

    return $this->table;
  }

  /**
   * Install
   *
   * @access  public
   * @return  void
   */
  public function install() {
    global $wpdb;

    $data = array( 0 => __( 'Newbie','wpachievements' ) );

    if ( is_multisite() ) {
      add_blog_option( 1, 'wpachievements_ranks_data', $data  );
      add_blog_option( 1, 'wpachievements_allow_tracking', 'unknown' );
    }
    else {
      add_option( 'wpachievements_ranks_data', $data );
      add_option( 'wpachievements_allow_tracking', 'unknown' );
    }

    if ( $wpdb->get_var("SHOW TABLES LIKE '".$this->get_table()."'") != $this->get_table() ) {
     $sql =
     "CREATE TABLE " . $this->get_table() . " (
      id bigint(20) NOT NULL AUTO_INCREMENT,
      uid bigint(20) NOT NULL,
      type VARCHAR(256) NOT NULL,
      rank TEXT NOT NULL,
      data TEXT NOT NULL,
      points bigint(20) NOT NULL,
      postid bigint(20) NOT NULL,
      timestamp varchar(200) NULL,
      UNIQUE KEY id (id)
      );";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }

    // Include settings so that we can run through defaults
    include_once( 'includes/admin/class-wpachievements-admin-settings.php' );

    $settings = WPAchievements_Admin_Settings::get_settings_pages();

    foreach ( $settings as $section ) {
      if ( ! method_exists( $section, 'get_settings' ) ) {
        continue;
      }
      $subsections = array_unique( array_merge( array( '' ), array_keys( $section->get_sections() ) ) );

      foreach ( $subsections as $subsection ) {
        foreach ( $section->get_settings( $subsection ) as $value ) {
          if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
            $autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
            add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
          }
        }
      }
    }

      // Add plugin installation date and variable for rating div
    add_option( 'wpachievements_install_date', date('Y-m-d h:i:s') );
    add_option( 'wpachievements_rating_div', 'no' );

    wp_schedule_event( time(), 'daily', 'wpachievements_tracker_send_event' );
  }

  /**
   * Uninstall plugin
   *
   * @access  public
   * @return  void
   */
  public static function uninstall() {
    global $wpdb;

    if ( is_multisite() ) {
      delete_blog_option(1,'wpachievements_achievements_data');
      delete_blog_option(1,'wpachievements_ranks_data');
      delete_blog_option(1,'wpach_of_template');
      delete_blog_option(1,'wpach_of_shortname');
      $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
    }
    else{
      delete_option('wpachievements_achievements_data');
      delete_option('wpachievements_ranks_data');
      delete_option('wpach_of_template');
      delete_option('wpach_of_shortname');
      $table = $wpdb->prefix.'achievements';
    }

    $wpdb->query(  $wpdb->prepare( "DROP TABLE {$table}" ) );
    $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE `achievements_count`" );
    $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE `achievements_gained`" );

    wp_clear_scheduled_hook( 'wpachievements_tracker_send_event' );
  }

  /**
   * What type of request is this?
   *
   * @access public
   * @param  string $type admin, ajax, cron or frontend.
   * @return bool
   */
  public function is_request( $type ) {
    switch ( $type ) {
      case 'admin':
        return is_admin();
      case 'ajax':
        return defined( 'DOING_AJAX' );
      case 'cron':
        return defined( 'DOING_CRON' );
      case 'frontend':
        return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
    }
  }

  /**
   * Get the plugin url.
   *
   * @return string
   */
  public function plugin_url() {
    return untrailingslashit( plugins_url( '/', __FILE__ ) );
  }

  /**
   * Get the plugin path.
   *
   * @return string
   */
  public function plugin_path() {
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
  }
}
endif;

/**
 * WPAchievements
 *
 * @return WPAchievements
 */
function WPAchievements() {
  return WPAchievements::instance();
}

WPAchievements();
