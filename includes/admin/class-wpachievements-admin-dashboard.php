<?php
/**
 * Display a dashboard widget with useful activities
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class WPAchievements_Admin_Dashboard {

  /**
   * Hook in methods
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function init() {
    $show_dashboard = false;

    if ( is_multisite() ) {
      if ( is_main_site() ) {
        $show_dashboard = true;
      }
    }
    else {
      $show_dashboard = true;
    }

    if ( $show_dashboard ) {
      add_action('wp_dashboard_setup', array( __CLASS__, 'add_dashboard_widgets' ) );
    }
  }

  /**
   * Register dashboard widgets and include required CSS (inline)
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function add_dashboard_widgets() {
    wp_add_dashboard_widget( 'dashboard_wpachievements', 'WPAchievements: Recent Activity', array( __CLASS__, 'recent_activities_widget' ) );
    add_action( 'admin_head', array( __CLASS__, 'dashboard_css' ) );
  }

  /**
   * Generate dashboard widget output
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function recent_activities_widget() {
    global $wpdb;

    echo '<div id="inner_cont_hold">';
    echo '<h4>' .  __( "Most Recent Achievements:", 'wpachievements' ) . ' <a href="#" id="wpamra">'.__("Refresh", 'wpachievements').'</a></h4>';
    echo '<div id="wpamra_hold">';

    $activities = $wpdb->get_results( $wpdb->prepare("SELECT id, uid, data, points FROM ".WPAchievements()->get_table()." WHERE type LIKE %s ORDER BY id DESC LIMIT 0, 5", 'wpachievements_achievement%') );

    if ( is_array( $activities ) ) {
      foreach ( $activities as $activity ) :
        $user_info = get_user_by('id', $activity->uid);
        if( !empty($user_info) ){
          $ach_name = explode( ':',$activity->data);
          echo '<div class="achievements_item" id="achieve_'.$activity->id.'">';
          echo '<span><strong>'. $user_info->user_login .'</strong> '. __('gained the achievement: ', 'wpachievements') .' </span>';
          echo '<span><strong>'. $ach_name[0] .'</strong> '. __('and got ', 'wpachievements') .' </span>';
          echo '<span><strong>'. $activity->points .' '. __('points ', 'wpachievements') .'</strong> </span>';
          echo '</div>';
        }
      endforeach;
    }
    echo '</div>';
    echo '<br/>';
    echo '<h4>'.__("Most Recent Quests:" , "wpachievements").' <a href="#" id="wpamrq">'.__("Refresh", "wpachievements").'</a></h4>';
    echo '<div id="wpamrq_hold">';

    $activities = $wpdb->get_results( $wpdb->prepare("SELECT id, uid, type, data, points FROM ".WPAchievements()->get_table()." WHERE points <> 0 AND type LIKE %s ORDER BY id DESC LIMIT 0, 5",'wpachievements_quest%') );

    if ( is_array( $activities ) ) {
      foreach ( $activities as $activity ) :
        $user_info = get_user_by('id', $activity->uid);
        if( !empty($user_info) ){
          $ach_name = explode( ':',$activity->data);
          echo '<div class="achievements_item" id="achieve_'.$activity->id.'">';
          echo '<span><strong>'. $user_info->user_login .'</strong> '. __('gained the quest: ', 'wpachievements') .' </span>';
          echo '<span><strong>'. $ach_name[0] .'</strong> '. __('and got ', 'wpachievements') .' </span>';
          echo '<span><strong>'. $activity->points .' '. __('points ', 'wpachievements') .'</strong> </span>';
          echo '</div>';
        }
      endforeach;
    }
    echo '</div>';
    echo '<br/>';
    echo '<h4>'.__("Most Recent Points:", "wpachievements").' <a href="#" id="wpamrp">'.__("Refresh", "wpachievements").'</a></h4>';
    echo '<div id="wpamrp_hold">';

    $activities = $wpdb->get_results( $wpdb->prepare("SELECT id, uid, type, data, points FROM ".WPAchievements()->get_table()." WHERE points <> 0 AND type NOT LIKE %s AND type NOT LIKE %s ORDER BY id DESC LIMIT 0, 5",'wpachievements_achievement%','wpachievements_quest%') );

    if ( is_array( $activities ) ) {
      foreach ( $activities as $activity ) :
        $user_info = get_user_by('id', $activity->uid);
        if( !empty($user_info) ){
          $type_text = WPAchievements()->achievement()->get_description($activity->type,$activity->points,'a ',$activity->data);
          if( $activity->points > 0 ){
            $point_type = __('gained', 'wpachievements');
          } else{
            $point_type = __('lost', 'wpachievements');
          }
          echo '<div class="achievements_item" id="achieve_'.$activity->id.'">';
          echo '<span><strong>'. $user_info->user_login .'</strong> '. $point_type .' </span>';
          echo '<span><strong>'. $activity->points .' '. __('points ', 'wpachievements') .'</strong> </span>';
          echo '<span>'. $type_text .' </span>';
          echo '</div>';
        }
      endforeach;
    }
    echo '</div>';
    echo '</div>';
  }

  /**
   * Widget CSS (inlune)
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function dashboard_css() {
    echo '<style>
    #dashboard_wpachievements h4{color:#333;font-size:16px;border-bottom:1px solid #ccc;padding:5px 10px 5px 0;}
    #dashboard_wpachievements h4 a{float:right;font-size:12px;margin-top:3px;}
    #dashboard_wpachievements #loader-icon img{display:block;margin:20px auto 10px;}
    #dashboard_wpachievements .achievements_item{color:#666;padding:2px 0;}
    #dashboard_wpachievements .achievements_item strong{color:#444;}
    #dashboard_wpachievements .sbHolder{border:4px solid #D1D1D1 !important;margin:10px auto -5px;}
    </style>';
    echo "<script>
    jQuery(document).ready(function(){
      jQuery('#dashboard_wpachievements h4 a').click(function(event){
        event.preventDefault();
        if( jQuery(this).attr('id') == 'wpamra' ){
          jQuery('#dashboard_wpachievements #wpamra_hold').hide('slow').load('".admin_url()." #dashboard_wpachievements #wpamra_hold', function() {
            jQuery('#dashboard_wpachievements #wpamra_hold').show('slow');
          });
        } else if( jQuery(this).attr('id') == 'wpamrp' ){
          jQuery('#dashboard_wpachievements #wpamrp_hold').hide('slow').load('".admin_url()." #dashboard_wpachievements #wpamrp_hold', function() {
            jQuery('#dashboard_wpachievements #wpamrp_hold').show('slow');
          });
        } else if( jQuery(this).attr('id') == 'wpamrq' ){
          jQuery('#dashboard_wpachievements #wpamrq_hold').hide('slow').load('".admin_url()." #dashboard_wpachievements #wpamrq_hold', function() {
            jQuery('#dashboard_wpachievements #wpamrq_hold').show('slow');
          });
        }
      });";
    echo "});
    </script>";
  }
}

WPAchievements_Admin_Dashboard::init();