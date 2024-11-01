<?php
/**
 * General Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPAchievements_Settings_General' ) ) :

class WPAchievements_Settings_General extends WPAchievements_Settings_Page {

  /**
   * Constructor.
   */
  public function __construct() {
    $this->slug  = 'wpachievements';
    $this->id    = 'general';
    $this->label = __( 'General Settings', $this->slug );

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
      '' => __( 'General Options', 'wpachievements' ),
      'achievement_popup_options' => __( 'Achievement Popup Options', 'wpachievements' ),
      'facebook_options' => __( 'Facebook Options', 'wpachievements' ),
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

    if ( $current_section == '' ) {
      $tt_pages = array(0 => "None");
      $tt_pages_obj = get_pages( array( 'sort_column' => 'post_title' ) );
      foreach ($tt_pages_obj as $tt_page) {
        $tt_pages[$tt_page->ID] = $tt_page->post_title;
      }

      return apply_filters( $this->slug.'_general_settings', array(
        array( 'title' => __( 'General Options', $this->slug ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

        array(
          'title'   => __( 'Purchase Code', $this->slug ),
          'desc'    => __( 'This is required to get future updates.', $this->slug ),
          'id'      => $this->slug.'_license_key',
          'type'    => 'text',
          'default' => '',
        ),

        array(
          'title'   => __( 'User Role', $this->slug ),
          'desc'    => __( 'Select the minimum user role that can modify WPAchievements.', $this->slug ),
          'id'      => $this->slug.'_role',
          'type'    => 'select',
          'default' => 'Administrator',
          'css'     => 'min-width:300px;',
          'options' => array(
            'Administrator'  => __('Administrator', $this->slug),
            'Editor'  => __('Editor', $this->slug),
            'Author'  => __('Author', $this->slug),
            'Contributor' => __('Contributor', $this->slug),
          ),
        ),

        array(
          'title'   => __( 'User Ranks', $this->slug ),
          'desc'    => __( 'Select whether you want to use the user ranks system.', $this->slug ),
          'id'      => $this->slug.'_rank_status',
          'type'    => 'select',
          'default' => 'Enable',
          'css'     => 'min-width:300px;',
          'options' => array(
            'Enable'  => __('Enable', $this->slug),
            'Disable'  => __('Disable', $this->slug),
          ),
        ),

        array(
          'title'   => __( 'Rank Type', $this->slug ),
          'desc'    => __( 'Select whether you want to use points or achievements for the user ranks system.', $this->slug ),
          'id'      => $this->slug.'_rank_type',
          'type'    => 'select',
          'default' => 'Points',
          'css'     => 'min-width:300px;',
          'options' => array(
            'Points'  => __('Points', $this->slug),
            'Achievements'  => __('Achievements', $this->slug),
          ),
        ),

        array(
          'title'   => __( 'Negative Points', $this->slug ),
          'desc'    => __( 'Enable this if you want to allow negative point balace (e.g. -100 Points).', $this->slug ),
          'id'      => $this->slug.'_negative_points',
          'type'    => 'checkbox',
          'default' => '',
        ),

        array(
          'title'   => __( 'Hide Admins from Leaderboard', $this->slug ),
          'desc'    => __( 'Select whether to hide admins from the leaderboards.', $this->slug ),
          'id'      => $this->slug.'_hide_admin',
          'type'    => 'checkbox',
          'default' => '',
        ),
        array(
          'title'   => __( 'Achievements Page', $this->slug ),
          'desc'    => __( 'Select which page to use to display the custom Achievements page.', $this->slug ),
          'id'      => $this->slug.'_ach_page',
          'type'    => 'select',
          'default' => '0',
          'css'     => 'min-width:300px;',
          'options' => $tt_pages,
        ),

        array(
          'title'   => __( 'Hide Similar Achievements', $this->slug ),
          'desc'    => __( 'Example: If user has achievement for 10 comments and for 20 comments, only 20 comment achievement will be shown in widgets, shortcodes etc.', $this->slug ),
          'id'      => $this->slug.'_sim_ach',
          'type'    => 'checkbox',
          'default' => '',
        ),

        array(
          'title'   => __( 'RTL Language', $this->slug ),
          'desc'    => __( 'Select whether you are translating the plugin into a RTL Language.', $this->slug ),
          'id'      => $this->slug.'_rtl_lang',
          'type'    => 'checkbox',
          'default' => '',
        ),

        array(
          'title'   => __( 'Hide Points Column in User Admin Overview', $this->slug ),
          'desc'    => __( 'Select whether to hide points columm in user profile overview.', $this->slug ),
          'id'      => $this->slug.'_hide_userpoint_profile_column',
          'type'    => 'checkbox',
          'default' => '',
        ),

        array(
          'title'   => __( 'Hide gained achievments in User Admin Overview', $this->slug ),
          'desc'    => __( 'Select whether to hide gained achievments columm in user profile overview.', $this->slug ),
          'id'      => $this->slug.'_hide_userachievments_profile_column',
          'type'    => 'checkbox',
          'default' => '',
        ),

        array(
          'title'   => __( 'Enable shortcode editor', $this->slug ),
          'desc'    => __( 'Enable this option to display a shortcode editor on page and post admin pages', $this->slug ),
          'id'      => $this->slug.'_shortcode_editor',
          'type'    => 'checkbox',
          'default' => 'yes',
        ),

      array( 'type' => 'sectionend', 'id' => 'general_options'),
    ), $current_section ); // End general settings
	}

  if ( $current_section == 'achievement_popup_options' ) {

    return apply_filters( $this->slug.'_achievement_popup_settings', array(
      array( 'title' => __( 'Achievement Popup Options', $this->slug ), 'type' => 'title', 'desc' => '', 'id' => 'achievement_popup_options' ),

        array(
          'title'   => __( 'Enable Notifications', $this->slug ),
          'desc'    => __( 'Select whether to enable or disable popup notifications.', $this->slug ),
          'id'      => $this->slug.'_popup_notifications',
          'type'    => 'checkbox',
          'default' => 'yes',
        ),

        array(
          'title'   => __( 'Popup Automatic Checks', $this->slug ),
          'desc'    => __( 'Choose the number of seconds in between automatic checks for achievements. (Enter 0 to disable)  <strong>NOTE:</strong> This can cause speed issues.', $this->slug ),
          'id'      => $this->slug.'_pcheck',
          'type'    => 'text',
          'default' => '5',
        ),

        array(
          'title'   => __( 'Show Sharing Buttons', $this->slug ),
          'desc'    => __( 'Select whether to show the Facebook and Twitter buttons.<br/><strong>NOTE:</strong> Facebook App info must be entered.', $this->slug ),
          'id'      => $this->slug.'_pshare',
          'type'    => 'checkbox',
          'default' => '',
        ),

        array(
          'title'   => __( 'Popup Time', $this->slug ),
          'desc'    => __( 'Enter the number of seconds before the popup box fades away.<br/>(Enter 0 to disable fade away)', $this->slug ),
          'id'      => $this->slug.'_ptim',
          'type'    => 'text',
          'default' => '0',
        ),

      array(
          'title'   => __( 'Popup background Color', $this->slug ),
          'desc'    => __( 'Choose the colour of the popup box.', $this->slug ),
          'id'      => $this->slug.'_pcol',
          'type'    => 'color',
          'default' => '#333333',
        ),

      array( 'type' => 'sectionend', 'id' => 'achievement_popup_options'),
    ), $current_section ); // End achievement popup settings
	}

  if ( $current_section == 'facebook_options' ) {

    return apply_filters( $this->slug.'_facebook_settings', array(

      array( 'title' => __( 'Facebook Options', $this->slug ), 'type' => 'title', 'desc' => 'For the Facebook capabilities to work correctly you need to create a <a href="https://developers.facebook.com/" target="_blank">Facebook Application</a><br /><br />To do so, follow these steps:<ul><li><span>Click on "My Apps" -> "Add a New App" and select "Website"</span></li><li><span>Enter your App name (e.g WPAchievements) and click on "Create New Facebook App ID"</span></li><li><span>On Category select "Apps for pages" and click "Create App-ID"</span></li><li><span>Scroll to section "Tell us about your website" and enter your site URL: <strong>'.home_url().'</strong></span></li><li><span>Scroll to "Next Steps" and click on "Skip to Developer Dashboard"</span></li><li><span>Copy the values from the field: App ID/ and App Secret, and enter them below:</span></li></ul><br />Check our detailed documentation if you have difficulties to follow the steps above. There you will find screenshots for every single step.<br />', 'id' => 'facebook_options' ),

        array(
          'title'   => __( 'Facebook App ID/API Key', $this->slug ),
          'desc'    => __( 'Enter the App ID/API Key from your Facebook App.', $this->slug ),
          'id'      => $this->slug.'_appID',
          'type'    => 'text',
          'default' => '',
        ),

        array(
          'title'   => __( 'Facebook App Secret', $this->slug ),
          'desc'    => __( 'Enter the App Secret from your Facebook App.', $this->slug ),
          'id'      => $this->slug.'_appSecret',
          'type'    => 'text',
          'default' => '',
        ),

        array( 'type' => 'sectionend', 'id' => 'facebook_options'),
      ), $current_section ); // End facebook settings
    }
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

return new WPAchievements_Settings_General();
