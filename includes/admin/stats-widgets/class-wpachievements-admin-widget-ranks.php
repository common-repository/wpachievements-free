<?php
/**
 * Display the summary stats
 */

class WPAchievements_Admin_Widget_Ranks {

  public static function output() {
    ?>
    <table width="100%" class="widefat wpachievements-table-stats" id="wpachievements-summary-stats">
      <tbody>
        <tr>
          <th><?php _e( 'Available Ranks', 'wpachievements' ); ?></th>
          <th class="th-center"><span><?php echo WPAchievements_Admin_Stats::get_ranks( 'total' ); ?></span></th>
        </tr>
        <tr>
          <th><?php _e( 'Users on highest rank', 'wpachievements' ); ?></th>
          <th class="th-center"><span><?php echo WPAchievements_Admin_Stats::get_ranks( 'on_highest' ); ?></span></th>
        </tr>
        <tr>
          <th><?php _e( 'Users on lowest rank', 'wpachievements' ); ?></th>
          <th class="th-center"><span><?php echo WPAchievements_Admin_Stats::get_ranks( 'on_lowest' ); ?></span></th>
        </tr>
      </tbody>
    </table>
    <?php
  }
}

WPAchievements_Admin_Widget_Ranks::output();
