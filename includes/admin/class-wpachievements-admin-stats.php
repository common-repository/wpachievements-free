<?php
/**
 * Methods to calculate the statistics
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class WPAchievements_Admin_Stats {

  /**
   * Generate the stats page output with charts and data
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function output() {

    // Load required scripts
    self::load_scripts();

    wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );

    self::register_meta_boxes();
    ?>
    <div class="wrap wpachievements">
      <h2><?php _e("Reports", 'wpachievements'); ?></h2>
      <br />
      <?php if ( "yes" == wpachievements_get_site_option( 'wpachievements_allow_tracking' ) ) : ?>
      <div class="metabox-holder" id="overview-widgets">
        <div class="postbox-container" id="wpachievements-postbox-container-1">
          <?php do_meta_boxes( 'wpachievements_stats_metaboxes', 'side', '' ); ?>
        </div>

        <div class="postbox-container" id="wpachievements-postbox-container-2">
          <?php do_meta_boxes( 'wpachievements_stats_metaboxes', 'normal', '' ); ?>
        </div>
      </div>
      <div style="clear:both"></div>
      <div>
        <?php printf( __( '%sDisable these reports%s and relinquish the benefits.', 'wpachievements' ), '<a href="'.esc_url( wp_nonce_url( add_query_arg( "wpachievements_tracker_optout", "true" ), "wpachievements_tracker_optout", "wpachievements_tracker_nonce" ) ).'">', '</a>' ); ?>
      </div>
      <script type="text/javascript">
        jQuery(document).ready(function () {
          // postboxes setup
          postboxes.add_postbox_toggles( 'wpachievements_stats_metaboxes' );
        });
      </script>
      <?php else:
        WPAchievements_Tracker::tracking_message(true);
      endif; ?>
    </div>
    <?php
  }

  /**
   * Load required scripts for graphs and dashboard boxes
   *
   * @static
   * @access  private
   * @return  void
   */
  private static function load_scripts() {
    wp_enqueue_script( 'common' );
    wp_enqueue_script( 'wp-lists' );
    wp_enqueue_script( 'postbox' );

    $backend_script_path = str_replace( array( 'http:', 'https:' ), '', WPAchievements()->plugin_url() . '/assets/' );

    wp_enqueue_style( 'jqplot-css', $backend_script_path . 'js/jqplot/jquery.jqplot.min.css', true, '1.0.9' );

    // Load the charts code.
    wp_enqueue_script( 'jqplot', $backend_script_path . 'js/jqplot/jquery.jqplot.min.js', true, '1.0.9' );
    wp_enqueue_script( 'jqplot-daterenderer', $backend_script_path . 'js/jqplot/plugins/jqplot.dateAxisRenderer.min.js', true, '1.0.9' );
    wp_enqueue_script( 'jqplot-tickrenderer', $backend_script_path . 'js/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js', true, '1.0.9' );
    wp_enqueue_script( 'jqplot-axisrenderer', $backend_script_path . 'js/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js', true, '1.0.9' );
    wp_enqueue_script( 'jqplot-textrenderer', $backend_script_path . 'js/jqplot/plugins/jqplot.canvasTextRenderer.min.js', true, '1.0.9' );
    wp_enqueue_script( 'jqplot-tooltip', $backend_script_path . 'js/jqplot/plugins/jqplot.highlighter.min.js', true, '1.0.9' );
    wp_enqueue_script( 'jqplot-donutrenderer', $backend_script_path . 'js/jqplot/plugins/jqplot.donutRenderer.min.js', true, '1.0.9' );
  }

  /**
   * Get the widget content
   *
   * @access  public
   * @return  void
   */
  public function get_widget_content() {

    // Get the requested widget
    $widget = filter_input( INPUT_POST, 'widget' );

    $widget_file = WPAchievements()->plugin_path() . '/includes/admin/stats-widgets/class-wpachievements-admin-widget-' . $widget . '.php';


    if ( file_exists( $widget_file ) ) {
      include_once( $widget_file );
    }

    wp_die();
  }

  /**
   * Register available postbox/stats widgets
   *
   * @static
   * @access  public
   * @return  void
   */
  private static function register_meta_boxes() {

    // Left Sidebar

    add_meta_box(
      'wpachievements_awarded_points_postbox',
      __( "Awarded Points", 'wpachievements' ),
      array(__CLASS__, 'generate_postbox_content' ),
      'wpachievements_stats_metaboxes',
      'side',
      null,
      array( 'widget' => 'awarded-points' )
    );

    add_meta_box(
      'wpachievements_deducted_points_postbox',
      __( "Deducted Points", 'wpachievements'),
      array( __CLASS__, 'generate_postbox_content' ),
      'wpachievements_stats_metaboxes',
      'side',
      null,
      array( 'widget' => 'deducted-points' )
    );

    add_meta_box(
      'wpachievements_ranks_postbox',
      __( "Ranks", 'wpachievements' ),
      array(__CLASS__, 'generate_postbox_content' ),
      'wpachievements_stats_metaboxes',
      'side',
      null,
      array( 'widget' => 'ranks' )
    );

    add_meta_box(
      'wpachievements_top-users_postbox',
      __( "Top Users", 'wpachievements' ),
      array( __CLASS__, 'generate_postbox_content' ),
      'wpachievements_stats_metaboxes',
      'side',
      null,
      array( 'widget' => 'top-users' )
    );

    // Main Widget Area

    add_meta_box(
      'wpachievements_points_chart_postbox',
      __( "Points Chart", 'wpachievements' ),
      array( __CLASS__, 'generate_postbox_content' ),
      'wpachievements_stats_metaboxes',
      'normal',
      null,
      array( 'widget' => 'points-chart' )
    );

    add_meta_box(
      'wpachievements_latest_achievements_postbox',
      __("Latest Achievements", 'wpachievements'),
      array( __CLASS__, 'generate_postbox_content' ),
      'wpachievements_stats_metaboxes',
      'normal',
      null,
      array( 'widget' => 'latest-achievements' )
    );

    add_meta_box(
      'wpachievements_latest_quests_postbox',
      __("Latest Quests", 'wpachievements'),
      array( __CLASS__, 'generate_postbox_content' ),
      'wpachievements_stats_metaboxes',
      'normal',
      null,
      array( 'widget' => 'latest-quests' )
    );

    add_meta_box(
      'wpachievements_latest_points_postbox',
      __("Latest Points", 'wpachievements'),
      array( __CLASS__, 'generate_postbox_content' ),
      'wpachievements_stats_metaboxes',
      'normal',
      null,
      array( 'widget' => 'latest-points' )
    );
  }

  /**
   * Generate the postbox content
   *
   * @static
   * @access  public
   * @param   string $post Unused
   * @param   array $args Widget parameters
   * @return  void
   */
  public static function generate_postbox_content( $post, $args ) {

    // Set the loading image
    $loading_img = '<div style="width: 100%; text-align: center;"><img src=" ' . WPAchievements()->plugin_url() . '/assets/img/loading.gif" alt="' . __( 'Loading...', 'wpachievements' ) . '"></div>';
    // Generate the container id
    $container_id = str_replace( '.', '_', $args['args']['widget'] . '_postbox' );

    if ( ! $container_id ) {
      return;
    }

    // Echo the placeholder div
    echo '<div id="' . $container_id . '">' . $loading_img . '</div>';

    // Now we can load the widget content with javascript
    ?>
    <script type="text/javascript">
      jQuery(document).ready( function($) {
        function wpachievements_stats_get_widget_content( widget, container_id ) {
          var data = {
            'action': 'wpachievements_stats_get_widget_content',
            'widget': widget
          };

          container = $("#" + container_id);

          if ( container.is(':visible') ) {
            $.ajax({
              url: ajaxurl,
              type: 'post',
              data: data,
              datatype: 'json',
            })
            .always( function(result) {
              // Take the returned result and add it to the DOM.
              $("#" + container_id).html("").html(result);
            })
            .fail( function(result) {
              // If we fail for some reason, like a timeout, try again.
              container.html("ERROR");
              //wpachievements_stats_get_widget_content(widget, container_id);
            });
          }
        }
        wpachievements_stats_get_widget_content( '<?php echo $args['args']['widget']; ?>', '<?php echo $container_id; ?>' );
      });
    </script>
    <?php
  }

  /**
   * Get the play count for a time period
   *
   * @static
   * @access  public
   * @param   string  $time_period  Number of days or 'total'
   * @param   string  $type         awarded|deducted
   * @return  integer
   */
  public static function get_points( $time_period, $type = 'awarded' ) {
    global $wpdb;

    $result = 0;
    $query  = '';

    if ( $type == "deducted" ) {
      $sum_query = "SUM(CASE WHEN points<0 THEN points ELSE 0 END)";
    }
    else {
      $sum_query = "SUM(CASE WHEN points>0 THEN points ELSE 0 END)";
    }

    switch ( $time_period ) {

      case 'today': {
        $query = "SELECT {$sum_query} FROM ".WPAchievements()->get_table()." WHERE DATE_FORMAT( FROM_UNIXTIME(`timestamp`), '%Y-%m-%d' ) = '".self::get_date()."'";
      } break;

      case 'yesterday': {
        $query = "SELECT {$sum_query} FROM ".WPAchievements()->get_table()." WHERE DATE_FORMAT( FROM_UNIXTIME(`timestamp`), '%Y-%m-%d' ) = '".self::get_date( '-1' )."'";
      } break;

      case 'week': {
        $query = "SELECT {$sum_query} FROM ".WPAchievements()->get_table()." WHERE DATE_FORMAT( FROM_UNIXTIME(`timestamp`), '%Y-%m-%d' ) BETWEEN '".self::get_date( '-7' )."' AND '".self::get_date()."'";
      } break;

      case 'month': {
        $query = "SELECT {$sum_query} FROM ".WPAchievements()->get_table()." WHERE DATE_FORMAT( FROM_UNIXTIME(`timestamp`), '%Y-%m-%d' ) BETWEEN '".self::get_date( '-30' )."' AND '".self::get_date()."'";
      } break;

      case 'year': {
        $query = "SELECT {$sum_query} FROM ".WPAchievements()->get_table()." WHERE DATE_FORMAT( FROM_UNIXTIME(`timestamp`), '%Y-%m-%d' ) BETWEEN '".self::get_date( '-365' )."' AND '".self::get_date()."'";
      } break;

      case 'total': {
        $query = "SELECT SUM(meta_value) FROM {$wpdb->prefix}usermeta WHERE meta_key = 'achievements_points'";
      } break;

      default: {
        $query = "SELECT {$sum_query} FROM ".WPAchievements()->get_table()." WHERE DATE_FORMAT( FROM_UNIXTIME(`timestamp`), '%Y-%m-%d' ) = '".self::get_date( $time_period )."'";
      } break;
    }

    if ( $query ) {
      $result = $wpdb->get_var( $query );
    }

    return intval( $result );
  }

  /**
   * Get awarded points grouped by hour by day
   *
   * @static
   * @access  public
   * @param   string $date_offset 0 = Today, -1 = Yesterday ...
   * @return  array      Array of hours and plays
   */
  public static function get_houry_points( $date_offset ) {
    global $wpdb;

    $data = array();

    // Populate initial data
    for( $index = 0; $index <= 23; $index++ ) {
      $data[$index] = 0;
    }

    // Rund the query
    $query = "SELECT HOUR(`date`) as hour, COUNT(*) as plays FROM {$wpdb->prefix}wpachievements_plays WHERE DATE_FORMAT( `date`, '%Y-%m-%d' ) = '".self::get_date( $date_offset )."' GROUP BY HOUR(`date`)";

    $results = $wpdb->get_results( $query );

    if ( $results ) {
      foreach ( $results as $result ) {
        $data[ $result->hour ] = intval( $result->plays );
      }
    }

    return $data;
  }

  /**
   * Get ranks
   *
   * @param string $what
   * @return int
   */
  public static function get_ranks( $what = 'total' ) {
    global $wpdb;

    $ranks = (array) wpachievements_get_site_option( 'wpachievements_ranks_data' );
    ksort($ranks);
    //$ranks = array_reverse($ranks, 1);
    $rank_points = array_keys($ranks);
    $rank_total = count($ranks);

    switch( $what ) {
      case 'total': {
        return $rank_total;
      } break;

      case 'on_highest': {
        $last_index = $rank_total - 1;
        $points = intval( $rank_points[ $last_index ] );

        $query = "SELECT COUNT(*) FROM {$wpdb->prefix}usermeta WHERE meta_key = 'achievements_points' AND meta_value >= {$points}";
        return intval( $wpdb->get_var( $query  ) );
      } break;

      case 'on_lowest': {
        if ( $rank_points > 1 ) {
          $index = 1;
        }
        else {
          $index = 0;
        }

        $points = intval( $rank_points[ $index ] );

        $query = "SELECT COUNT(*) FROM {$wpdb->prefix}usermeta WHERE meta_key = 'achievements_points' AND meta_value < {$points}";
        return intval( $wpdb->get_var( $query  ) );
      } break;
    }

    return 10;
  }

  public static function get_top_users( $count = 10 ) {
    global $wpdb;

    $users = $wpdb->get_results( "SELECT user_id, meta_value as points FROM {$wpdb->prefix}usermeta WHERE meta_key = 'achievements_points' ORDER BY CAST(`meta_value` AS INT) DESC LIMIT {$count}" );

    return $users;
  }

  /**
   * Retrieve top games ordered by game play count
   *
   * @static
   * @access  public
   * @param   integer $count
   * @return  WP_Query
   */
  public static function get_latest_activities( $what = 'achievement', $count = 10 ) {
    global $wpdb;

    if ( 'point' == $what ) {
      $activities = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".WPAchievements()->get_table()." WHERE points <> 0 AND type NOT LIKE %s AND type NOT LIKE %s ORDER BY id DESC LIMIT {$count}",'wpachievements_achievement%','wpachievements_quest%') );
    }
    else {
      $query_type = "wpachievements_".$what."%";
      $activities = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".WPAchievements()->get_table()." WHERE type LIKE %s ORDER BY id DESC LIMIT {$count}", $query_type) );
    }

    return $activities;
  }

  /**
   * Get a date based on the site offset
   *
   * @static
   * @access  public
   * @param   string $day_offset Offset in days (0, -1,-7,-30,-365)
   * @param   string $format Date format (Y-m-d)
   * @return  string         Date
   */
  public static function get_date( $day_offset = false, $format = 'Y-m-d' ) {

    // Get the site offset
    $offset = get_option( 'gmt_offset' ) * 60 * 60;

    if ( $day_offset ) {
      $date = date( $format, strtotime( "{$day_offset} day" ) + $offset );
    }
    else {
      $date = date( $format, time() + $offset );
    }

    return $date;
  }
}
