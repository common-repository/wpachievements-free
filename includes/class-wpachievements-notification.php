<?php
/**
* Handle pop up notifications
*
*/
class WPAchievements_Notification {

  /**
   * Register the init hook
   */
  public static function init() {
    add_action( 'init', array( __CLASS__, 'init_nofications' ) );
  }

  /**
   * Init notification hooks
   *
   * @return void
   */
  public static function init_nofications() {

    // Proceed only if the user is logged in
    if ( ! is_user_logged_in() || 'yes' != wpachievements_get_site_option( 'wpachievements_popup_notifications', 'yes' ) ) {
      return;
    }

    add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
    add_filter( 'heartbeat_received', array( __CLASS__, 'show_if_necessary' ), 10, 2 );
    add_action( 'wp_footer', array( __CLASS__, 'show_if_necessary' ) );
  }

  /**
   * Enqueue required scripts and styles for the frontend
   */
  public static function enqueue_scripts() {

    wp_register_style( 'wpachievements-fb-sharing-style', WPAchievements()->plugin_url() . '/includes/social/facebook/css/wpachievements_fb_sharing.css' );
    wp_enqueue_style( 'wpachievements-fb-sharing-style' );

    wp_register_style( 'wpachievements-twr-sharing-style', WPAchievements()->plugin_url() . '/includes/social/twitter/css/wpachievements_twr_sharing.css' );
    wp_enqueue_style( 'wpachievements-twr-sharing-style' );

    wp_register_style( 'wpachievements-notify-style', WPAchievements()->plugin_url() . '/includes/popup/css/MetroNotificationStyle.min.css' );

    wp_enqueue_style( 'wpachievements-notify-style' );

    if ( wpachievements_get_site_option('wpachievements_rtl_lang') == 'yes' ) {
      wp_register_style( 'wpachievements-notify-rtl-style', WPAchievements()->plugin_url() . '/includes/popup/css/MetroNotificationStyle.rtl.min.css' );
      wp_enqueue_style( 'wpachievements-notify-rtl-style' );
    }

    $ach_share = wpachievements_get_site_option( 'wpachievements_pshare' );
    $appId = wpachievements_get_site_option( 'wpachievements_appID' );

    if ( $ach_share == 'yes' && $appId ) {
      wp_register_script( 'wpachievements-notify-script', WPAchievements()->plugin_url() . '/includes/popup/js/MetroNotificationShare.js', array('jquery') );
    }
    else {
      wp_register_script( 'wpachievements-notify-script', WPAchievements()->plugin_url() . '/includes/popup/js/MetroNotification.js', array('jquery') );
    }

    wp_enqueue_script( 'wpachievements-notify-script' );

    if ( is_user_logged_in() ) {
      $pcheck = wpachievements_get_site_option('wpachievements_pcheck');
      wp_enqueue_script( 'wpachievements-notify-check', WPAchievements()->plugin_url() .  '/assets/js/notify-check.js', array( 'heartbeat' ) );
      wp_localize_script( 'wpachievements-notify-check', 'WPA_Ajax', array( 'userid' => get_current_user_id(), 'check_rate' => $pcheck  ) );
    }
  }

  /**
   * Show the notification pop up
   *
   * @param array $response
   * @param array $data
   * @return string
   */
  public static function show_if_necessary( $response = array(), $data = array() ) {

    if ( empty( $data['wpachievements-check'] ) ) {
      // Direct call
      if ( ! is_user_logged_in() ) {
        return $response;
      }

      $user_id = get_current_user_id();
    }
    else {
      // heart beat call
      $user_id = intval( $data['wpachievements-check'] );
    }

    $pending_notifications = (array) get_user_meta( $user_id, 'wpachievements_got_new_ach' );

    if ( ! $pending_notifications ) {
      return $response;
    }

    ob_start();

    if ( function_exists('wpachievements_fb_share_achievement_filter') ) {
      echo wpachievements_fb_share_achievement_filter('achievement');
    }

    do_action( 'wpachievements_before_show_achievement', $user_id, $pending_notifications );

    // Delete pending notifications
    delete_user_meta( $user_id, 'wpachievements_got_new_ach' );

    $popup_background_color = wpachievements_get_site_option( 'wpachievements_pcol', '#333333' );
    $popup_fadeout_time = intval( wpachievements_get_site_option( 'wpachievements_ptim' ));

    foreach( $pending_notifications as $notifications ) {
      foreach( $notifications as $notification ) {
        if ( empty( $notification['title'] ) || empty( $notification['image'] ) ) {
          continue;
        }

        do_action( 'wpachievements_before_achievement_popup', $notification );

        ?>
        <script type="text/javascript">
          jQuery(document).ready(function() {
            jQuery.smallBox({
              sound: "false",
              title: "<?php echo $notification['title']; ?>",
              content: "<?php echo str_replace( '"', '\'', $notification['text'] ); ?>",
              color: "<?php echo $popup_background_color; ?>",
              <?php
              if (  $popup_fadeout_time > 0 ) {
                if (  $popup_fadeout_time < 1000 ) {
                  echo 'timeout: "'.$popup_fadeout_time.'000",';
                }
                else{
                  echo 'timeout: "'.$popup_fadeout_time.'",';
                }
              }
              ?>
              img: "<?php echo $notification['image']; ?>",
              icon: "<?php echo WPACHIEVEMENTS_URL .'/includes/popup/img/medal.png'; ?>",
              extra_type: "achievement"
            });
            jQuery("#wp-admin-bar-wpachievements_points_menu").load('<?php echo home_url(''); ?> #wp-admin-bar-wpachievements_points_menu > *');
          });
        </script>
        <?php
      }
    }

    do_action( 'wpachievements_after_show_achievement', $user_id, $notification );

    if ( ! is_array( $response ) ) {
      $response = array();
    }

    $response['wpachievements-check'] = ob_get_clean();

    if ( empty( $data['wpachievements-check'] ) ) {
      echo $response['wpachievements-check'];
    }
    else {
      return $response;
    }
  }
}

WPAchievements_Notification::init();
