<?php
/**
 * Handle frontend and backend scripts
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class WPAchievements_Scripts {

  /**
   * Hook in methods
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function init() {
    add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontend_scripts' ) );
    add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );

  }

  /**
   * Load frontend scripts and styles
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function frontend_scripts() {

    $suffix = ( 'yes' == wpachievements_get_site_option( 'wpachievements_rtl_lang' ) ) ? '-rtl' : '';

    wp_register_style( 'wpachievements-style', WPAchievements()->plugin_url() . '/assets/css/style' . $suffix . '.css' );
    wp_enqueue_style( 'wpachievements-style' );

    wp_register_style( 'wpachievements-gridtab-style', WPAchievements()->plugin_url() . '/assets/js/gridtab/gridtab.min.css' );
    wp_enqueue_style( 'wpachievements-gridtab-style' );

    wp_register_style( 'wpachievements-fontawesome', WPAchievements()->plugin_url() . '/assets/css/fontawesome-all.min.css' );
    wp_enqueue_style( 'wpachievements-fontawesome' );

    wp_register_script( 'wpachievements-achievements-list', WPAchievements()->plugin_url() . '/assets/js/script.js', array('jquery'), null, true );
    wp_localize_script( 'wpachievements-achievements-list', 'wpa_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_register_script( 'wpachievements-gridtab', WPAchievements()->plugin_url() . '/assets/js/gridtab/gridtab.min.js', array('jquery'), null, true );

    wp_enqueue_script( 'wpachievements-achievements-list' );

    if ( is_user_logged_in() ) {
      if ( "Disable" != wpachievements_get_site_option( 'wpachievements_rank_status' ) ) {
        wp_enqueue_script( 'WPachievements_Rank_Update_Script', WPAchievements()->plugin_url() . '/assets/js/front-ranks-script.js', array('jquery'), null, true );
      }
    }
  }

  /**
   * Load admin scripts and styles
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function admin_scripts() {}
}

WPAchievements_Scripts::init();