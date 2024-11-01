<?php
/**
 * WPAchievements stats adds functionality to track plugin usage to help to identify
 * important functions and plugins that we can improve the plugin and extend our
 * integrations.
 *
 * Determinate important functions to be able to improve WPAchievements
 * and to be able to offer features our customers really need.
 */

// No direct access
if( ! defined( 'ABSPATH' ) ) {
  die();
}

class WPAchievements_Tracker {

  /**
   * Init function
   *
   * @access  public
   * @return  void
   */
  public static function init() {
    add_action( 'init', array( __CLASS__, 'wp_init' ) );
  }

  /**
   * Handle tracker requests
   */
  public static function wp_init() {
    $allow_tracking = wpachievements_get_site_option( 'wpachievements_allow_tracking' );

    // Tracking feature
    if ( 'yes' == $allow_tracking && WPAchievements()->is_request( 'ajax' ) ) {
      add_action( 'wpachievements_tracker_send_event', array( __CLASS__, 'send_tracking_data' ) );
    }

    if ( WPAchievements()->is_request( 'admin' ) ) {
      $current_page = filter_input( INPUT_GET, 'page' );

      if ( ( ! $allow_tracking || ( "unknown" == $allow_tracking ) ) && ( 'wpachievements_reports' != $current_page ) ) {
        add_action( 'admin_notices', array( __CLASS__, 'tracking_message' ) );
      }
      add_action( 'admin_init', array( __CLASS__, 'tracker_optin' ) );
    }
  }

  /**
   * Show stats opt in notice
   */
  public static function tracking_message( $hide_skip_button = false ) {
    ?>
    <div class="notice notice-success">
      <h3><?php _e("Help to improve WPAchievements!", 'wpachievements'); ?></h3>
      <p>
        <?php printf( __( 'Enable site repots to get a better overview of your visitors and your site activities. After enabling this feature you will see report charts and tables on the WPAchievements reports page. Additionally enabling this feature means making WPAchievements better &mdash; your site will be considered as we evaluate new features, judge the quality of an update, or determine if an improvement makes sense. WPAchievements will collect and send usage data to our site which will help us to make WPAchievements even better. %1$sRead more about what we collect%2$s.', 'wpachievements' ), '<a href="https://wpachievements.net/usage-tracking/" target="_blank">', '</a>' ); ?></p>
      <p class="submit">
        <a class="button-primary button button-large" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wpachievements_tracker_optin', 'true' ), 'wpachievements_tracker_optin', 'wpachievements_tracker_nonce' ) ); ?>"><?php esc_html_e( 'Enable', 'wpachievements' ); ?></a>
        <?php if ( ! $hide_skip_button ) : ?>
        <a class="button-secondary button button-large skip"  href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wpachievements_tracker_optout', 'true' ), 'wpachievements_tracker_optout', 'wpachievements_tracker_nonce' ) ); ?>"><?php esc_html_e( 'No thanks', 'wpachievements' ); ?></a>
        <?php endif; ?>
      </p>
    </div>
  <?php
  }

  /**
   * Handle tracker opt in/out
   */
  public static function tracker_optin() {
    $optin  = filter_input( INPUT_GET, 'wpachievements_tracker_optin' );
    $optout = filter_input( INPUT_GET, 'wpachievements_tracker_optout' );
    $nonce  = filter_input( INPUT_GET, 'wpachievements_tracker_nonce' );

    if ( $optin && wp_verify_nonce( $nonce, 'wpachievements_tracker_optin' ) ) {
      self::update_option( 'wpachievements_allow_tracking', 'yes' );
      remove_action( 'admin_notices', array( __CLASS__, 'tracking_message' ) );
      self::send_tracking_data();
    }
    elseif ( $optout && wp_verify_nonce( $nonce, 'wpachievements_tracker_optout' ) ) {
      self::update_option( 'wpachievements_allow_tracking', 'no' );
      remove_action( 'admin_notices', array( __CLASS__, 'tracking_message' ) );

      if ( is_multisite() ) {
        switch_to_blog(1);
      }

      delete_option( 'wpachievements_tracker_last_send' );

      if ( is_multisite() ) {
        restore_current_blog();
      }
    }
  }

  /**
   * Update tracker options
   *
   * @param string $option
   * @param string $value
   */
  private static function update_option( $option, $value ) {
    if ( is_multisite() ) {
      switch_to_blog(1);
    }

    // Update time first before sending to ensure it is set
    update_option( $option, $value );

    if ( is_multisite() ) {
      restore_current_blog();
    }
  }

  /**
   * Decide whether to send tracking data or not
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function send_tracking_data() {

    // Don't trigger this on AJAX Requests
    if ( defined( 'DOING_AJAX') && DOING_AJAX ) {
      return;
    }

    if ( self::get_last_send_time() <= strtotime( '-1 week' ) ) {
      $params = self::get_tracking_data();

      wp_remote_post( 'http://api.wpachievements.net/stats/', array(
          'method'      => 'POST',
          'timeout'     => 45,
          'blocking'    => false,
          'headers'     => array( 'user-agent' => 'WPAchievementsTracker/' . md5( esc_url( home_url( '/' ) ) ) . ';' ),
          'body'        => json_encode( $params ),
        )
      );

      // Update time first before sending to ensure it is set
      self::update_option( 'wpachievements_tracker_last_send', time() );
    }
  }

  /**
   * Get the last time tracking data was sent
   *
   * @static
   * @access  private
   * @return  int Timestamp
   */
  private static function get_last_send_time() {
    return wpachievements_get_site_option( 'wpachievements_tracker_last_send', false );
  }

  /**
   * Get all the tracking data
   *
   * @static
   * @access  protected
   * @return  array Array of tracking data
   */
  protected static function get_tracking_data() {

    $data = array();

    // General site info
    $data['url']      = home_url();

    // Server Info
    $data['server']   = self::get_server_info();

    // WordPress Info
    $data['wp']       = self::get_wordpress_info();

    // Theme Info
    $data['theme']    = self::get_theme_info();

    // Plugin Info
    $data['plugins']  = self::get_active_plugins();

    return $data;
  }

  /**
   * Get the current theme info, theme name and version
   * to improve WPAchievements compatibility with most used themes.
   *
   * @static
   * @access  private
   * @return  array
   */
  private static function get_theme_info() {

    $theme_data = wp_get_theme();

    return array(
      'name'        => $theme_data->get('Name'),
      'url'         => $theme_data->get('ThemeURI'),
      'version'     => $theme_data->get('Version'),
      'parent_name' => $theme_data->parent() ? $theme_data->parent()->get('Name') : '',
      'parent_url'  => $theme_data->parent() ? $theme_data->parent()->get('ThemeURI') : '',
      'parent_version' => $theme_data->parent() ? $theme_data->parent()->get('Version') : '',
    );
  }

  /**
   * Get WordPress related data be able to optimize
   * WPAchievements memory usage and to know which older WP versions
   * we still need to support.
   *
   * @static
   * @access  private
   * @return  array
   */
  private static function get_wordpress_info() {

    $wp_data = array(
      'name'      => get_bloginfo( 'name' ),
      'locale'    => get_locale(),
      'version'   => get_bloginfo( 'version' ),
      'multisite' => is_multisite() ? 'Yes' : 'No',
    );

    return $wp_data;
  }

  /**
   * Get Server related info to make sure that
   * our plugin will work properly on all user servers.
   *
   * @static
   * @access  private
   * @return  array
   */
  private static function get_server_info() {
    global $wpdb;

    $server_data = array();

    if ( isset( $_SERVER['SERVER_SOFTWARE'] ) && ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
      $server_data['software'] = $_SERVER['SERVER_SOFTWARE'];
    }

    if ( function_exists( 'phpversion' ) ) {
      $server_data['php_version'] = phpversion();
    }

    $memory = self::let_to_num( WP_MEMORY_LIMIT );

    if ( function_exists( 'ini_get' ) ) {

      if ( function_exists( 'memory_get_usage' ) ) {
        $system_memory = self::let_to_num( @ini_get( 'memory_limit' ) );
        $memory        = max( $memory, $system_memory );
      }

      $server_data['php_post_max_size'] = size_format( self::let_to_num( ini_get( 'post_max_size' ) ) );
      $server_data['php_time_limt'] = ini_get( 'max_execution_time' );
      $server_data['php_max_input_vars'] = ini_get( 'max_input_vars' );
    }

    $server_data['memory_limit']        = size_format( $memory );
    $server_data['php_max_upload_size'] = size_format( wp_max_upload_size() );
    $server_data['mysql_version']       = $wpdb->db_version();

    return $server_data;
  }

  /**
   * Get all active plugins to make sure that WPAchievements
   * is working correctly with most used plugins
   *
   * @static
   * @access  private
   * @return  array
   */
  private static function get_active_plugins() {
    // Ensure get_plugins function is loaded
    if( ! function_exists( 'get_plugins' ) ) {
      include ABSPATH . '/wp-admin/includes/plugin.php';
    }

    $plugins  = get_plugins();
    $active_plugins_keys = get_option( 'active_plugins', array() );
    $active_plugins = array();

    foreach ( $plugins as $k => $v ) {
      // Take care of formatting the data how we want it.
      $formatted = array();
      $formatted['name'] = strip_tags( $v['Name'] );
      if ( isset( $v['Version'] ) ) {
        $formatted['version'] = strip_tags( $v['Version'] );
      }
      if ( isset( $v['PluginURI'] ) ) {
        $formatted['plugin_uri'] = strip_tags( $v['PluginURI'] );
      }
      if ( in_array( $k, $active_plugins_keys ) ) {
        // Remove active plugins from list so we can show active and inactive separately
        $active_plugins[$k] = $formatted;
      }
    }

    return $active_plugins;
  }

  /**
   * Transform the php.ini notation for numbers (like '2M') to an integer.
   *
   * @static
   * @access  public
   * @param   string $size php.ini notation for numbers
   * @return  integer
   */
  public static function let_to_num( $size ) {
    $l   = substr( $size, -1 );
    $ret = substr( $size, 0, -1 );

    switch ( strtoupper( $l ) ) {
      case 'P':
        $ret *= 1024;
      case 'T':
        $ret *= 1024;
      case 'G':
        $ret *= 1024;
      case 'M':
        $ret *= 1024;
      case 'K':
        $ret *= 1024;
    }

    return $ret;
  }
}

WPAchievements_Tracker::init();
