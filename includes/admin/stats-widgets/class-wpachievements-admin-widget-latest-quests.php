<?php
/**
 * Display the summary stats
 */

class WPAchievements_Admin_Widget_Latest_Achievements {

  public static function output() {

    $activities = WPAchievements_Admin_Stats::get_latest_activities( 'quest' );
    ?>
    <table width="100%" class="widefat wpachievements-table-stats" id="wpachievements-latest-plays">
      <thead>
        <tr>
          <th class="th-center">
            <?php _e( 'User', 'wpachievements' ); ?>
          </th>
          <th class="th-center">
            <?php _e( 'Quest', 'wpachievements' ); ?>
          </th>
          <th class="th-center">
            <?php _e( 'Points', 'wpachievements' ); ?>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ( $activities ) {
          foreach ( $activities as $activity ) {
            $user_info = get_user_by( 'id', $activity->uid );
            $ach_name = explode( ':', $activity->data );

            echo '<tr>';
            echo '<td class="th-center">'.$user_info->user_nicename.'</td>';
            echo '<td class="th-center">'.$ach_name[0].'</td>';
            echo '<td class="th-center">'.$activity->points.'</td>';
            echo '</tr>';
          }
        }
        else {
          echo '<tr><td colspan="4">'.__( "No activities found", 'wpachievements').'</td></tr>';
        }
        ?>
      </tbody>
    </table>
    <?php
  }
}

WPAchievements_Admin_Widget_Latest_Achievements::output();