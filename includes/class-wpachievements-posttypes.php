<?php
/**
 * Register Post Types
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class WPAchievements_PostTypes {

  public static function register() {

    // Achievements
    $labels = array(
      'name' => __( 'Achievements', 'wpachievements' ),
      'singular_name' => __( 'Achievement', 'wpachievements' ),
      'add_new' => __( 'Add New Achievement' , 'wpachievements' ),
      'add_new_item' => __( 'Add New Achievement' , 'wpachievements' ),
      'edit_item' =>  __( 'Edit Achievement' , 'wpachievements' ),
      'new_item' => __( 'New Achievement' , 'wpachievements' ),
      'view_item' => __('View Achievement', 'wpachievements'),
      'search_items' => __('Search Achievements', 'wpachievements'),
      'not_found' =>  __('No Achievements Found', 'wpachievements'),
      'not_found_in_trash' => __('No Achievements Found in Trash', 'wpachievements'),
    );

    register_post_type('wpachievements', array(
      'labels' => $labels,
      'public' => false,
      'show_ui' => true,
      'hierarchical' => true,
      'rewrite' => false,
      'query_var' => "wpachievements",
      'supports' => array(
        'title'
      ),
      'show_in_menu'  => false,
    ));

    // Quests
    $labels = array(
      'name' => __( 'Quests', 'wpachievements' ),
      'singular_name' => __( 'Quest', 'wpachievements' ),
      'add_new' => __( 'Add New Quest' , 'wpachievements' ),
      'add_new_item' => __( 'Add New Quest' , 'wpachievements' ),
      'edit_item' =>  __( 'Edit Quest' , 'wpachievements' ),
      'new_item' => __( 'New Quest' , 'wpachievements' ),
      'view_item' => __('View Quest', 'wpachievements'),
      'search_items' => __('Search Quests', 'wpachievements'),
      'not_found' =>  __('No Quests Found', 'wpachievements'),
      'not_found_in_trash' => __('No Quests Found in Trash', 'wpachievements'),
    );

    register_post_type('wpquests', array(
      'labels' => $labels,
      'public' => false,
      'show_ui' => true,
      'hierarchical' => true,
      'rewrite' => false,
      'query_var' => "wpquests",
      'supports' => array(
        'title'
      ),
      'show_in_menu'  => false,
    ));
  }
}

WPAchievements_PostTypes::register();