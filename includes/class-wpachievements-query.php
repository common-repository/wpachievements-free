<?php
/**
 * Class for handling database queries
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class WPAchievements_Query {

  /**
   * Query achievements and quests
   *
   * @param array $args
   * @return WP_Query
   */
  private static function get( $args = array() ) {

    $defaults = array(
     'post_status'    => 'publish',
     'posts_per_page' => -1,
     'orderby'        => 'date',
     'order'          => 'ASC',
    );

    $query_args = wp_parse_args( $args, $defaults );

    return get_posts( $query_args );
  }

  /**
   * Get all published achievements
   *
   * @param array $args
   * @return WP_Query
   */
  public static function get_achievements( $args = array() ) {

    $defaults = array(
     'post_type'      => 'wpachievements',
    );

    $query_args = wp_parse_args( $args, $defaults );

    return self::get( $query_args );
  }

  /**
   * Get all custom achievements
   *
   * @return WP_Query
   */
  public static function get_custom_achievements() {
    $args = array(
      'meta_query' => array(
        array(
          'key' => '_achievement_type',
          'value' => 'custom_trigger',
        ),
      ),
    );

    return self::get_achievements( $args );
  }

  /**
   * Get all quest
   *
   * @param array $args
   * @return WP_Query
   */
  public static function get_quests( $args = array() ) {

    $defaults = array(
      'post_type' => 'wpquests',
    );

    $query_args = wp_parse_args( $args, $defaults );

    return self::get( $query_args );
  }
}
