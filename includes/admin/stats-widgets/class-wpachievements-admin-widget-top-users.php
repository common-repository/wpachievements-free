<?php
/**
 * Display the top users ordered by number of plays
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class WPAchievemenets_Admin_Widget_Top_Users {

  public static function output() {

    $users = WPAchievements_Admin_Stats::get_top_users();
    ?>
    <table width="100%" class="widefat wpachievements-table-stats" id="wpachievements-top-users">
      <tbody>
        <tr>
          <th>
            <?php _e( 'User', 'wpachievements' ); ?>
          </th>
          <th>
            <?php _e( 'Rank', 'wpachievements' ); ?>
          </th>
          <th class="th-center">
            <?php _e( 'Points', 'wpachievements' ); ?>
          </th>
        </tr>
        <?php
        if ( $users ) {
          foreach ( $users as $user ) {
            $user_data = get_userdata( $user->user_id );
            if ( $user_data ) {
              $user_name = $user_data->user_nicename;
            }
            else {
              $user_name = __( "Unknown", 'wpachievements' );
            }

            $rank = wpachievements_getRank( $user->user_id );

            echo '<tr>';
            echo '<th>'.$user_name.'</th>';
            echo '<th>'.$rank.'</th>';
            echo '<th class="th-center"><span>'.$user->points.'</span></th>';
            echo '</tr>';
          }
        }
        ?>
      </tbody>
    </table>
    <?php
  }
}

WPAchievemenets_Admin_Widget_Top_Users::output();
