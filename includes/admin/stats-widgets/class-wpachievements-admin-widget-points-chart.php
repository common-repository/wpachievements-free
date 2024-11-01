<?php
/**
 * Display the points diagram
 */

class WPAchievements_Admin_Widget_Points_Chart {

  public static function output() {
    $days = 7;
    $awarded = "";
    //$deducted = "";

    // Collect data for the graph
    for( $count = $days; $count >= 0; $count-- ) {
      $awarded .= "['" . WPAchievements_Admin_Stats::get_date( '-' . $count ) . "'," . WPAchievements_Admin_Stats::get_points( '-' . $count ) . "]";

      //$deducted_points = WPAchievements_Admin_Stats::get_points( '-' . $count, 'deducted' ) * -1;
      //$deducted .= "['" . WPAchievements_Admin_Stats::get_date( '-' . $count ) . "'," . $deducted_points . "]";

      if ( $count > 0 ) {
       $awarded .= ',';
       //$deducted .= ',';
      }
    }

    // Generate the graph
    ?>
    <div id="points-chart" style="height:300px;"></div>

    <script type="text/javascript">
       var poits_chart;

       jQuery(document).ready(function($) {
         points_chart = $.jqplot( 'points-chart', [[<?php echo $awarded; ?>]], {
           animate: true,
           animateReplot: true,
           title: {
             text: <?php echo json_encode( sprintf( __( 'Points awarded in the last %s days', 'wpachievements' ), $days ) ); ?>,
             fontSize: '12px',
           },
           grid: {
             drawBorder: false,
             shadow: false,
             background: '#ffffff',
           },
           seriesColors: ["#00ACFF"],
           series:[
             {
               rendererOptions: {
                 smooth: true,
                 animation: {
                   speed: 1000
                 }
               },
               fill: true,
               fillColor: '#F0FAFF',
               fillAndStroke: true,
             }
           ],
           axes: {
             xaxis: {
               min: '<?php echo WPAchievements_Admin_Stats::get_date( '-' . $days ); ?>',
               max: '<?php echo WPAchievements_Admin_Stats::get_date(); ?>',
               tickInterval: '1 day',
               renderer: $.jqplot.DateAxisRenderer,
               tickRenderer: $.jqplot.CanvasAxisTickRenderer,
               tickOptions: {
                 fontSize: '10px',
                 angle: -45,
                 formatString: '%b %#d',
                 showGridline: false,
               },
             },
             yaxis: {
               /*min: 0,*/
               padMin: 1.0,
               label: <?php echo json_encode( __( 'Number of points', 'wpachievements' ) ); ?>,
               labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
               labelOptions: {
                 angle: -90,
                 fontSize: '10px',
               },
             }
           },
           highlighter: {
             show: true,
             showMarker: true,
             formatString: '%s:&nbsp;<b>%d</b>&nbsp;',
             tooltipOffset: 3,
             sizeAdjust: 5,
           },
         });

         $(window).resize(function () {
           points_chart.replot({resetAxes: ['xaxis', 'yaxis']});
         });
      });
    </script>
    <?php
  }
}

WPAchievements_Admin_Widget_Points_Chart::output();
