<?php
/**
 * General Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPAchievements_Settings_Achievements_Integration' ) ) :

class WPAchievements_Settings_Achievements_Integration extends WPAchievements_Settings_Page {

  /**
   * Constructor.
   */
  public function __construct() {
    $this->slug  = 'wpachievements';
    $this->id    = 'module';
    $this->label = __( 'Module Integration', $this->slug );

    add_filter( $this->slug.'_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
    add_action( $this->slug.'_settings_' . $this->id, array( $this, 'output' ) );
    add_action( $this->slug.'_sections_' . $this->id, array( $this, 'output_sections' ) );
    add_action( $this->slug.'_settings_save_' . $this->id, array( $this, 'save' ) );
  }

  /**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			'' => __( 'Wordpress', 'woocommerce' )
		);
		return apply_filters( 'wpachievements_get_sections_' . $this->id, $sections );
	}

  /**
   * Get settings array
   *
   * @version 2.0.0
   * @since   1.0.0
   * @access  public
   * @return array
   */
  public function get_settings( $current_section = '' ) {

    $settings = array();

    $settings = apply_filters( $this->slug.'_achievements_modules_admin_settings', $settings, $this->slug, $current_section ); 

    return $settings;
  }

  /**
   * Save settings
   */
  public function save() {
    global $current_section;
    
    $settings = $this->get_settings( $current_section );

    WPAchievements_Admin_Settings::save_fields( $settings );
  }
}
endif;

return new WPAchievements_Settings_Achievements_Integration();
