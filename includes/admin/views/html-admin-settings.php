<?php
/**
 * Admin View: Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
?>
<div class="wrap wpachievements">
  <form method="post" id="mainform" action="" enctype="multipart/form-data">
    <div class="icon32 icon32-wpachievements-settings" id="icon-wpachievements"><br /></div>
    <h2 class="nav-tab-wrapper">
      <?php
      foreach ( $tabs as $name => $label ) {
        echo '<a href="' . admin_url( 'admin.php?page=wpachievements_settings&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
      }
      do_action( 'wpachievements_settings_tabs' );
      ?>
    </h2>

    <?php
    do_action( 'wpachievements_sections_' . $current_tab );
    do_action( 'wpachievements_settings_' . $current_tab );
    do_action( 'wpachievements_settings_tabs_' . $current_tab );
    ?>

    <p class="submit">
      <input name="save" class="button-primary" type="submit" value="<?php _e( 'Save changes', 'wpachievements' ); ?>" />
      <?php wp_nonce_field( 'wpachievements-settings' ); ?>
    </p>
  </form>
</div>