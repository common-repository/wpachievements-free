<?php
/**
 * Shortcodes
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class WPAchievements_Shortcodes {

  /**
   * Init shortcodes.
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function init() {
    $shortcodes = array(
      'wpa_myachievements'      => __CLASS__ . '::myachievements',
      'wpa_myquests'            => __CLASS__ . '::myquests',
      'wpa_myranks'             => __CLASS__ . '::myranks',
      'wpa_mypoints'            => __CLASS__ . '::mypoints',

      'wpa_leaderboard'         => __CLASS__ . '::leaderboard',
      'wpa_leaderboard_list'    => __CLASS__ . '::leaderboard_list',
      'wpa_leaderboard_widget'  => __CLASS__ . '::leaderboard_widget',

      'wpa_custom_achievement'  => __CLASS__ . '::custom_achievement',
      'wpa_rank_achievements'   => __CLASS__ . '::rank_achievements',
      'wpa_activity_code'       => __CLASS__ . '::activity_code',
      'wpa_quest_steps'         => __CLASS__ . '::quest_steps',

      'wpa_achievements'        => __CLASS__ . '::achievements',
      'wpa_quests'              => __CLASS__ . '::quests',

      'wpa_achievement'         => __CLASS__ . '::achievement',
      'wpa_quest'               => __CLASS__ . '::quest',

      'wpa_if_achievement'      => __CLASS__ . '::conditional',
      'wpa_if_quest'            => __CLASS__ . '::conditional',
      'wpa_if_rank'             => __CLASS__ . '::conditional',
    );

    foreach ( $shortcodes as $shortcode => $function ) {
      add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
    }
  }

  /**
   * Get shortcode parameters for shortcode editor
   *
   * @static
   * @access  public
   * @return  array
   */
  public static function get_parameters() {
    $parameters = array();

    //[wpa_myachievements achievement_limit="" show_title="" image_width="" title_heading="" title_class="" image_holder_class="" list_class="" list_element_class="" image_class=""]
    $shortcode = 'wpa_myachievements';
    $parameters[ $shortcode ] = array(
      'title' => __("My Achievements", 'wpachievements' ),
      'description' => __( "Show achievements gained by user", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

        array(
          'title'     => __( 'Limit Achievements', 'wpachievements' ),
          'desc'      => __( 'Limit displayed achievements (-1 = all achievements).', 'wpachievements' ),
          'id'        => $shortcode . '__achievement_limit',
          'type'      => 'number',
          'default'   => '-1',
        ),
        array(
          'title'     => __( 'Show Title', 'wpachievements' ),
          'desc'      => __( 'Display the achievement title.', 'wpachievements' ),
          'id'        => $shortcode . '__show_title',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Image Width', 'wpachievements' ),
          'desc'      => __( 'Achievements image width in px.', 'wpachievements' ),
          'id'        => $shortcode . '__image_width',
          'type'      => 'number',
          'default'   => '30',
        ),

        array( 'type' => 'sectionend', 'id' => 'general_options'),
        array( 'title' => __( 'Styling Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'styling_options' ),

        array(
          'title'     => __( 'Title Heading', 'wpachievements' ),
          'desc'      => __( 'Select Heading Level for the title.', 'wpachievements' ),
          'id'        => $shortcode . '__title_heading',
          'type'      => 'select',
          'options'   => array(
            'h1' => __( 'H1', 'wpachievements'),
            'h2' => __( 'H2', 'wpachievements' ),
            'h3' => __( 'H3', 'wpachievements' ),
            'h4' => __( 'H4', 'wpachievements' ),
            'h5' => __( 'H5', 'wpachievements' ),
            'h6' => __( 'H6', 'wpachievements' ),
          ),
          'default'   => 'h3',
        ),

        array(
          'title'     => __( 'Title CSS Class', 'wpachievements' ),
          'desc'      => __( 'Optional CSS class to fit your design.', 'wpachievements' ),
          'id'        => $shortcode . '__title_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Output Achievement Wrap Class', 'wpachievements' ),
          'desc'      => __( 'Wrapper CSS class for the all achievements.', 'wpachievements' ),
          'id'        => $shortcode . '__image_holder_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'List Class', 'wpachievements' ),
          'desc'      => __( 'Wrapper CSS class for the achievement list.', 'wpachievements' ),
          'id'        => $shortcode . '__list_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'List Element Class', 'wpachievements' ),
          'desc'      => __( 'CSS class for the achievement list element.', 'wpachievements' ),
          'id'        => $shortcode . '__list_element_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Image Class', 'wpachievements' ),
          'desc'      => __( 'CSS class for the achievement image.', 'wpachievements' ),
          'id'        => $shortcode . '__image_class',
          'type'      => 'text',
        ),

        array( 'type' => 'sectionend', 'id' => 'styling_options'),
      ),
    );

    //[wpa_myquests quest_limit="" show_title="" image_width="" title_heading="" title_class="" image_holder_class="" list_class="" list_element_class="" image_class=""]
    $shortcode = 'wpa_myquests';
    $parameters[ $shortcode ] = array(
      'title' => __("My Quests", 'wpachievements' ),
      'description' => __( "Show quests solved by user", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

        array(
          'title'     => __( 'Limit Quests', 'wpachievements' ),
          'desc'      => __( 'Limit displayed quests (-1 = all quests).', 'wpachievements' ),
          'id'        => $shortcode . '__quest_limit',
          'type'      => 'number',
          'default'   => '-1',
        ),
        array(
          'title'     => __( 'Show Title', 'wpachievements' ),
          'desc'      => __( 'Display the achievement title.', 'wpachievements' ),
          'id'        => $shortcode . '__show_title',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Image Width', 'wpachievements' ),
          'desc'      => __( 'Achievements image width in px.', 'wpachievements' ),
          'id'        => $shortcode . '__image_width',
          'type'      => 'number',
          'default'   => '30',
        ),

        array( 'type' => 'sectionend', 'id' => 'general_options'),
        array( 'title' => __( 'Styling Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'styling_options' ),

        array(
          'title'     => __( 'Title Heading', 'wpachievements' ),
          'desc'      => __( 'Select Heading Level for the title.', 'wpachievements' ),
          'id'        => $shortcode . '__title_heading',
          'type'      => 'select',
          'options'   => array(
            'h1' => __( 'H1', 'wpachievements'),
            'h2' => __( 'H2', 'wpachievements' ),
            'h3' => __( 'H3', 'wpachievements' ),
            'h4' => __( 'H4', 'wpachievements' ),
            'h5' => __( 'H5', 'wpachievements' ),
            'h6' => __( 'H6', 'wpachievements' ),
          ),
          'default'   => 'h3',
        ),

        array(
          'title'     => __( 'Title CSS Class', 'wpachievements' ),
          'desc'      => __( 'Optional CSS class to fit your design.', 'wpachievements' ),
          'id'        => $shortcode . '__title_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Output Achievement Wrap Class', 'wpachievements' ),
          'desc'      => __( 'Wrapper CSS class for the all achievements.', 'wpachievements' ),
          'id'        => $shortcode . '__image_holder_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'List Class', 'wpachievements' ),
          'desc'      => __( 'Wrapper CSS class for the achievement list.', 'wpachievements' ),
          'id'        => $shortcode . '__list_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'List Element Class', 'wpachievements' ),
          'desc'      => __( 'CSS class for the achievement list element.', 'wpachievements' ),
          'id'        => $shortcode . '__list_element_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Image Class', 'wpachievements' ),
          'desc'      => __( 'CSS class for the achievement image.', 'wpachievements' ),
          'id'        => $shortcode . '__image_class',
          'type'      => 'text',
        ),

        array( 'type' => 'sectionend', 'id' => 'styling_options'),
      ),
    );

    //[wpa_myranks show_title="" title_class="" rank_image=""]
    $shortcode = 'wpa_myranks';
    $parameters[ $shortcode ] = array(
      'title' => __("My Rank", 'wpachievements' ),
      'description' => __( "Show user's current rank", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Show Title', 'wpachievements' ),
          'desc'      => __( 'Display the rank title.', 'wpachievements' ),
          'id'        => $shortcode . '__show_title',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Show Rank Image', 'wpachievements' ),
          'desc'      => __( 'Display the rank image.', 'wpachievements' ),
          'id'        => $shortcode . '__rank_image',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array( 'type' => 'sectionend', 'id' => 'general_options'),
        array( 'title' => __( 'Styling Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'styling_options' ),
        array(
          'title'     => __( 'Title CSS Class', 'wpachievements' ),
          'desc'      => __( 'Optional CSS class to fit your design.', 'wpachievements' ),
          'id'        => $shortcode . '__title_class',
          'type'      => 'text',
        ),
        array( 'type' => 'sectionend', 'id' => 'styling_options'),
      ),
    );

    //[wpa_myranks show_title="" title_class="" rank_image=""]
    $shortcode = 'wpa_mypoints';
    $parameters[ $shortcode ] = array(
      'title' => __("My Points", 'wpachievements' ),
      'description' => __( "Show user's points", "wpachievements" ),
      'fields' => array(),
    );

    //[wpa_leaderboard_list list_class="" limit="10" type="" user_position="true" user_ranking="true"]
    $shortcode = 'wpa_leaderboard_list';
    $parameters[ $shortcode ] = array(
      'title' => __("Leaderboard List", 'wpachievements' ),
      'description' => __( "Show an unformatted leaderboard list", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Order Type', 'wpachievements' ),
          'desc'      => __( 'Whether to order the leaderboard by amount of points or achievements.', 'wpachievements' ),
          'id'        => $shortcode . '__type',
          'type'      => 'select',
          'options'   => array(
            'Points' => __( 'Points', 'wpachievements'),
            'Achievements' => __( 'Achievements', 'wpachievements' ),
          ),
          'default'   => 'Points',
        ),
        array(
          'title'     => __( 'User Position', 'wpachievements' ),
          'desc'      => __( 'Whether to show the trophy icons/place numbering.', 'wpachievements' ),
          'id'        => $shortcode . '__user_position',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'User Rank', 'wpachievements' ),
          'desc'      => __( 'Whether to show the users rank information.', 'wpachievements' ),
          'id'        => $shortcode . '__user_ranking',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Limit Users', 'wpachievements' ),
          'desc'      => __( 'Limit the number of users shown.', 'wpachievements' ),
          'id'        => $shortcode . '__limit',
          'type'      => 'number',
          'default'   => '10',
        ),
        array( 'type' => 'sectionend', 'id' => 'general_options'),
        array( 'title' => __( 'Styling Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'styling_options' ),
        array(
          'title'     => __( 'List CSS Class', 'wpachievements' ),
          'desc'      => __( 'Optional list CSS class to fit your design.', 'wpachievements' ),
          'id'        => $shortcode . '__list_class',
          'type'      => 'text',
          'default'   => '',
        ),
        array( 'type' => 'sectionend', 'id' => 'styling_options'),
      ),
    );

    //[wpa_leaderboard_widget limit="" type=""]
    $shortcode = 'wpa_leaderboard_widget';
    $parameters[ $shortcode ] = array(
      'title' => __("Leaderboard Widget", 'wpachievements' ),
      'description' => __( "Show a leaderboard in widget style.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Order Type', 'wpachievements' ),
          'desc'      => __( 'Whether to order the leaderboard by amount of points or achievements.', 'wpachievements' ),
          'id'        => $shortcode . '__type',
          'type'      => 'select',
          'options'   => array(
            'Points' => __( 'Points', 'wpachievements'),
            'Achievements' => __( 'Achievements', 'wpachievements' ),
          ),
          'default'   => 'Points',
        ),
        array(
          'title'     => __( 'Limit Users', 'wpachievements' ),
          'desc'      => __( 'Limit the number of users shown.', 'wpachievements' ),
          'id'        => $shortcode . '__limit',
          'type'      => 'number',
          'default'   => '10',
        ),
        array( 'type' => 'sectionend', 'id' => 'general_options'),
      ),
    );

    //[wpa_leaderboard list_class="" limit="10" achievement_limit="10" quest_limit="10" position_numbers="true" columns="avatar,points,rank,achievements,quests"]
    $shortcode = 'wpa_leaderboard';
    $parameters[ $shortcode ] = array(
      'title' => __("Sortable Leaderboard Table", 'wpachievements' ),
      'description' => __( "Show a leaderboard in widget style.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'User Position', 'wpachievements' ),
          'desc'      => __( 'Whether to show leaderboard position numbering.', 'wpachievements' ),
          'id'        => $shortcode . '__position_numbers',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Limit Achievements', 'wpachievements' ),
          'desc'      => __( 'Limit the number of achievements shown.', 'wpachievements' ),
          'id'        => $shortcode . '__achievement_limit',
          'type'      => 'number',
          'default'   => '10',
        ),
        array(
          'title'     => __( 'Limit Quests', 'wpachievements' ),
          'desc'      => __( 'Limit the number of quests shown.', 'wpachievements' ),
          'id'        => $shortcode . '__quest_limit',
          'type'      => 'number',
          'default'   => '10',
        ),
        array(
          'title'     => __( 'Limit Users', 'wpachievements' ),
          'desc'      => __( 'Limit the number of users shown.', 'wpachievements' ),
          'id'        => $shortcode . '__limit',
          'type'      => 'number',
          'default'   => '10',
        ),
        array(
          'title'     => __( 'Table Columns', 'wpachievements' ),
          'desc'      => __( 'Select which columns to display.', 'wpachievements' ),
          'id'        => $shortcode . '__columns',
          'type'      => 'multiselect',
          'options'   => array(
            'avatar' => __( 'User Avatar', 'wpachievements' ),
            'points' => __( 'Number of points', 'wpachievements' ),
            'rank'   => __( 'Current user rank', 'wpachievements' ),
            'achievements' => __( 'Achievements badges', 'wpachievements' ),
            'quests' => __( 'Quest badges', 'wpachievements' ),
          ),
          'default'   => array( 'avatar','points','rank','achievements','quests' ),
        ),
        array( 'type' => 'sectionend', 'id' => 'general_options'),
        array( 'title' => __( 'Styling Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'styling_options' ),
        array(
          'title'     => __( 'List CSS Class', 'wpachievements' ),
          'desc'      => __( 'Optional list CSS class to fit your design.', 'wpachievements' ),
          'id'        => $shortcode . '__list_class',
          'type'      => 'text',
          'default'   => '',
        ),
        array( 'type' => 'sectionend', 'id' => 'styling_options'),
      ),
    );

    //[wpa_custom_achievement trigger_id="" type="button" text="__('Gain Achievement', 'wpachievements')"]
    $shortcode = 'wpa_custom_achievement';
    $parameters[ $shortcode ] = array(
      'title' => __("Custom Achievement", 'wpachievements' ),
      'description' => __( "Generates triggers for custom achievements.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Custom Achievement', 'wpachievements' ),
          'desc'      => __( 'Select a custom achievement.', 'wpachievements' ),
          'id'        => $shortcode . '__trigger_id',
          'type'      => 'custom_achievement_select',
        ),
        array(
          'title'     => __( 'Type', 'wpachievements' ),
          'desc'      => __( 'Whether to produce a button or trigger the achievement when the post/page loads.', 'wpachievements' ),
          'id'        => $shortcode . '__type',
          'type'      => 'select',
          'options'   => array(
            'button'  => __( 'Button', 'wpachievements' ),
            'instant' => __( 'Instant', 'wpachievements' ),
          ),
          'default'   => 'button',
        ),
        array(
          'title'     => __( 'Button Text', 'wpachievements' ),
          'desc'      => __( 'If the type "Button" is choosen then this text is displayed within the button.', 'wpachievements' ),
          'id'        => $shortcode . '__text',
          'type'      => 'text',
          'default'   => __( 'Gain Achievement', 'wpachievements' ),
        ),
        array( 'type' => 'sectionend', 'id' => 'general_options'),
      ),
    );

    //[wpa_rank_achievements rank="" show_title="" title_class="" image_holder_class="" image_class="" image_width="" achievement_limit=""]
    $shortcode = 'wpa_rank_achievements';
    $parameters[ $shortcode ] = array(
      'title' => __("Achievements by Rank", 'wpachievements' ),
      'description' => __( "Display a list of achievements available for the choosen rank.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Rank', 'wpachievements' ),
          'desc'      => __( 'Select a rank to list achievements for.', 'wpachievements' ),
          'id'        => $shortcode . '__rank',
          'type'      => 'rank_select',
        ),
        array(
          'title'     => __( 'Limit Achievements', 'wpachievements' ),
          'desc'      => __( 'Limit the number of achievements shown.', 'wpachievements' ),
          'id'        => $shortcode . '__achievement_limit',
          'type'      => 'number',
          'default'   => '10',
        ),
        array(
          'title'     => __( 'Image Width', 'wpachievements' ),
          'desc'      => __( 'Quest image width in px.', 'wpachievements' ),
          'id'        => $shortcode . '__image_width',
          'type'      => 'number',
          'default'   => '30',
        ),
        array(
          'title'     => __( 'Show Title', 'wpachievements' ),
          'desc'      => __( 'Display the rank title.', 'wpachievements' ),
          'id'        => $shortcode . '__show_title',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array( 'type' => 'sectionend', 'id' => 'general_options'),
        array( 'title' => __( 'Styling Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'styling_options' ),
        array(
          'title'     => __( 'Title CSS Class', 'wpachievements' ),
          'desc'      => __( 'Optional CSS class to fit your design.', 'wpachievements' ),
          'id'        => $shortcode . '__title_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Image Wrap Class', 'wpachievements' ),
          'desc'      => __( 'Wrapper CSS class for the quest image.', 'wpachievements' ),
          'id'        => $shortcode . '__image_holder_class',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Image Class', 'wpachievements' ),
          'desc'      => __( 'CSS class for the quest image.', 'wpachievements' ),
          'id'        => $shortcode . '__image_class',
          'type'      => 'text',
          'default'   => 'wpa_a_image',
        ),
        array( 'type' => 'sectionend', 'id' => 'styling_options'),
      ),
    );

    //[wpa_activity_code]
    $shortcode = 'wpa_activity_code';
    $parameters[ $shortcode ] = array(
      'title' => __("Activity Code", 'wpachievements' ),
      'description' => __( "Show a form where user can enter special activity codes to unlock achievements.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Input Field Placeholder', 'wpachievements' ),
          'desc'      => __( 'Placeholder for the activity code input area.', 'wpachievements' ),
          'id'        => $shortcode . '__input_placeholder',
          'type'      => 'text',
          'default'   => __("Enter Activity Code", "wpachievements"),
        ),
        array(
          'title'     => __( 'Button Text', 'wpachievements' ),
          'desc'      => __( 'Submit button text.', 'wpachievements' ),
          'id'        => $shortcode . '__submit_button_text',
          'type'      => 'text',
          'default'   => __("Submit", "wpachievements"),
        ),
        array( 'type' => 'sectionend', 'id' => 'general_options'),
      ),
    );

    //[wpa_quest_steps quest_id="x"]
    $shortcode = 'wpa_quest_steps';
    $parameters[ $shortcode ] = array(
      'title' => __("Interactive Quest Steps", 'wpachievements' ),
      'description' => __( "Display the progress for a logged in user on a certain quest.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Quest', 'wpachievements' ),
          'desc'      => __( 'Quest to show the progress for.', 'wpachievements' ),
          'id'        => $shortcode . '__quest_id',
          'type'      => 'quest_select',
        ),
        array(
          'title'     => __( 'Limit Rank', 'wpachievements' ),
          'desc'      => __( 'Limit visibility of the quest progress to user rank.', 'wpachievements' ),
          'id'        => $shortcode . '__limit_rank',
          'type'      => 'checkbox',
          'default'   => 'no',
        ),
        array(
          'title'     => __( 'Show Title', 'wpachievements' ),
          'desc'      => __( 'Display the rank title.', 'wpachievements' ),
          'id'        => $shortcode . '__show_title',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array( 'type' => 'sectionend', 'id' => 'general_options'),
        array( 'title' => __( 'Styling Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'styling_options' ),
        array(
          'title'     => __( 'Title CSS Class', 'wpachievements' ),
          'desc'      => __( 'Optional CSS class to fit your design.', 'wpachievements' ),
          'id'        => $shortcode . '__title_class',
          'type'      => 'text',
        ),
        array( 'type' => 'sectionend', 'id' => 'styling_options'),
      ),
    );

    //[wpa_quests]
    $shortcode = 'wpa_quests';
    $parameters[ $shortcode ] = array(
      'title' => __( "Our Quests", 'wpachievements' ),
      'description' => __( "Display all available quests.", "wpachievements" ),
      'fields' => array(
        array(
          'title'     => __( 'Show Title', 'wpachievements' ),
          'desc'      => __( 'Display the title.', 'wpachievements' ),
          'id'        => $shortcode . '__show_title',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Heading', 'wpachievements' ),
          'desc'      => __( 'Display a heading above your content.', 'wpachievements' ),
          'id'        => $shortcode . '__title',
          'type'      => 'text',
          'default'   => __( 'Our Quests', 'wpachievements' ),
        ),
         array(
          'title'     => __( 'Title Heading', 'wpachievements' ),
          'desc'      => __( 'Select Heading Level for the title.', 'wpachievements' ),
          'id'        => $shortcode . '__title_heading',
          'type'      => 'select',
          'options'   => array(
            'h1' => __( 'H1', 'wpachievements'),
            'h2' => __( 'H2', 'wpachievements' ),
            'h3' => __( 'H3', 'wpachievements' ),
            'h4' => __( 'H4', 'wpachievements' ),
            'h5' => __( 'H5', 'wpachievements' ),
            'h6' => __( 'H6', 'wpachievements' ),
          ),
          'default'   => 'h2',
        ),
      ),
    );

    //[wpa_achievements show_title="true" title="Our Achievements" title_heading="h2"]
    $shortcode = 'wpa_achievements';
    $parameters[ $shortcode ] = array(
      'title' => __( "Our Achievements", 'wpachievements' ),
      'description' => __( "Display all available achievements.", "wpachievements" ),
      'fields' => array(
        array(
          'title'     => __( 'Show Title', 'wpachievements' ),
          'desc'      => __( 'Display the title.', 'wpachievements' ),
          'id'        => $shortcode . '__show_title',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Heading', 'wpachievements' ),
          'desc'      => __( 'Display a heading above your content.', 'wpachievements' ),
          'id'        => $shortcode . '__title',
          'type'      => 'text',
          'default'   => __( 'Our Achievements', 'wpachievements' ),
        ),
         array(
          'title'     => __( 'Title Heading', 'wpachievements' ),
          'desc'      => __( 'Select Heading Level for the title.', 'wpachievements' ),
          'id'        => $shortcode . '__title_heading',
          'type'      => 'select',
          'options'   => array(
            'h1' => __( 'H1', 'wpachievements'),
            'h2' => __( 'H2', 'wpachievements' ),
            'h3' => __( 'H3', 'wpachievements' ),
            'h4' => __( 'H4', 'wpachievements' ),
            'h5' => __( 'H5', 'wpachievements' ),
            'h6' => __( 'H6', 'wpachievements' ),
          ),
          'default'   => 'h2',
        ),
      ),
    );

    //[wpa_achievement post_id="" show_title="true" show_description="true" show_image="true" show_trigger="true" trigger_title="How to gain this achievement?"]
    $shortcode = 'wpa_achievement';
    $parameters[ $shortcode ] = array(
      'title' => __("Achievement", 'wpachievements' ),
      'description' => __( "Show description of a single achievement.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Achievement', 'wpachievements' ),
          'desc'      => __( 'Select an achievement.', 'wpachievements' ),
          'id'        => $shortcode . '__post_id',
          'type'      => 'achievement_select',
        ),
        array(
          'title'     => __( 'Show Title', 'wpachievements' ),
          'desc'      => __( 'Display the achievement title.', 'wpachievements' ),
          'id'        => $shortcode . '__show_title',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Show Description', 'wpachievements' ),
          'desc'      => __( 'Display the achieivement post content.', 'wpachievements' ),
          'id'        => $shortcode . '__show_description',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Show Achievement Badge', 'wpachievements' ),
          'desc'      => __( 'Display the achievement badge.', 'wpachievements' ),
          'id'        => $shortcode . '__show_image',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Show Trigger', 'wpachievements' ),
          'desc'      => __( 'Display how this achievement can be gained.', 'wpachievements' ),
          'id'        => $shortcode . '__show_trigger',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Trigger Title', 'wpachievements' ),
          'desc'      => __( 'Define the trigger title.', 'wpachievements' ),
          'id'        => $shortcode . '__trigger_title',
          'type'      => 'text',
          'default'   => __( "How to gain this achievement?", 'wpachievements' ),
        ),

        array( 'type' => 'sectionend', 'id' => 'general_options'),
      ),
    );

    //[wpa_quest post_id="" show_title="true" show_description="true" show_image="true" show_trigger="true" trigger_title="How to solve this quest?"]
    $shortcode = 'wpa_quest';
    $parameters[ $shortcode ] = array(
      'title' => __("Quest", 'wpachievements' ),
      'description' => __( "Show description of a single quest.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Quest', 'wpachievements' ),
          'desc'      => __( 'Select a quest.', 'wpachievements' ),
          'id'        => $shortcode . '__post_id',
          'type'      => 'quest_select',
        ),
        array(
          'title'     => __( 'Show Title', 'wpachievements' ),
          'desc'      => __( 'Display the quest title.', 'wpachievements' ),
          'id'        => $shortcode . '__show_title',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Show Description', 'wpachievements' ),
          'desc'      => __( 'Display the quest post content.', 'wpachievements' ),
          'id'        => $shortcode . '__show_description',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Show Quest Badge', 'wpachievements' ),
          'desc'      => __( 'Display the quest badge.', 'wpachievements' ),
          'id'        => $shortcode . '__show_image',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Show Required Steps', 'wpachievements' ),
          'desc'      => __( 'Display how this quest can be solved.', 'wpachievements' ),
          'id'        => $shortcode . '__show_trigger',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Trigger Title', 'wpachievements' ),
          'desc'      => __( 'Define the trigger title.', 'wpachievements' ),
          'id'        => $shortcode . '__trigger_title',
          'type'      => 'text',
          'default'   => __( "How to solve this quest?", 'wpachievements' ),
        ),

        array( 'type' => 'sectionend', 'id' => 'general_options'),
      ),
    );

    //[wpa_if_achievement post_id=""]
    $shortcode = 'wpa_if_achievement';
    $parameters[ $shortcode ] = array(
      'title' => __( 'IF Achievement', 'wpachievements' ),
      'description' => __( 'Shortcode to check if user has gained an achievement to display content conditionally.', 'wpachievements' ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Achievement', 'wpachievements' ),
          'desc'      => __( 'Select an achievement.', 'wpachievements' ),
          'id'        => $shortcode . '__post_id',
          'type'      => 'achievement_select',
        ),
        array(
          'title'     => '',
          'desc'      => '',
          'id'        => $shortcode . '__conditional',
          'type'      => 'text',
          'css'       => 'display:none',
          'default'   => __('Paste your content here if user has gained this achievement.', 'wpachievements'). ' <br/>[wpa_else_achievement]<br/>' . __('Paste your "else" content here.', 'wpachievements') . '<br />[/wpa_if_achievement]',
        ),

        array( 'type' => 'sectionend', 'id' => 'general_options'),
      ),
    );

    //[wpa_if_quest post_id=""]
    $shortcode = 'wpa_if_quest';
    $parameters[ $shortcode ] = array(
      'title' => __("IF Quest", 'wpachievements' ),
      'description' => __("Shortcode to check if user has solved a quest to display content conditionally.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Quest', 'wpachievements' ),
          'desc'      => __( 'Select a quest.', 'wpachievements' ),
          'id'        => $shortcode . '__post_id',
          'type'      => 'quest_select',
        ),
        array(
          'title'     => '',
          'desc'      => '',
          'id'        => $shortcode . '__conditional',
          'type'      => 'text',
          'css'       => 'display:none',
          'default'   => __('Paste your content here if user has solved this quest.', 'wpachievements'). ' <br/>[wpa_else_quest]<br/>' . __('Paste your "else" content here.', 'wpachievements') . '<br />[/wpa_if_quest]',
        ),

        array( 'type' => 'sectionend', 'id' => 'general_options'),
      ),
    );

    //[wpa_if_rank rank="Rookie" condition="equal"]
    $shortcode = 'wpa_if_rank';
    $parameters[ $shortcode ] = array(
      'title' => __("IF Rank", 'wpachievements' ),
      'description' => __("Shortcode to check if user is on a certain rank to display content conditionally.", "wpachievements" ),
      'fields' => array(
        array( 'title' => __( 'General Options', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
        array(
          'title'     => __( 'Rank', 'wpachievements' ),
          'desc'      => __( 'Select a rank.', 'wpachievements' ),
          'id'        => $shortcode . '__rank',
          'type'      => 'rank_select',
        ),
        array(
          'title'     => __( 'Condition', 'wpachievements' ),
          'desc'      => __( 'Set the condition to check.', 'wpachievements' ),
          'id'        => $shortcode . '__condition',
          'type'      => 'select',
          'options'   => array(
            'equal'   => __( 'Requires selected rank', 'wpachievements'),
            'minimal' => __( 'Requires at least selected rank.', 'wpachievements' ),
          ),
          'default'   => 'equal',
        ),
        array(
          'title'     => '',
          'desc'      => '',
          'id'        => $shortcode . '__conditional',
          'type'      => 'text',
          'css'       => 'display:none',
          'default'   => __( 'Paste your content here if user has required rank.', 'wpachievements' ) . ' <br/>[wpa_else_rank]<br/>' . __('Paste your "else" content here.', 'wpachievements') . '<br />[/wpa_if_rank]',
        ),

        array( 'type' => 'sectionend', 'id' => 'general_options' ),
      ),
    );

    return apply_filters( "wpachievements_shortcode_editor_parameters", $parameters );
  }

  private static function enqueue_gridtab_lib() {
    wp_enqueue_script( 'wpachievements-gridtab' );
  }

  /**
   * Retrieves the user id and allows users to filter the user id
   *
   * @access public
   * @param  integer $user_id
   * @return integer User ID
   */
  private static function get_user_id( $user_id = 0 ) {

    if ( ! $user_id ) {
      $user_id = get_current_user_id();
    }

    return apply_filters( 'wpachievements_shortcode_user_id', $user_id );
  }

  /**
   * Show the "My Achievements" widget
   * [wpa_myachievements achievement_limit="" show_title="" image_width="" title_heading="" title_class="" image_holder_class="" list_class="" list_element_class="" image_class=""]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function myachievements( $atts ) {

    extract( shortcode_atts( array(
      'user_id' => '',
      'show_title' => 'true',
      'title_heading' => 'h3',
      'title_class' => 'wpa_ach_short_title',
      'list_class' => 'wpa_horizontal_list',
      'list_element_class' => 'wpa_loc_caption',
      'image_holder_class' => 'wpa_horizontal_list_align',
      'image_class' => 'wpa_a_image',
      'image_width' => '30',
      'achievement_limit' => '-1',
    ), $atts ) );


    $user_id = self::get_user_id( $user_id );

    $userachievement = (array) get_user_meta( $user_id, 'achievements_gained', true );
    $myachievements = '';

    $myachievements .= '<div class="'. $image_holder_class .'">';

    if ( "true" == $show_title ) {
      $myachievements .= '<'. $title_heading .' class="'. $title_class .'">'. __('My Achievements', 'wpachievements') .'</'. $title_heading .'>';
    }

    $already_counted[] = array();

    $sim_ach = wpachievements_get_site_option( 'wpachievements_sim_ach' );

    $no_achievements = '<p>'. __('No Achievements Yet!', 'wpachievements') .'</p>';

    $count=0;
    $iii=0;
    $achievement_badges = array();

    if ( $userachievement ) {
      if ( is_multisite() ) {
        switch_to_blog(1);
      }

      $args = array(
        'post__in' => $userachievement,
        'posts_per_page' => $achievement_limit
      );

      $achievements = WPAchievements_Query::get_achievements( $args );

      if ( $achievements ) {
        foreach ( $achievements as $achievement ) {
          $count++;
          $ach_title = $achievement->post_title;
          $ach_desc = $achievement->post_content;
          $ach_img = get_post_meta( $achievement->ID, '_achievement_image', true );
          $ach_occurences = get_post_meta( $achievement->ID, '_achievement_occurrences', true );
          $type = 'wpachievements_achievement_'.get_post_meta( $achievement->ID, '_achievement_type', true );

          $img_alt_tag = sprintf( __("%s Icon", 'wpachievements' ), stripslashes( $ach_title ) );
          $img_title_tag = stripslashes( $ach_title ) . ': ' . stripslashes( strip_tags( $ach_desc ) );

          if ( $sim_ach == 'yes' ) {
            if ( !array_key_exists( $type,$already_counted ) ) {
              $iii++;
              $first = ( $iii == 1 ) ? 'first ' : '';

              if ( $type != 'wpachievements_achievement_custom_achievement' ) {
                $already_counted[$type] = $ach_occurences;
              }

              $achievement_badges[$count] = '<li class="'. $list_element_class .'"><img src="'.$ach_img.'" width="'. $image_width .'" class="'. $first . $image_class .'" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" /></li>';
            }
            elseif ( $already_counted[$type] <= $ach_occurences ) {
              $iii++;
              $first = ( $iii == 1 ) ? 'first ' : '';

              if ( $type != 'wpachievements_achievement_custom_achievement' ) {
                $already_counted[$type] = $ach_occurences;
              }

              $achievement_badges[$count] = '<li class="'. $list_element_class .'"><img src="'.$ach_img.'" width="'. $image_width .'" class="'. $first . $image_class .'" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" /></li>';
            }
          }
          else {
            $iii++;
            $first = ( $iii == 1 ) ? 'first ' : '';
            $achievement_badges[$count] = '<li class="'. $list_element_class .'"><img src="'.$ach_img.'" width="'. $image_width .'" class="'. $first . $image_class .'" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" /></li>';
          }
        }

        if ( is_array($achievement_badges) ) {
          $myachievements .= '<ul class="'. $list_class .'">';
          foreach( $achievement_badges as $achievement_badge ) {
            $myachievements .= $achievement_badge;
          }
          $myachievements .= '</ul>';
        }
      }

      if ( is_multisite() ) {
        restore_current_blog();
      }

      if ( $iii == 0 ) {
        $myachievements .= $no_achievements;
      }
    }
    else {
      $myachievements .= $no_achievements;
    }

    $myachievements .= '</div>';

    return $myachievements;
  }

  /**
   * Show the "My Quests" widget
   * [wpa_myquests quest_limit="" show_title="" image_width="" title_heading="" title_class="" image_holder_class="" list_class="" list_element_class="" image_class=""]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function myquests( $atts ) {

    extract( shortcode_atts( array(
      'user_id' => '',
      'show_title' => 'true',
      'title_heading' => 'h3',
      'title_class' => 'wpa_ach_short_title',
      'list_class' => 'wpa_horizontal_list',
      'list_element_class' => 'wpa_loc_caption',
      'image_holder_class' => 'wpa_horizontal_list_align',
      'image_class' => 'wpa_a_image',
      'image_width' => '30',
      'quest_limit' => '-1',
    ), $atts ) );

    $user_id = self::get_user_id( $user_id );

    $userquests = (array) get_user_meta( $user_id, 'quests_gained', true );
    $myquests = '';

    $myquests .= '<div class="'. $image_holder_class .'">';

    if ( "true" == $show_title ) {
      $myquests .= '<'. $title_heading .' class="'. $title_class .'">'. __('My Quests', 'wpachievements') .'</'. $title_heading .'>';
    }

    $no_quests = '<p>'. __('No Quests Yet!', 'wpachievements') .'</p>';

    $count=0;
    $iii=0;
    $quest_badges=array();

    if ( $userquests ) {
      if ( is_multisite() ) {
        switch_to_blog(1);
      }

      $args = array(
        'post__in' => $userquests,
        'posts_per_page' => $quest_limit
      );

      $quests = WPAchievements_Query::get_quests( $args );

      if ( $quests ) {
        foreach( $quests as $quest ) {
          $count++;
          $post_id = $quest->ID;
          $title = $quest->post_title;
          $description = $quest->post_content;
          $image = get_post_meta( $post_id, '_quest_image', true );
          $iii++;
          $first = ( $iii == 1 ) ? 'first ' : '';

          $img_alt_tag = sprintf( __("%s Icon", 'wpachievements' ), stripslashes( $title ) );
          $img_title_tag = stripslashes( $title ) . ': ' . stripslashes( strip_tags( $description ) );

          $quest_badges[$count] = '<li class="'. $list_element_class .'"><img src="'.$image.'" width="'. $image_width .'" class="'. $first . $image_class .'" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" /></li>';
        }

        if ( is_array( $quest_badges ) ) {
          $myquests .= '<ul class="'. $list_class .'">';
          foreach( $quest_badges as $quest_badge ) {
            $myquests .= $quest_badge;
          }
          $myquests .= '</ul>';
        }
      }

      if( is_multisite() ) {
        restore_current_blog();
      }

      if ( $iii==0 ) {
        $myquests .= $no_quests;
      }
    }
    else {
      $myquests .= $no_quests;
    }

    $myquests .= '</div>';

    return $myquests;
  }

  /**
   * Show the "My Rank" widget
   * [wpa_myranks user_id="" show_title="" title_class="" rank_image=""]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function myranks( $atts ) {

    extract( shortcode_atts( array(
      'user_id' => '',
      'show_title' => 'true',
      'title_class' => '',
      'rank_image' => '',
    ), $atts ) );

    $user_id = self::get_user_id( $user_id );

    if ( ! $user_id ) {
      return;
    }

    $myranks = '';

    if( strtolower( $show_title ) == 'true' ) {
      $myranks = '<h3 class="rank_short_title '. $title_class .'">'. __('My Rank', 'wpachievements') .'</h3>';
    }

    if ( strtolower( $rank_image ) == 'true' ) {
      $myranks .= wpachievements_getRankImage($user_id);
    }

    list($lvlstat,$wid) = wpa_ranks_widget($user_id);
    $myranks .= $lvlstat;
    $myranks .="<div class='clear'></div><script>
    jQuery(document).ready(function(){
      jQuery('.pb_bar_user_login').animate({width:'".$wid."px'},1500);
    });
    </script>";

    return $myranks;
  }

  /**
   * Simple shortcode to display user points anywhere
   * [wpa_points user_id=""]
   *
   * If user_id parameter is empty, points of the current user will be displayed
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function mypoints( $atts ) {

    extract( shortcode_atts( array(
      'user_id' => '',
    ), $atts ) );

    $user_id = self::get_user_id( $user_id );

    if ( ! $user_id ) {
      return;
    }

    return WPAchievements_User::get_points( $user_id );
  }

  /**
   * List the Achievements Leaderboard
   * [wpa_leaderboard_list list_class="" limit="10" type="" user_position="true" user_ranking="true"]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function leaderboard_list( $atts ) {
    global $wpdb;

    extract( shortcode_atts( array(
      'list_class' => '',
      'limit' => '10',
      'type' => '',
      'user_position' => 'true',
      'user_ranking' => 'true',
    ), $atts ) );

    $table = $wpdb->prefix.'usermeta';

    if( is_multisite() ) {
      switch_to_blog(1);
      $table = $wpdb->prefix.'usermeta';
      restore_current_blog();
    }

    $hide_admin = wpachievements_get_site_option( 'wpachievements_hide_admin' );

    $admins = array();

    if ( $hide_admin == 'yes' ) {
      $user_query = new WP_User_Query( array( 'role' => 'Administrator' ) );
      $users = $user_query->get_results();

      foreach( $users as $user ) {
        $admins[] = $user->ID;
      }
    }
    else {

      $admins[] = 0;
    }

    $meta_key = 'achievements_count';

    if ( strtolower($type) == 'points' ) {
      $meta_key = 'achievements_points';
      $meta_key = apply_filters( 'wpachievements_meta_key', $meta_key );
    }

    $user_achievements = $wpdb->get_results( $wpdb->prepare("SELECT user_id,meta_value FROM ".$table." WHERE meta_key=%s AND user_id NOT IN (".implode(',', $admins).") ORDER BY meta_value * 1 DESC LIMIT %d", $meta_key, $limit ) );

    $trophies = array('','gold','silver','bronze');
    $count=0;
    $html = '<ul class="wpach_leaderboard '.$list_class.'">';

    if ( is_array( $user_achievements ) && $user_achievements ) {
      foreach ( $user_achievements as $user_info ) {
        $count++;
        if ( $user_info->meta_value > 0) {
          $user_inf = get_userdata($user_info->user_id);
          $html .= '<li>';

          if ( strtolower( $user_position ) == 'true' ) {
            if ( $count < 4 ) {
              $trophy = $trophies[$count];
            }
            else {
              $trophy = 'default';
            }

            $html .= '<div class="myus_icon trophy_'.$trophy.'">';

            if ( $count > 3 ) {
              $html .= '<div class="myus_num">'.$count.'<span>th</span></div>';
            }

            $html .= '</div>';
          }

          $html .= '<h3>'. get_avatar($user_info->user_id, '50') .'<span>'.$user_inf->display_name.'</span></h3>';

          if ( strtolower($type) == 'points' ) {
            $html .= '<div class="points_count">'.__('Total Points', 'wpachievements').': '.$user_info->meta_value.'</div>';
          }
          else {
            $html .= '<div class="achievement_count">'.__('Achievements', 'wpachievements').': '.$user_info->meta_value.'</div>';
          }

          if ( strtolower( $user_ranking ) == 'true' ) {
            $html .= '<div class="user_ranking">'.__('Rank', 'wpachievements').': '.wpachievements_getRank($user_info->user_id).'</div>';
          }

          $html .= '</li>';
        }
      }
    }

    $html .= '</ul>';

    return $html;
  }

  /**
   * Show the Achievements Leaderboard Widget
   * [wpa_leaderboard_widget limit="" type=""]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function leaderboard_widget( $atts ) {
    global $wpdb;

    extract( shortcode_atts( array(
      'limit' => '10',
      'type' => '',
    ), $atts ) );

    if (is_multisite() ) {
      switch_to_blog(1);
    }

    $table = $wpdb->prefix.'usermeta';

    if (is_multisite() ) {
      restore_current_blog();
    }

    $hide_admin = wpachievements_get_site_option( 'wpachievements_hide_admin' );

    $admins = array();

    if ( $hide_admin == 'yes' ) {
      $user_query = new WP_User_Query( array( 'role' => 'Administrator' ) );
      $users = $user_query->get_results();

      foreach( $users as $user ) {
        $admins[] = $user->ID;
      }
    }
    else {
      $admins[] = 0;
    }

    $meta_key = 'achievements_count';

    if ( strtolower($type) == 'points' ) {
      $meta_key = 'achievements_points';
      $meta_key = apply_filters( 'wpachievements_meta_key', $meta_key );
    }

    $user_achievements = $wpdb->get_results( $wpdb->prepare("SELECT user_id,meta_value FROM ".$table." WHERE meta_key=%s AND user_id NOT IN (".implode(',', $admins).") ORDER BY meta_value * 1 DESC LIMIT %d", $meta_key, $limit ) );

    $trophies = array('','gold','silver','bronze');
    $count=0;
    $html = '';

    if ( is_array( $user_achievements ) && $user_achievements ) {
      $html = '<div class="widget_wpachievements_widget">';

      foreach( $user_achievements as $user_info ) {
        if ( $user_info->meta_value > 0 ) {
          $user_inf = get_userdata($user_info->user_id);
          $count++;

          if( $count < 4) {
            $trophy = $trophies[$count];
          }
          else {
            $trophy = 'default';
          }

          $html .= '<center>';
          $html .= '<div class="myus_user wpach_leaderboard">'. get_avatar($user_info->user_id, '50') .'<div class="myus_title">';

          $profile_url = apply_filters( 'wpachievements_user_profile_url', false, $user_info->user_id );

          if ( $profile_url ) {
            $html .= '<a href="' . $profile_url . '" title="' . sprintf( __( "View %s Profile", 'wpachievements'), $user_inf->display_name ) . '">' . $user_inf->display_name . '</a>';
          }
          else {
            $html .= $user_inf->display_name;
          }

          if ( strtolower($type) == 'points' ) {
            $html .= '</div><div class="myus_count">'.__('Total Points', 'wpachievements').': '.$user_info->meta_value.'</div>';
          }
          else {
            $html .= '</div><div class="myus_count">'.__('Achievements', 'wpachievements').': '.$user_info->meta_value.'</div>';
          }

          $html .= '<div class="myus_icon trophy_'.$trophy.'">';

          if( $count > 3 ) {
            $html .= '<div class="myus_num">'.$count.'<span>th</span></div>';
          }

          $html .= '</div><div class="user_finish"></div></div></center>';
        }
      }
      $html .= '</div>';
    }

    return $html;
  }

  /**
   * Sortable Style Leaderboard
   * [wpa_leaderboard list_class="" limit="" achievement_limit="" quest_limit="" position_numbers="" columns=""]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function leaderboard( $atts ) {
    global $wpdb;

    extract( shortcode_atts( array(
      'list_class' => '',
      'limit' => '10',
      'achievement_limit' => '10',
      'quest_limit' => '10',
      'position_numbers' => 'true',
      'columns' => 'avatar,points,rank,achievements,quests'
    ), $atts ) );

    wp_enqueue_style( 'wpachievements-data-table-style', WPACHIEVEMENTS_URL .'/assets/js/data-tables/css/jquery.dataTables.css' );
    wp_register_script( 'wpachievements-data-table-script', WPACHIEVEMENTS_URL .'/assets/js/data-tables/js/jquery.dataTables.min.js', array('jquery') );
    wp_enqueue_script( 'wpachievements-data-table-script' );
    wp_enqueue_script( 'wpachievements-leaderboard-script', WPACHIEVEMENTS_URL . '/assets/js/leaderboard-table.js' );

    if ( is_multisite() ) {
      switch_to_blog(1);
    }

    $table = $wpdb->prefix.'usermeta';

    if ( is_multisite() ) {
      restore_current_blog();
    }

    $hide_admin = wpachievements_get_site_option( 'wpachievements_hide_admin' );
    $admins = array();

    if ( $hide_admin == 'yes' ) {
      $user_query = new WP_User_Query( array( 'role' => 'Administrator' ) );
      $users = $user_query->get_results();

      foreach ( $users as $user ) {
        $admins[] = $user->ID;
      }
    }
    else {
      $admins[] = 0;
    }

    $meta_key = 'achievements_points';
    $meta_key = apply_filters( 'wpachievements_meta_key', $meta_key );

    $user_achievements = $wpdb->get_results( $wpdb->prepare("SELECT user_id,meta_value FROM ".$table." WHERE meta_key=%s AND user_id NOT IN (".implode(',', $admins).") ORDER BY meta_value * 1 DESC LIMIT %d", $meta_key, $limit ) );

    if ( !empty( $list_class ) ) {
      $list_class = ' class="'.$list_class.'"';
    }

    $html = '';

    if ( is_array( $user_achievements ) && $user_achievements && !is_home() ) {
      $html .= '<table id="wpa_leaderboard_sortable"'.$list_class.'>
      <thead>
      <tr>';
      $columns = strtolower($columns);

      if ( $position_numbers == 'true' ) {
        $html .= '<th>'.__('Position','wpachievements').'</th>';
      }

      if ( strpos($columns, 'avatar') !== FALSE ) {
        $html .= '<th>'.__('Avatar','wpachievements').'</th>';
      }

      $html .= '<th>'.__('Username','wpachievements').'</th>';

      if ( strpos($columns, 'points') !== FALSE ) {
        $html .= '<th>'.__('Points','wpachievements').'</th>';
      }

      if( strpos($columns, 'rank') !== FALSE ) {
        $html .= '<th>'.__('Rank','wpachievements').'</th>';
      }

      if ( strpos($columns, 'achievements') !== FALSE ) {
        $html .= '<th>'.__('Achievements','wpachievements').'</th>';
      }

      if ( strpos($columns, 'quests') !== FALSE ) {
        $html .= '<th>'.__('Quests','wpachievements').'</th>';
      }

      echo '</tr>
      </thead>';

      $html .= '<tbody>';
      $count=0;

      foreach( $user_achievements as $user ) {
        $count++;

        if ( $user->meta_value > 0 ) {
          $user_info = get_userdata($user->user_id);
          $html .= '<tr>';

          // Position Column
          if ( $position_numbers == 'true' ) {
            $html .= '<td>'.$count.'</td>';
          }

          $profile_url = apply_filters( 'wpachievements_user_profile_url', false, $user->user_id );

          // Avatar Column
          if ( strpos($columns, 'avatar') !== FALSE ) {
            if ( $profile_url ) {
              $html .= '<td><a href="' . $profile_url . '" title="' . sprintf( __( "View %s Profile", 'wpachievements'), $user_info->display_name ) . '">' . get_avatar($user->user_id, $size = '50') . '</a></td>';
            }
            else {
              $html .= '<td>'.get_avatar($user->user_id, $size = '50').'</td>';
            }
          }

          // Username Column
          $html .= '<td>';

          if ( $profile_url ) {
            $html .= '<a href="' . $profile_url . '" title="' . sprintf( __( "View %s Profile", 'wpachievements'), $user_info->display_name ) . '">' . $user_info->display_name . '</a>';
          }
          else {
            $html .= $user_info->display_name;
          }

          $html .= '</td>';

          // Points Column
          if ( strpos($columns, 'points') !== FALSE ) {
            $html .= '<td>'.$user->meta_value.'</td>';
          }

          // Rank Column
          if ( strpos($columns, 'rank') !== FALSE ) {
            $html .= '<td>'.wpachievements_getRank($user->user_id).'</td>';
          }

          // Achievements Column
          if ( strpos($columns, 'achievements') !== FALSE ) {
            $sim_ach = wpachievements_get_site_option( 'wpachievements_sim_ach' );

            $html .= '<td>';
            $userachievement = (array) get_user_meta( $user->user_id, 'achievements_gained', true );

            if ( $userachievement ) {
              if ( is_multisite() ) {
                switch_to_blog(1);
              }

              $already_counted = array();
              $iii=0;

              $args = array(
                'post__in' => $userachievement,
                'posts_per_page' => $achievement_limit
              );

              $achievements = WPAchievements_Query::get_achievements( $args );

              if ( $achievements ) {
                foreach( $achievements as $achievement ) {
                  $ach_ID = $achievement->ID;
                  $ach_title = $achievement->post_title;
                  $ach_desc = $achievement->post_content;
                  $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );
                  $ach_occurences = get_post_meta( $ach_ID, '_achievement_occurrences', true );
                  $type = 'wpachievements_achievement_'.get_post_meta( $ach_ID, '_achievement_type', true );

                  $img_alt_tag = sprintf( __("%s Icon", 'wpachievements' ), stripslashes( $ach_title ) );
                  $img_title_tag = stripslashes( $ach_title ) . ': ' . stripslashes( strip_tags( $ach_desc ) );

                  if ( $sim_ach == 'yes' ) {
                    if ( !array_key_exists($type,$already_counted) ) {
                      $iii++;
                      $first = ( $iii == 1 ) ? 'first ' : '';

                      if ( $type != 'wpachievements_achievement_custom_achievement' ) {
                        $already_counted[$type] = $ach_occurences;
                      }

                      $html .= '<img src="'.$ach_img.'" class="wpa_table_ach_img" width="30" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" />';
                    }
                    elseif ( $already_counted[$type] <= $ach_occurences ) {
                      $iii++;
                      $first = ( $iii == 1 ) ? 'first ' : '';

                      if ( $type != 'wpachievements_achievement_custom_achievement' ) {
                        $already_counted[$type] = $ach_occurences;
                      }

                      $html .= '<img src="'.$ach_img.'" class="wpa_table_ach_img" width="30" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" />';
                    }
                  }
                  else {
                    $iii++;
                    $first = ( $iii == 1 ) ? 'first ' : '';

                    $html .= '<img src="'.$ach_img.'" class="wpa_table_ach_img" width="30" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" />';
                  }
                }
              }

              if (is_multisite() ) {
                restore_current_blog();
              }
            }
            else {
              $html .= __('None','wpachievements');
            }

            $html .= '</td>';
          }

          // Quests Column
          if ( strpos($columns, 'quests') !== FALSE ) {
            $html .= '<td>';
            $userquests = (array) get_user_meta( $user->user_id, 'quests_gained', true );

            if ( $userquests ) {
              $args = array(
                'post__in' => $userquests,
                'posts_per_page' => $quest_limit
              );

              $quests = WPAchievements_Query::get_quests( $args );

              if ( $quests ) {
                foreach( $quests as $quest ) {
                  $post_id = $quest->ID;
                  $title = $quest->post_title;
                  $description = $quest->post_content;
                  $image = get_post_meta( $post_id, '_quest_image', true );
                  $html .= '<img src="'.$image.'" width="30" class="wpa_table_ach_img" alt="'.sprintf( __("%s Icon", 'wpachievements' ), stripslashes( $title ) ).'" title="'.stripslashes($title).': '.stripslashes(strip_tags($description)).'" />';
                }
              }

              if ( is_multisite() ) {
                restore_current_blog();
              }
            }
            else {
              $html .= __('None','wpachievements');
            }

            $html .= '</td>';
          }

          $html .= '</tr>';
        }
      }

      $html .= '</tbody>';
      $html .= '</table><br/>';
    }

    return $html;
  }

  /**
   * Trigger Custom Achievements
   * [wpa_custom_achievement trigger_id="" type="" text=""]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function custom_achievement( $atts ) {

    if ( ! is_user_logged_in() || is_home() ) {
      return;
    }

    extract( shortcode_atts( array(
      'trigger_id' => '',
      'type' => 'button',
      'text' => __('Gain Achievement', 'wpachievements'),
    ), $atts ) );

    if ( 'instant' == $type ) {
      WPAchievements()->achievement()->custom_achievement_trigger( $trigger_id );
    }
    else {
      $trigger_html = '<a href="#" id="'.$trigger_id.'" class="wpa_custom_trigger '.$type.'">'.$text.'</a>';
      $trigger_html .= '<script type="text/javascript">jQuery(document).on("click", "a#'.$trigger_id.'",function(event){ event.preventDefault(); if( !jQuery(this).hasClass("trigger_disabled") ){jQuery.post( "'.admin_url( 'admin-ajax.php' ).'", { "action": "wpa_auto_custom_trigger", "wpa_trigger_id": "'.$trigger_id.'"} , function(data){jQuery("a#'.$trigger_id.'").addClass("trigger_disabled");});}});</script>';
      return $trigger_html;
    }
  }

  /**
   * Show the Achievements based on rank limits
   * [wpa_rank_achievements user_id="" rank="" show_title="" title_class="" image_holder_class="" image_class="" image_width="" achievement_limit=""]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function rank_achievements( $atts ) {

    extract( shortcode_atts( array(
      'user_id' => '',
      'show_title' => 'true',
      'title_class' => '',
      'image_holder_class' => '',
      'image_class' => 'wpa_a_image',
      'image_width' => '30',
      'achievement_limit' => '-1',
      'rank' => 'Newbie'
    ), $atts ) );

    $myachievements = '';

    if ( $user_id && is_numeric($user_id) ) {
      $rank = wpachievements_getRank($user_id);
    }

    if ( $rank != '' ) {
      if ( $show_title == 'true' || $show_title == 'True' ) {
        $myachievements .= '<h3 class="wpa_ach_short_title '. $title_class .'">'. __('Achievements for Rank:', 'wpachievements') .' '.$rank.'</h3>';
      }

      $myachievements .= '<div class="'. $image_holder_class .'">';
      $already_counted[] = array();

      $sim_ach = wpachievements_get_site_option( 'wpachievements_sim_ach' );

      $count=0;
      $iii=0;
      $achievement_badges = array();

      if ( is_multisite() ) {
        switch_to_blog(1);
      }

      $args = array(
        'posts_per_page' => $achievement_limit,
        'meta_query' => array(
          'relation' => 'OR',
          array(
            'key' => '_achievement_rank',
            'value' => $rank,
          ),
          array(
            'key' => '_achievement_rank',
            'value' => 'Any',
          ),
        )
      );

      $achievements = WPAchievements_Query::get_achievements( $args );

      if ( $achievements ) {
        foreach( $achievements as $achievement ) {
          $count++;
          $ach_ID = $achievement->ID;
          $ach_title = $achievement->post_title;
          $ach_desc = $achievement->post_content;
          $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );
          $ach_occurences = get_post_meta( $ach_ID, '_achievement_occurrences', true );
          $type = 'wpachievements_achievement_'.get_post_meta( $ach_ID, '_achievement_type', true );

          $img_alt_tag = sprintf( __("%s Icon", 'wpachievements' ), stripslashes( $ach_title ) );
          $img_title_tag = stripslashes( $ach_title ) . ': ' . stripslashes( strip_tags( $ach_desc ) );

          if ( $sim_ach == 'yes' ) {
            if ( !array_key_exists($type,$already_counted) ) {
              $iii++;
              $first = ( $iii == 1 ) ? 'first ' : '';

              if ( $type != 'wpachievements_achievement_custom_achievement' ) {
                $already_counted[$type] = $ach_occurences;
              }

              $achievement_badges[$count] = '<img src="'.$ach_img.'" width="'. $image_width .'" class="'. $first . $image_class .'" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" style="width:'.$image_width.'px !important;" />';
            }
            elseif ( $already_counted[$type] <= $ach_occurences ) {
              $iii++;
              $first = ( $iii == 1 ) ? 'first ' : '';

              if ( $type != 'wpachievements_achievement_custom_achievement' ) {
                $already_counted[$type] = $ach_occurences;
              }

              $achievement_badges[$count] = '<img src="'.$ach_img.'" width="'. $image_width .'" class="'. $first . $image_class .'" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" style="width:'.$image_width.'px !important;" />';
            }
          }
          else {
            $iii++;
            $first = ( $iii == 1 ) ? 'first ' : '';

            $achievement_badges[$count] = '<img src="'.$ach_img.'" width="'. $image_width .'" class="'. $first . $image_class .'" alt="'.$img_alt_tag.'" title="'.$img_title_tag.'" style="width:'.$image_width.'px !important;" />';
          }
        }

        if( is_array($achievement_badges) ) {
          foreach( $achievement_badges as $achievement_badge ){
            $myachievements .= $achievement_badge;
          }
        }
      }

      if ( is_multisite() ) {
        restore_current_blog();
      }

      $myachievements .= '</div>';
    }

    return $myachievements;
  }

  /**
   * Show the activity code input form
   * [wpa_activity_code]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function activity_code( $atts ) {

    extract( shortcode_atts( array(
      'input_placeholder' => __("Enter Activity Code", "wpachievements"),
      'submit_button_text' => __("Submit", "wpachievements"),
    ), $atts ) );

    $output = '<div class="wpa_activity_code_wrap"><p><div class="wpa_loader"></div><span class="wpa_activity_code_message"></span><p>';

    $output .= '<form method="post" class="activity-code-form" action="' . esc_url( home_url( '/' ) ) . '">
    <label>
    <input type="text" placeholder="' . $input_placeholder . '" value="" name="activity_code" />
    </label>
    <input type="submit" value="'. $submit_button_text .'" />
    </form>';

    $output .= '</div><div class="clear"></div>';

    return $output;
  }

  /**
   * Generate a quest progress bar
   * [wpa_quest_steps quest_id="x"]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function quest_steps( $atts ) {

    extract( shortcode_atts( array(
      'quest_id' => '',
      'show_title' => 'true',
      'title_class' => '',
      'class' => 'custom-complex',
      'limit_rank' => 'false',
    ), $atts ) );

    if ( ! $quest_id ) {
      return __("WPAchievements Quest Steps - Quest Post ID not set in shortcode parameters!", "wpachievements" );
    }

    $user_lvl_ok = true;

    // Get Quest Steps
    $steps = get_post_meta( intval( $quest_id ), '_quest_details', true );

    if ( ! empty( $steps ) && is_array( $steps ) ) {
      $i = 0;
      $completed_all_steps = false;

      // Calculate user's progess on this quest
      $user_id = get_current_user_id();

      if ( $user_id ) {
        // Check if the user has already gained this quest
        $quests_gained = (array) get_user_meta( $user_id, 'quests_gained', true );
        if ( in_array( $quest_id, $quests_gained ) ) {
          $completed_all_steps = true;
          $quest_steps = WPAchievements_User::get_quest_progress( $quest_id );
        }
        else {
          // Compute finished steps
          $quest_steps = WPAchievements_User::get_quest_progress( $quest_id );
        }
      }

      if ( "true" == $limit_rank ) {
        // Get Rank informations
        $usersrank = wpachievements_getRank( $user_id );
        $usersrank_lvl = wpachievements_rankToPoints( $usersrank );

        $questrank = get_post_meta( $quest_id, '_quest_rank', true );
        $questrank_lvl = wpachievements_rankToPoints( $questrank );

        $user_lvl_ok = ( $usersrank_lvl >= $questrank_lvl ) ? true : false;
      }

      ob_start();
      ?>

      <?php if ( "true" == strtolower( $show_title ) ) : ?>
        <h3 <?php if ( $title_class ) echo 'class="'.$title_class.'"'; ?>><?php printf( __('%s Steps', 'wpachievements'), get_the_title( $quest_id ) ); ?></h3>
      <?php endif; ?>

      <div class="quest-steps">
        <ul class="wpa-progress-indicator <?php echo $class; ?>">
          <?php foreach ( $steps as $key => $step ) :
          $i++;

          if ( ( $completed_all_steps || ( isset( $quest_steps[ $key ] ) && $quest_steps[ $key ] ) ) && $user_lvl_ok ) {
            $completed = 'class="completed"';
            $icon = "fa-check-circle";
          }
          else {
            $completed = '';
            $icon = "fa-minus-circle";
          }
          ?>
          <li <?php echo $completed; ?>>
            <span class="bubble"></span>
            <i class="fa fa-lg <?php echo $icon; ?>"></i>
            <?php if ( 'custom-complex' == $class ) : ?>
              <br />
            <?php endif; ?>
            <span class="vertical-text"><?php echo $step['step_description']; ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php if ( ! $user_id ) : ?>
        <div class="wpa-alert wpa-alert-info">
          <?php _e("Please log in to see your progress on this quest!", "wpachievements" ); ?>
        </div>
      <?php else: ?>
        <?php if ( ! $user_lvl_ok ) : ?>
          <div class="wpa-alert wpa-alert-info">
            <?php _e("Your rank is to low to see the progress on this quest!", "wpachievements" ); ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
      <?php
      return ob_get_clean();
    }
  }

  /**
   * Displays all available quests
   * [wpa_quests]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function quests( $atts ) {

    extract( shortcode_atts( array(
      'show_title'    => 'true',
      'title'         => '',
      'title_heading' => 'h2',
    ), $atts ) );

    self::enqueue_gridtab_lib();

    if ( is_multisite() ) {
      switch_to_blog(1);
    }

    $user_id = get_current_user_id();

    if ( $user_id ) {
      // Get user achievements
      $userquests = (array) get_user_meta( $user_id, 'quests_gained', true );
    }
    else {
      $userquests = array();
    }

    // Get all publishes quests
    $quests = WPAchievements_Query::get_quests();

    if ( ! $quests ) {
      return '<div class="wpa_horizontal_list_align"><p>' . __('No Quests available.', 'wpachievements') . '</p></div>';
    }

    ob_start();
    ?>

    <?php if ( $show_title ) : ?>
    <<?php echo $title_heading; ?>><?php echo $title; ?></<?php echo $title_heading; ?>>
    <?php endif; ?>

    <dl class="wpa_achivements_gridtab">
      <?php
      foreach( $quests as $quest ) :
        $image = get_post_meta( $quest->ID, '_quest_image', true );

        $points = get_post_meta( $quest->ID, '_quest_points', true );
        $points_string = sprintf( _n( '%d Point', '%d Points', $points, 'wpachievements'), $points );
        if ( $points > 0 ) {
          $points_alert_class = 'success';
          $points_string = '+' . $points_string;
        }
        else {
          $points_alert_class = 'danger';
        }
        ?>
        <dt>
          <div style="width:100%;">
            <?php
            if ( $user_id ) :
              if ( in_array( $quest->ID, $userquests ) ) : ?>
                <div class="wpa_ribbon"><span><?php _e("Unlocked", "wpachievements"); ?></span></div>
                <?php
              else : ?>
                <div class="wpa_ribbon wpa_ribbon_red"><span><?php _e("Locked", "wpachievements"); ?></span></div>
                <?php
              endif;
            endif; ?>

            <img src="<?php echo $image; ?>" alt="<?php echo $quest->post_title; ?> Icon" width="120" />
          </div>
          <div><?php echo $quest->post_title; ?></div>
        </dt>
        <dd>
          <div class="wpa_description">
            <div class="wpa_description_image">
              <img class="achievement_badge" src="<?php echo $image; ?>" alt="<?php echo $quest->post_title; ?> Icon" width="120" />
              <p class="wpa-alert wpa-alert-<?php echo $points_alert_class; ?>"><?php echo $points_string; ?></p>
            </div>
            <div class="wpa_description_content">
              <p><?php echo $quest->post_content; ?></p>
              <?php
              $quest_steps = wpa_quest_steps( $quest->ID );
              if ( $quest_steps ) : ?>
              <h3><?php _e('Steps to complete', 'wpachievements'); ?></h3>
              <ol>
                <?php foreach ($quest_steps as $quest_step ) : ?>
                  <li><?php echo $quest_step; ?></li>
                <?php endforeach; ?>
              </ol>
              <?php endif; ?>
            </div>
          </div>
        </dd>
        <?php
      endforeach;
      ?>
    </dl>
    <?php

    return ob_get_clean();
  }

  /**
   * Displays an achievements
   * [wpa_achievements]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function achievements( $atts ) {

    extract( shortcode_atts( array(
      'show_title'    => 'true',
      'title'         => '',
      'title_heading' => 'h2',
    ), $atts ) );

    self::enqueue_gridtab_lib();

    if ( is_multisite() ) {
      switch_to_blog(1);
    }

    $user_id = get_current_user_id();

    if ( $user_id ) {
      // Get user achievements
      $userachievements = (array) get_user_meta( $user_id, 'achievements_gained', true );
    }
    else {
      $userachievements = array();
    }

    // Get all published achievements
    $achievements = WPAchievements_Query::get_achievements();

    if ( ! $achievements ) {
     return '<div class="wpa_horizontal_list_align"><p>' . __('No Achievements available.', 'wpachievements') . '</p></div>';
    }

    ob_start();

    if ( $show_title ) : ?>
      <<?php echo $title_heading; ?>><?php echo $title; ?></<?php echo $title_heading; ?>>
    <?php endif; ?>

    <dl class="wpa_achivements_gridtab">
      <?php
      foreach( $achievements as $achievement ) :
        $image = get_post_meta( $achievement->ID, '_achievement_image', true );
        $occurences = get_post_meta( $achievement->ID, '_achievement_occurrences', true );
        $type = get_post_meta( $achievement->ID, '_achievement_type', true );

        $points = get_post_meta( $achievement->ID, '_achievement_points', true );
        $points_string = sprintf( _n( '%d Point', '%d Points', $points, 'wpachievements'), $points);
        if ( $points > 0 ) {
          $points_alert_class = 'success';
          $points_string =  '+' . $points_string;
        }
        else {
          $points_alert_class = 'danger';
        }
        ?>
        <dt>
          <div style="width:100%;">
            <?php
            if ( $user_id ) :
              if ( in_array( $achievement->ID, $userachievements ) ) : ?>
                <div class="wpa_ribbon"><span><?php _e("Unlocked", "wpachievements"); ?></span></div>
                <?php
              else : ?>
                <div class="wpa_ribbon wpa_ribbon_red"><span><?php _e("Locked", "wpachievements"); ?></span></div>
                <?php
              endif;
            endif; ?>

            <img src="<?php echo $image; ?>" alt="<?php echo $achievement->post_title; ?> Icon" width="120" />
          </div>
          <div><?php echo $achievement->post_title; ?></div>
        </dt>
        <dd>
          <div class="wpa_description">
            <div class="wpa_description_image">
              <img class="achievement_badge" src="<?php echo $image; ?>" alt="<?php echo $achievement->post_title; ?> Icon" width="120" />
              <p class="wpa-alert wpa-alert-<?php echo $points_alert_class; ?>"><?php echo $points_string; ?></p>
            </div>
            <div class="wpa_description_content">
              <p><?php echo $achievement->post_content; ?></p>
              <h3><?php _e('How do I get this?', 'wpachievements'); ?></h3>
              <p><?php echo __('Get this achievement', 'wpachievements').' '. WPAchievements()->achievement()->get_description( $type, '', $occurences ); ?></p>
            </div>
          </div>
        </dd>
        <?php
      endforeach;
      ?>
    </dl>
    <?php

    return ob_get_clean();
  }

  public static function achievement( $atts ) {
    $atts['post_type'] = 'wpachievements';

    return self::single_achievement_quest( $atts );
  }

  public static function quest( $atts ) {
    $atts['post_type'] = 'wpquests';
    $atts['quest_steps'] = wpa_quest_steps( $atts['post_id'] );

   return self::single_achievement_quest( $atts );
  }

  /**
   * Show a certain achievement
   * [wpa_achievement post_id="" show_title="" ]
   *
   * @static
   * @access  public
   * @param   array $atts Shortcode parameters
   * @return  string
   */
  public static function single_achievement_quest( $atts ) {

    extract( shortcode_atts( array(
      'post_id' => '',
      'show_title' => 'true',
      'show_description' => 'true',
      'show_image'    => 'true',
      'show_trigger' => 'true',
      'trigger_title' => '',
      'post_type' => 'wpachievements',
      'quest_steps' => array(),
    ), $atts ) );

    $type = ( 'wpachievements' == $post_type ) ? 'achievement' : 'quest';

    ob_start();

    if ( ! $post_id ) {
      echo "<p>" . __( "No achievement or quest selected.", "wpachievements" ) . "</p>";
      return ob_get_clean();
    }

    $achievement = new WP_Query( array( 'p' => intval( $post_id ),  'post_type' => $post_type ) );

    if ( $achievement->have_posts() ) {
      echo '<div class="wpa_achievement_container">';

      while ( $achievement->have_posts() ) {
        $achievement->the_post();

        $title =  get_the_title();

        if ( 'true' == strtolower( $show_image ) ) {
          $image = get_post_meta( $post_id, '_' . $type . '_image', true );
          $img_alt_tag = sprintf( __("%s Badge", 'wpachievements' ), stripslashes( $title ) );

          if ( $image ) {
            echo '<div class="wpa_achievement_badge_wrap">';
            echo '<img src="' . $image . '" class="wpa_achievement_badge" alt="' . $img_alt_tag . '" />';
            echo '</div>';
          }
        }

        echo '<div class="wpa_achievement_content">';

        if ( 'true' == strtolower( $show_title ) ) {
          echo '<div class="wpa_achievement_title">' . $title . '</div>';
        }

        if ( 'true' == strtolower( $show_description ) ) {
          echo apply_filters( 'the_content', get_the_content() );
        }

        if ( 'true' == strtolower( $show_trigger ) ) {
          if ( $trigger_title ) {
            echo '<div class="wpa_achievement_trigger_title">' . $trigger_title . '</div>';
          }

          switch ($type ) {
            case 'achievement': {
              $occurences = get_post_meta( $post_id, '_achievement_occurrences', true );
              $trigger = get_post_meta( $post_id, '_achievement_type', true );
              $custom_trigger_desc = get_post_meta( $post_id, '_achievement_trigger_desc', true );

              $description = ( $trigger == 'custom_trigger' ) ? $custom_trigger_desc : WPAchievements()->achievement()->get_description( $trigger, '', $occurences );

              echo '<p>' . sprintf( __('Gain this achievement %s.', 'wpachievements'), $description ) . '</p>';
            } break;

            case 'quest': {
              if ( $quest_steps ) {
                echo '<ol class="wpachievements_quest_steps">';
                foreach ($quest_steps as $quest_step ) {
                  echo "<li>{$quest_step}</li>";
                }
                echo "</ol>";
              }
            } break;
          }
        }

        echo '</div>';
      }

      echo '</div>';

      /* Restore original Post Data */
      wp_reset_postdata();
    }
    else {
      echo "<p>" . __( "Nothing found.", "wpachievements" ) . "</p>";
    }

    return ob_get_clean();
  }

  /**
   * Handle wpa_if_achievement, wpa_if_quest and wpa_if_rank shortcode
   *
   * Checks if a user has gained a specific achievement and displays the conditional content
   * Usage example:
   * [wpa_if_achievement post_id="xx"]
   * <p>Yes the user has gained this achievement</p>
   * [wpa_else_achievement]
   * <p>Achievement not yet gained</p>
   * [/wpa_if_achievement]
   *
   * @static
   * @access  public
   * @param   array $atts    Shortcode params
   * @param   string $content Content inside shortcode tags
   * @param   string $tag shortcode tag
   * @return  string
   */
  public static function conditional( $atts, $content, $tag ) {

    // Generate the conditional check callback
    $callback = array( __CLASS__, $tag );

    if ( ! is_callable( $callback ) ) {
      // Something went wrong. Can't find the callback function
      return;
    }

    // Check if condition is fulfilled
    $condition = call_user_func( $callback, $atts );

    // Generate the else conditional tag
    $else = '[' . str_replace( 'wpa_if_', 'wpa_else_', $tag ) . ']';

    if ( strpos($content, $else ) !== false ) {
      list( $if_statement, $else ) = explode( $else, $content, 2 );
    }
    else {
      $if_statement = $content;
      $else         = "";
    }

    return do_shortcode( $condition ? $if_statement : $else );
  }

  /**
   * Check if user has gained an achievement
   *
   * @version 6.0.0
   * @since   6.0.0
   * @static
   * @access  public
   * @param   array  $atts
   * @return  boolean
   */
  public static function wpa_if_achievement( $atts = array() ) {

    if ( ! is_user_logged_in() || empty( $atts['post_id'] ) ) {
      return false;
    }

    return WPAchievements_User::has_achievement( get_current_user_id(), $atts['post_id'] );
  }

  /**
   * Check if user has solved a quest
   *
   * @static
   * @access  public
   * @param   array  $atts
   * @return  boolean
   */
  public static function wpa_if_quest( $atts = array() ) {

    if ( ! is_user_logged_in() || empty( $atts['post_id'] ) ) {
      return false;
    }

    return WPAchievements_User::has_quest( get_current_user_id(), $atts['post_id'] );
  }

  /**
   * Check if user has a specific rank
   *
   * @static
   * @access  public
   * @param   array  $atts
   * @return  boolean
   */
  public static function wpa_if_rank( $atts = array() ) {

    if ( ! is_user_logged_in() || empty( $atts['rank'] ) || empty( $atts['condition'] ) ) {
      return false;
    }

    switch( $atts['condition'] ) {
      case 'minimal': {
        $user_points = WPAchievements_User::get_points( get_current_user_id() );
        $required_rank_points = wpachievements_rankToPoints( $atts['rank'] );

        if ( $user_points >= $required_rank_points ) {
          return true;
        }
      } break;

      default: {
        return WPAchievements_User::has_rank( get_current_user_id(), $atts['rank'] );
      } break;
    }

    return false;
  }
}
