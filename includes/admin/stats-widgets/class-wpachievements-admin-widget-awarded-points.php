<?php
/**
 * Display the summary stats
 */

class WPAchievements_Admin_Widget_Points {

  public static function output() {
    ?>
    <table width="100%" class="widefat wpachievements-table-stats" id="wpachievements-summary-stats">
      <tbody>
        <tr>
          <th><?php _e( 'Today', 'wpachievements' ); ?></th>
          <th class="th-center"><span><?php echo number_format( WPAchievements_Admin_Stats::get_points( 'today', 'awarded' ) ); ?></span></th>
        </tr>
        <tr>
          <th><?php _e( 'Yesterday', 'wpachievements' ); ?></th>
          <th class="th-center"><span><?php echo number_format( WPAchievements_Admin_Stats::get_points( 'yesterday', 'awarded' ) ); ?></span></th>
        </tr>
        <tr>
          <th><?php _e( 'Last 7 Days', 'wpachievements' ); ?></th>
          <th class="th-center"><span><?php echo number_format( WPAchievements_Admin_Stats::get_points( 'week', 'awarded' ) ); ?></span></th>
        </tr>
        <tr>
          <th><?php _e( 'Last 30 Days', 'wpachievements' ); ?></th>
          <th class="th-center"><span><?php echo number_format( WPAchievements_Admin_Stats::get_points( 'month', 'awarded' ) ); ?></span></th>
        </tr>
        <tr>
          <th><?php _e( 'In Circulation', 'wpachievements' ); ?></th>
          <th class="th-center"><span><?php echo number_format( WPAchievements_Admin_Stats::get_points( 'total', 'awarded' ) ); ?></span></th>
        </tr>
      </tbody>
    </table>
    <?php
  }
}

WPAchievements_Admin_Widget_Points::output();
