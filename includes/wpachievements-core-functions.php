<?php
/**
 * Core functions available on bothe the front-end and admin
 */

/**
 * Retrieve an option depending on WP site (multisite, single blog)
 *
 * @param   string $option_name Option name
 * @param   string $default Default value if setting is not set
 * @return  mixed Option value
 */
function wpachievements_get_site_option( $option_name, $default = false ) {

  if ( is_multisite() ) {
    $value = get_blog_option( 1, $option_name, $default );
  }
  else {
    $value = get_option( $option_name, $default );
  }

  return $value;
}

/**
 * Update an option depending on WP site (multisite, single blog)
 *
 * @param string $option_name Option name
 * @param mixed $value
 * @return void
 */
function wpachievements_update_site_option( $option_name, $value ) {
  if ( is_multisite() ) {
    update_blog_option( 1, $option_name, $value );
  }
  else {
    update_option( $option_name, $value);
  }
}

function wpa_achievements_page_content( $content ) {

  $wpa_page_id = intval( wpachievements_get_site_option( 'wpachievements_ach_page' ) );

  if ( $wpa_page_id && is_page( $wpa_page_id ) ) {
    // Replace Content
    $content = do_shortcode( '[wpa_achievements show_title="true" title="' . __('Our Achievements', 'wpachievements') . '" title_heading="h2"]' );
    $content .= do_shortcode('[wpa_quests show_title="true" title="' . __('Our Quests', 'wpachievements' ) . '" title_heading="h2"]');
  }

  return $content;
}
add_filter( 'the_content', 'wpa_achievements_page_content' );
