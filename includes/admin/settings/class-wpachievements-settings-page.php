<?php
/**
 * Settings Page/Tab
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPAchievements_Settings_Page' ) ) :

/**
 * WPAchievements_Settings_Page
 */
class WPAchievements_Settings_Page {

  protected $slug = 'wpachievements';
  protected $id    = 'page';
  protected $label = 'Pages';

  /**
   * Add this page to settings
   *
   * @version 1.0.0
   * @since   1.0.0
   * @access  public
   * @param   array $pages Pages
   * @return  array Pages
   */
  public function add_settings_page( $pages ) {
    $pages[ $this->id ] = $this->label;

    return $pages;
  }

  /**
   * Get settings array
   *
   * @version 1.0.0
   * @since   1.0.0
   * @access  public
   * @return array
   */
  public function get_settings() {
    return array();
  }

  /**
   * Get sections
   *
   * @return array
   */
  public function get_sections() {
    return apply_filters( $this->slug.'_get_sections_' . $this->id, array() );
  }

  /**
   * Output sections
   */
  public function output_sections() {
    global $current_section;

    $sections = $this->get_sections();
    
    if ( empty( $sections ) ) {
      return;
    }

    echo '<ul class="subsubsub">';

    $array_keys = array_keys( $sections );

    foreach ( $sections as $id => $label ) {
      echo '<li><a href="' . admin_url( 'edit.php?post_type='.$this->slug.'&page='.$this->slug.'_settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
    }

    echo '</ul><br class="clear" />';
  }

  /**
   * Output the settings
   */
  public function output() {
    global $current_section;
    
		$settings = $this->get_settings( $current_section );

    WPAchievements_Admin_Settings::output_fields( $settings );
  }

  /**
   * Save settings
   */
  public function save() {
    global $current_section;

    $settings = $this->get_settings($current_section);
    WPAchievements_Admin_Settings::save_fields( $settings );

     if ( $current_section ) {
        do_action( $this->slug.'_update_options_' . $this->id . '_' . $current_section );
     }
  }
} // END Class
endif;