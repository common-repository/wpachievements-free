<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 ***********************************************************
 *   W P A C H I E V E M E N T S   A D M I N   S E T U P   *
 ***********************************************************
 */

require_once( "class-wpachievements-admin-achievements.php" );
require_once( "class-wpachievements-admin-quests.php" );
require_once( "class-wpachievements-admin-ranks.php" );
require_once( "class-wpachievements-admin-dashboard.php" );
require_once( "class-wpachievements-admin-shortcode-editor.php" );

 //*************** Setup Admin Scripts ***************\\
 add_action( 'admin_enqueue_scripts', 'wpachievements_admin_scripts' );
 add_action( 'admin_head', 'wpachievements_admin_head' );
function wpachievements_admin_scripts($hook) {

  if( 'users.php' == $hook ){
    wp_enqueue_style( 'user_management_style', WPACHIEVEMENTS_URL . '/assets/css/user-management.css' );
  } elseif( 'user-edit.php' == $hook ){
    wp_enqueue_style( 'JUI', WPACHIEVEMENTS_URL . '/assets/js/ui-darkness/css/ui-darkness/jquery-ui-1.10.3.custom.css' );
    wp_enqueue_style( 'user_management_style', WPACHIEVEMENTS_URL . '/assets/css/user-profile.css' );

    wp_enqueue_script( "UIScript", WPACHIEVEMENTS_URL . "/assets/js/ui-darkness/js/jquery-ui-1.10.3.custom.js" );
    wp_register_script( 'user_management_script', WPACHIEVEMENTS_URL . '/assets/js/user-profile-script.js', array('jquery','media-upload','thickbox') );
    wp_enqueue_media();
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'user_management_script' );
  }

  if( 'wpachievements_page_wpachievements_settings' != $hook && 'wpachievements_page_wpachievements_ranks' != $hook && 'wpachievements_page_wpachievements_achievements' != $hook && 'wpachievements_page_wpachievements_faq' != $hook )
   return;

  if( 'toplevel_page_wpachievements_admin' == $hook ){
  } elseif( 'wpachievements_page_wpachievements_settings' == $hook ){
    wp_enqueue_style( 'admin_settings', WPACHIEVEMENTS_URL . '/assets/css/admin-settings.css' );
  } elseif( 'wpachievements_page_wpachievements_faq' == $hook ){
    wp_enqueue_style( 'wpa_latest_info', WPACHIEVEMENTS_URL . '/assets/css/info.css' );
    if( get_bloginfo('version') >= 3.8 ){
      wp_enqueue_style( 'wpa_latest_info_3_8', WPACHIEVEMENTS_URL . '/assets/css/info-3.8.css' );
    }
    wp_enqueue_script( 'wpa_latest_info_script', WPACHIEVEMENTS_URL . '/assets/js/info.js', array('jquery') );
  } else{
    wp_enqueue_style( 'admin-settings', WPACHIEVEMENTS_URL . '/assets/css/admin-settings.css' );
    wp_enqueue_style( 'JUI', WPACHIEVEMENTS_URL . '/assets/js/ui-darkness/css/ui-darkness/jquery-ui-1.10.3.custom.css' );
    wp_enqueue_style( 'UI_Spinner', WPACHIEVEMENTS_URL . '/assets/css/admin.css' );
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_script( "UIScript", WPACHIEVEMENTS_URL . "/assets/js/ui-darkness/js/jquery-ui-1.10.3.custom.js" );
    wp_register_script( 'my-upload', WPACHIEVEMENTS_URL . '/assets/js/admin-script.js', array('jquery','media-upload','thickbox') );
    wp_enqueue_media();
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'my-upload' );
  }
}

function wpachievements_admin_head() {

  wp_register_script( 'wpachievements_admin_menu_script', WPACHIEVEMENTS_URL . '/assets/js/admin-menu-script.js', array('jquery') );
  wp_enqueue_script( 'wpachievements_admin_menu_script' );

  $screen = get_current_screen();

  if( $screen->id == 'edit-wpquests' || $screen->id == 'wpquests'  ){
    wp_register_script( 'wpachievements_admin_menu_active_script', WPACHIEVEMENTS_URL . '/assets/js/admin-menu-script-active.js', array('jquery') );
    wp_enqueue_script( 'wpachievements_admin_menu_active_script' );
  }

  if( $screen->id == 'wpachievements' || $screen->id == 'wpquests' || 'wpachievements_page_wpachievements_reports' == $screen->id ){
    wp_enqueue_style( 'wpachievements_admin_style', WPACHIEVEMENTS_URL . '/assets/css/admin.css' );
    if( get_bloginfo('version') >= 3.8 ){
      wp_enqueue_style( 'wpachievements_admin_style_3_8', WPACHIEVEMENTS_URL . '/assets/css/admin-3.8.css' );
    }
    wp_register_script( 'wpachievements_admin_script', WPACHIEVEMENTS_URL . '/assets/js/admin-script.js', array('jquery','media-upload','thickbox') );
    wp_enqueue_media();
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'wpachievements_admin_script' );

    wp_dequeue_script( 'autosave' );
  }
}

/**
 * Checks if the admin menu should be added.
 *
 * On multisite installations show the menu only on the main blog
 *
 * @version 8.2.0
 * @since   8.2.0
 * @return  boolean True if the admin menu should be displayed
 */
function wpachievements_should_add_menu() {
  global $blog_id;

  if ( is_multisite() && 1 != $blog_id ) {
    return false;
  }

  return true;
}

/**
 * Map settings option to WP user role ids
 *
 * @todo: This needs to be refactored
 *
 * @version 8.2.0
 * @since   8.2.0
 * @return  string WP user role id
 */
function wpachievements_get_admin_user_role() {

  $user_role = wpachievements_get_site_option('wpachievements_role');

  switch ( $user_role ) {
    case 'Editor':
      return 'moderate_comments';
    case 'Author':
      return 'edit_published_posts';
    case 'Contributor':
      return 'edit_posts';
    default:
      return 'manage_options';
  }
}

//*************** Setup Admin Menu ***************\\
add_action( 'admin_menu', 'wpachievements_admin_menu' );

function wpachievements_admin_menu() {

  if ( ! wpachievements_should_add_menu() ) {
    return;
  }

  add_menu_page(__("WPAchievements", 'wpachievements'), __("WPAchievements", 'wpachievements'), wpachievements_get_admin_user_role(), 'edit.php?post_type=wpachievements', '', WPACHIEVEMENTS_URL . '/assets/img/logo_small.png' );

  add_menu_page(__("WPQuests", 'wpachievements'), __("WPQuests", 'wpachievements'), wpachievements_get_admin_user_role(), 'edit.php?post_type=wpquests', '', WPACHIEVEMENTS_URL . '/assets/img/logo_small.png' );

  if ( 'Disable' != wpachievements_get_site_option('wpachievements_rank_status') ) {
    add_submenu_page( 'edit.php?post_type=wpachievements', __('WPAchievements - Ranks', 'wpachievements'), __('Ranks', 'wpachievements'), wpachievements_get_admin_user_role(), 'wpachievements_ranks', array( 'WPAchievements_Admin_Ranks', 'admin_page' ) );
  }

  add_submenu_page( 'edit.php?post_type=wpachievements', __("Reports", 'wpachievements'), __("Reports", 'wpachievements'), 'manage_options', 'wpachievements_reports', 'wpachievements_reports_page');

  add_submenu_page( 'edit.php?post_type=wpachievements', __('WPAchievements - Settings', 'wpachievements'), __('Settings', 'wpachievements'), 'manage_options', 'wpachievements_settings', 'wpachievements_settings_admin');

  add_submenu_page( 'edit.php?post_type=wpachievements', __('FAQ', 'wpachievements'), __('FAQ', 'wpachievements'), 'manage_options', 'wpachievements_faq', 'wpachievements_faq');
}

/**
 * Display the settings page
 *
 */
function wpachievements_settings_admin() {
  //require_once( 'class-wpachievements-admin-settings.php' );
  do_action('wpachievements_wpach_of_template' );
  WPAchievements_Admin_Settings::output();
}

function wpachievements_reports_page() {
  WPAchievements_Admin_Stats::output();
}

/**
 * Display a customized WP Editor
 *
 * @static
 * @param   WP_Post $post
 * @return  void
 */
function wpachievements_descrition_editor( $post ) {
  wp_nonce_field( 'wpachievements_achievement_save', 'wpachievements_achievement_nonce' );
  wp_editor( $post->post_content, "achievement_desc_editor", array(
    'media_buttons' => false,
    'textarea_rows' => 5,
    'quicktags' => false,
    'tinymce' => array(
      'theme_advanced_buttons1' => 'bold,italic,underline',
      'theme_advanced_buttons2' => '',
      'theme_advanced_buttons3' => '',
      'theme_advanced_buttons4' => ''
    )
  ));
}

function wpachievements_image_box( $post, $args = array() ){

  $defaults = array(
    'id'    => 'achievement',
    'title' => __("Achievement", "wpachievements" )
  );

  $args = wp_parse_args( $args['args'], $defaults );

  $cur_image = get_post_meta( $post->ID, '_'.$args['id'].'_image', true );

  if( $cur_image ){
    echo '<div id="image_preview_holder"><img src="'.$cur_image.'" alt="Achievement Logo" /><br/><a href="#" id="achievement_image_remove">Remove</a></div>';
  } else{
    echo '<div id="image_preview_holder"></div>';
  }

  echo '<span id="no-image-links"><a href="#" id="achievement_image_pick" class="button button-secondary">Select Image</a> <input id="upload_image" type="text" name="upload_image" value="'.$cur_image.'" /><input class="button button-primary" id="upload_image_button" type="button" value="'.__('Upload Image', 'wpachievements').'" /></span>';
  echo '<div id="default-image-selection" style="display:none;">';
  $path = WPACHIEVEMENTS_URL . '/img/icons/';
  $handle = opendir( WPACHIEVEMENTS_PATH.'/img/icons/' );
  $count=0;
  while($file = readdir($handle)){
    if($file !== '.' && $file !== '..'){
      $count++;
      echo '<span><input type="radio" name="achievement_badge" value="'.$path.$file.'" /><img src="'.$path.$file.'" alt="'.__('Achievement Image', 'wpachievements').' '.$count.'" class="radio_btn" /></span>';
    }
  }
  do_action('wpachievements_add_image_icons', $count );

  echo '<div class="clear"></div></div>';
}

/**
 *********************************************************
 *   W P A C H I E V E M E N T S   U S E R   A D M I N   *
 *********************************************************
 */
 //*************** Add Columns to User List ***************\\
 if( is_multisite() ){
   add_filter('wpmu_users_columns', 'wpachievements_add_custom_user_columns');
 } else{
   add_filter('manage_users_columns', 'wpachievements_add_custom_user_columns');
 }
 add_action('manage_users_custom_column',  'wpachievements_show_custom_user_columns', 10, 3);

function wpachievements_add_custom_user_columns($columns){

  if ( 'yes' != strtolower( wpachievements_get_site_option( 'wpachievements_hide_userpoint_profile_column' ) ) ) {
    $columns = array_merge( $columns, array( 'user_points' => 'Points' ) );
  }
  if ( 'yes' != strtolower( wpachievements_get_site_option( 'wpachievements_hide_userachievments_profile_column' ) ) ) {
    $columns = array_merge( $columns, array( 'user_achievements' => 'Achievements' ) );
  }

  return $columns;
}

function wpachievements_show_custom_user_columns($value, $column_name, $user_id){

   do_action('wpachievements_user_admin_load', $user_id);

   if( 'user_points' == $column_name ){
     return WPAchievements_User::get_points( $user_id );
   }
   if( 'user_achievements' == $column_name ){
     $userachievement = get_user_meta($user_id, 'achievements_gained', true );
     $achievements_list = 'None';
     if(!empty($userachievement) && $userachievement != ''){
       $achievements_list = '';
       $iii=0;
       foreach($userachievement as $achievement){
         $achievements_list .= get_the_title($achievement);
         if( !empty($achievements_list) ){
           $iii++;
         }
         if(end($userachievement) !== $achievement){
           $achievements_list .= ', ';
         }
       }
       if( $iii < 1 ){
         $achievements_list = 'None';
       }
     }

     return $achievements_list;
   }
   return $value;
}

 //*************** Add Fields to User Profile ***************\\
 if( is_multisite() ){
   if( is_network_admin() ){
     add_action( 'show_user_profile', 'wpachievements_show_extra_profile_fields' );
     add_action( 'edit_user_profile', 'wpachievements_show_extra_profile_fields' );
   }
 } else{
   add_action( 'show_user_profile', 'wpachievements_show_extra_profile_fields' );
   add_action( 'edit_user_profile', 'wpachievements_show_extra_profile_fields' );
 }

function wpachievements_show_extra_profile_fields( $user ){

  do_action('wpachievements_user_profile_load', $user->ID);

  if( ! is_super_admin() ) {
    return;
  }
  $userpoints_html = '';
  $userpoints = WPAchievements_User::get_points( $user->ID );

  $userpoints_html .= '<table class="form-table">';
  $userpoints_html .= '<tr>';
  $userpoints_html .= '<th><label for="wpa_points">'. __('Points','wpachievements') .'</label></th>';
  $userpoints_html .= '<td>';
  $userpoints_html .= '<input type="text" name="wpa_points" id="wpa_points" value="'. $userpoints .'" class="regular-text" /><br />';
  $userpoints_html .= '</td>';
  $userpoints_html .= '</tr>';
  $userpoints_html .= '</table>';

  $userpoints_html = apply_filters( 'wpachievements_show_userpoints_profile_fields', $userpoints_html );

  ?>
  <br/>
  <h3><?php echo __('WPAchievements Management','wpachievements'); ?></h3>
  <?php

  echo $userpoints_html;

  $achievements = WPAchievements_Query::get_achievements();
  if ( $achievements ) {
    $userachievement = get_user_meta( $user->ID, 'achievements_gained', true );
    ?>
    <table class="form-table">
      <tr>
        <th><label><?php echo __('Achievements','wpachievements'); ?></label></th>
        <td>
        <?php
        foreach( $achievements as $achievement ){
          $ach_ID = $achievement->ID;
          $ach_title = $achievement->post_title;
          $ach_desc = $achievement->post_content;
          $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
          $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
          if( $ach_woopoints > 0 ){
            $ach_points = sprintf( __('%d Points and %d WooPoints', 'wpachievements'), $ach_points, $ach_woopoints );
          } else{
            $ach_points = sprintf( __('%d Points', 'wpachievements'), $ach_points );
          }
          if($userachievement){
            if(in_array($ach_ID,$userachievement)){
              echo '<label><input type="checkbox" checked="checked" name="achi[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
            } else{
              echo '<label><input type="checkbox" name="achi[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
            }
          } else{
            echo '<label><input type="checkbox" name="achi[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
          }
        }
        ?>
        </td>
      </tr>
    </table>
    <br/>
    <?php
  }

  $quests = WPAchievements_Query::get_quests();
  if ( $quests ) {
    $userquest = get_user_meta( $user->ID, 'quests_gained', true );
    ?>
    <table class="form-table">
      <tr>
        <th><label><?php echo __('Quests','wpachievements'); ?></label></th>
        <td>
        <?php
        foreach( $quests as $quest ) {
          $ach_ID = $quest->ID;
          $ach_title = $quest->post_title;
          $ach_desc = $quest->post_content;
          $ach_points = get_post_meta( $ach_ID, '_quest_points', true );
          $ach_woopoints = get_post_meta( $ach_ID, '_quest_woo_points', true );
          if( $ach_woopoints > 0 ){
            $ach_points = sprintf( __('%d Points and %d WooPoints', 'wpachievements'), $ach_points, $ach_woopoints );
          } else{
            $ach_points = sprintf( __('%d Points', 'wpachievements'), $ach_points );
          }
          if($userquest){
            if(in_array($ach_ID,$userquest)){
              echo '<label><input type="checkbox" checked="checked" name="quest[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
            } else{
              echo '<label><input type="checkbox" name="quest[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
            }
          } else{
            echo '<label><input type="checkbox" name="quest[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
          }
        }
        ?>
        </td>
      </tr>
    </table>
    <br/>
    <?php
  }
}

if( is_multisite() ){
   if( is_network_admin() ){
     add_action( 'personal_options_update', 'wpachievements_save_profile_achievements' );
     add_action( 'edit_user_profile_update', 'wpachievements_save_profile_achievements' );
   }
} else{
   add_action( 'personal_options_update', 'wpachievements_save_profile_achievements' );
   add_action( 'edit_user_profile_update', 'wpachievements_save_profile_achievements' );
}

function wpachievements_save_profile_achievements( $user_id ){
  global $wpdb;

  $is_save_points = true;

  if( ! is_super_admin() ) {
    return;
  }

  $new_points = intval( filter_input( INPUT_POST, 'wpa_points', FILTER_SANITIZE_NUMBER_INT ) );

  $is_save_points = apply_filters( 'wpachievements_is_save_points', $is_save_points );

  if( $is_save_points ){
    $current_points = WPAchievements_User::get_points( $user_id );
    if( $new_points != $current_points ){
      update_user_meta( $user_id, 'achievements_points', $new_points );
    }
  }

  $newachievements = ( isset($_POST['achi']) ) ? $_POST['achi'] : '';
  $userachievement = get_user_meta( $user_id, 'achievements_gained', true );

  if( !empty($newachievements) && $newachievements != '' ){
    if( !empty($userachievement) && $userachievement != '' ){
      if( is_array($newachievements) ){
      $addachievements = array_diff($newachievements, $userachievement);
      $removeachievements = array_diff($userachievement, $newachievements);
      } else{
      if( empty($newachievements) || $newachievements == '' ){
        $removeachievements = $newachievements;
      } else{
        if( !array_key_exists($newachievements, $userachievement) ){
          $addachievements = $userachievement;
        }
      }
      }
    } else{
      $addachievements = $newachievements;
      $removeachievements = '';
    }
  } else{
    $addachievements = '';
    $removeachievements = $userachievement;
  }

  if( !empty($addachievements) && $addachievements != '' ){
    $args = array(
      'post__in' => $addachievements,
    );
    $achievements = WPAchievements_Query::get_achievements( $args );
    if( $achievements ){
      foreach( $achievements as $achievement ) {
        $ach_ID = $achievement->ID;
        $ach_title = $achievement->post_title;
        $ach_desc = $achievement->post_content;
        $ach_data = $ach_title.': '.$ach_desc;
        $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
        $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
        $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );
        $type = 'wpachievements_achievement_'.get_post_meta( $ach_ID, '_achievement_type', true );

        WPAchievements_User::handle_points( array(
          'activity'          => 'wpachievements_achievement',
          'user_id'           => $user_id,
          'points'            => $ach_points,
          'reference'         => 'new_achievement',
          'log_entry'         => 'for Achievement: '.$ach_title,
        ) );

        $achievement_data = array(
          'id' => $achievement->ID,
          'title' => $achievement->post_title,
          'description' => $achievement->post_content,
          'points' => $ach_points,
          'rank' => get_post_meta( $achievement->ID, '_achievement_rank', true ),
          'trigger' => $type,
          'occurences' => get_post_meta( $achievement->ID, '_achievement_occurrences', true ),
          'img' => $ach_img,
        );

        do_action( 'wpachievements_admin_add_achievement', $user_id, $ach_ID, $achievement_data );

        $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user_id, '$type', '$ach_data', '%d', '')", $ach_points) );

          $ach_meta = get_user_meta( $user_id, 'wpachievements_got_new_ach', true );
          if( $ach_meta ){
            if( !in_array_r( $ach_title, $ach_meta ) && !in_array_r( $ach_desc, $ach_meta ) && !in_array_r( $ach_img, $ach_meta )  ){
              $ach_meta = array ( array( "title" => $ach_title, "text" => $ach_desc, "image" => $ach_img) );
              update_user_meta( $user_id, 'wpachievements_got_new_ach', $ach_meta );
            }
          } else{
            $ach_meta = array ( array( "title" => $ach_title, "text" => $ach_desc, "image" => $ach_img) );
            update_user_meta( $user_id, 'wpachievements_got_new_ach', $ach_meta );
          }

        update_post_meta( $ach_ID, '_user_gained_'.$user_id, $user_id );
      }
    }
  }
  if( !empty($removeachievements) && $removeachievements != '' ){
    $args = array(
      'post__in' => $removeachievements,
    );
    $achievements = WPAchievements_Query::get_achievements( $args );
    if( $achievements ){
      foreach( $achievements as $achievement ){
        $ach_ID = $achievement->ID;
        $ach_title = $achievement->post_title;
        $ach_desc = $achievement->post_content;
        $ach_data = $ach_title.': '.$ach_desc;
        $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
        $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
        $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );

        WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_removed',
                  'user_id'           => $user_id,
                  'post_id'           => $ach_ID,
                  'points'            => -$ach_points,
                  'reference'         => 'wpachievements_removed',
                  'log_entry'         => 'for Achievement Removed: '.$ach_title,
                ) );

        do_action( 'wpachievements_remove_achievement', $user_id, $ach_ID );
        do_action( 'wpachievements_admin_remove_achievement', $user_id, 'wpachievements_removed', $ach_points );

        $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user_id, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_points) );

        delete_post_meta( $ach_ID, '_user_gained_'.$user_id );

        $ach_meta = get_user_meta( $user_id, 'wpachievements_got_new_ach', true );
        if( in_array_r( $ach_title, $ach_meta ) && in_array_r( $ach_desc, $ach_meta ) && in_array_r( $ach_img, $ach_meta ) ){
          foreach( $ach_meta as $key => $value ){
            if( $value["title"] == $ach_title && $value["text"] == $ach_desc && $value["image"] == $ach_img ){ unset($ach_meta[$key]); }
          }
          update_user_meta( $user_id, 'wpachievements_got_new_ach', $ach_meta );
        }
      }
    }
  }

  $size = empty($newachievements) ? 0 : sizeof($newachievements);

  update_user_meta( $user_id, 'achievements_gained', $newachievements );
  update_user_meta( $user_id, 'achievements_count', $size);

  $newquests = isset($_POST['quest']) ? $_POST['quest'] : '';
  $userquest = get_user_meta( $user_id, 'quests_gained', true );

  if( !empty($newquests) && $newquests != '' ){
    if( !empty($userquest) && $userquest != '' ){
      if( is_array($newquests) ){
      $addquests = array_diff($newquests, $userquest);
      $removequests = array_diff($userquest, $newquests);
      } else{
      if( empty($newquests) || $newquests == '' ){
        $removequests = $newquests;
      } else{
        if( !array_key_exists($newquests, $userquest) ){
          $addquests = $userquest;
        }
      }
      }
    } else{
      $addquests = $newquests;
      $removequests = '';
    }
  } else{
    $addquests = '';
    $removequests = $userquest;
  }

  if( !empty($addquests) && $addquests != '' ){
    $args = array(
      'post__in' => $addquests,
    );
    $quests = WPAchievements_Query::get_quests( $args );
    if( $quests ){
      foreach( $quests as $quest ){
        $quest_ID = $quest->ID;
        $quest_title = $quest->post_title;
        $quest_desc = $quest->post_content;
        $quest_data = $quest_title.': '.$quest_desc;
        $quest_points = get_post_meta( $quest_ID, '_quest_points', true );
        $quest_woopoints = get_post_meta( $quest_ID, '_quest_woo_points', true );
        $quest_img = get_post_meta( $quest_ID, '_quest_image', true );
        $type = 'wpachievements_quest';

        WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_quest',
                  'user_id'           => $user_id,
                  'post_id'           => $quest_ID,
                  'points'            => $quest_points,
                  'reference'         => 'new_quest',
                  'log_entry'         => 'for Quest: '.$quest_title,
                ) );

        do_action( 'wpachievements_admin_add_quest', $user_id, $type, $quest_points );

        $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user_id, '$type', '$quest_data', '%d', '')", $quest_points) );

        $quest_meta = get_user_meta( $user_id, 'wpachievements_got_new_quest', true );
        if( !in_array_r( $quest_title, $quest_meta ) && !in_array_r( $quest_desc, $quest_meta ) && !in_array_r( $quest_img, $quest_meta )  ){
          $quest_meta = array( array( "title" => $quest_title, "text" => $quest_desc, "image" => $quest_img) );
          update_user_meta( $user_id, 'wpachievements_got_new_quest', $quest_meta );
          update_post_meta( $quest_ID, '_user_gained_'.$user_id, $user_id );
        }
      }
    }
  }
  if( !empty($removequests) && $removequests != '' ){
    $args = array(
      'post__in' => $removequests,
    );
    $quests = WPAchievements_Query::get_quests( $args );
    if( $quests ){
      foreach( $quests as $quest ){
        $quest_ID = $quest->ID;
        $quest_title = $quest->post_title;
        $quest_desc = $quest->post_content;
        $quest_data = $quest_title.': '.$quest_desc;
        $quest_points = get_post_meta( $quest_ID, '_quest_points', true );
        $quest_woopoints = get_post_meta( $quest_ID, '_quest_woo_points', true );
        $quest_img = get_post_meta( $quest_ID, '_quest_image', true );

        WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_quest_removed',
                  'user_id'           => $user_id,
                  'post_id'           => $quest_ID,
                  'points'            => -$quest_points,
                  'reference'         => 'wpachievements_quest_removed',
                  'log_entry'         => 'for Quest Removed: '.$quest_title,
                ) );

        do_action( 'wpachievements_admin_remove_quest', $user_id, 'wpachievements_quest_removed', $quest_points );

        $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user_id, 'wpachievements_quest_removed', '$quest_data', '-%d', '')", $quest_points) );

        delete_post_meta( $quest_ID, '_user_gained_'.$user_id );

        $quest_meta = get_user_meta( $user_id, 'wpachievements_got_new_quest', true );
        if( in_array_r( $quest_title, $quest_meta ) && in_array_r( $quest_desc, $quest_meta ) && in_array_r( $quest_img, $quest_meta )  ){
          foreach( $quest_meta as $key => $value ){
            if( $value["title"] == $quest_title && $value["text"] == $quest_desc && $value["image"] == $quest_img ){ unset($quest_meta[$key]); }
          }
        }
        update_user_meta( $user_id, 'wpachievements_got_new_quest', $quest_meta );
      }
    }
  }
  if( empty($newquests) || $newquests == '' ){
  $size = 0;
  } else{
  $size = sizeof($newquests);
  }
  update_user_meta( $user_id, 'quests_gained', $newquests );
  update_user_meta( $user_id, 'quests_count', $size);
}

/**
*************************************************************************
*   W P A C H I E V E M E N T S   L A T E S T   I N F O R M A T I O N   *
*************************************************************************
*/

/**
 * Display a rating request notice
 *
 * @version 8.0.0
 * @since   8.0.0
 * @return  void
 */
function wpachievements_rating_notice() {

  if ( get_option( 'wpachievements_rating_div' ) == "no" ) {
    $install_date = get_option( 'wpachievements_install_date' );
    $display_date = date('Y-m-d h:i:s');
    $datetime1 = new DateTime($install_date);
    $datetime2 = new DateTime($display_date);
    $diff_intrval = round(($datetime2->format('U') - $datetime1->format('U')) / (60*60*24));

    if ( $diff_intrval >= 7 ) {
      echo '<div class="updated notice wpachievements_fivestar">
        <p>Awesome, you\'ve been using <strong>WPAchievements</strong> for a while. May we ask you to give it a <strong>5-star</strong> rating on WordPress.org?
          <br /><strong>Your WPAchievements Team</strong>
          <ul>
            <li><a href="https://wordpress.org/support/plugin/wpachievements-free/reviews/#new-post" class="thankyou" target="_new" title="Ok, you deserved it" style="font-weight:bold;">Ok, you deserved it</a></li>
              <li><a href="javascript:void(0);" class="mapHideRating" title="I already did" style="font-weight:bold;">I already did</a></li>
              <li><a href="javascript:void(0);" class="mapHideRating" title="No, not good enough" style="font-weight:bold;">No, not good enough</a></li>
          </ul>
      </div>
      <script>
      jQuery( document ).ready(function( $ ) {
      jQuery(\'.mapHideRating\').click(function(){
          var data={\'action\':\'wpa_hide_rating\'}
               jQuery.ajax({
          url: "'.admin_url( 'admin-ajax.php' ).'",
          type: "post",
          data: data,
          dataType: "json",
          async: !0,
          success: function(e) {
              if (e=="success") {
                 jQuery(\'.wpachievements_fivestar\').slideUp(\'slow\');
              }
          }
           });
          })
      });
      </script>
      ';
    }
  }
}

/**
 * Hide rating
 *
 * @version 8.0.0
 * @since   8.0.0
 * @return  void
 */
function wpachievements_hide_rating_div() {
  update_option('wpachievements_rating_div','yes');
  echo json_encode(array("success"));
  wp_die();
}
add_action('wp_ajax_wpa_hide_rating','wpachievements_hide_rating_div');


if ( is_multisite() ) {
  add_action( 'network_admin_notices', 'wpachievements_rating_notice' );
  global $blog_id;
  if ( $blog_id == 1 ) {
    add_action( 'admin_notices', 'wpachievements_rating_notice' );
  }
}
else {
  add_action( 'admin_notices', 'wpachievements_rating_notice' );
}

/**
 * Display the FAQ page
 *
 */
function wpachievements_faq() {
  include_once( 'views/html-admin-faq.php' );
}

/**
 *********************************************************************
 *   W P A C H I E V E M E N T S   U P D A T E   M E N U   T A B S   *
 *********************************************************************
 */
//*************** Update Admin Menu Tabs ***************\\
function update_wpachievements_points_menu_admin(){
echo "<script>
jQuery(document).ready(function(){
  jQuery('#wp-admin-bar-custom_ranks_menu').load('".get_bloginfo('url')." #wp-admin-bar-custom_ranks_menu > *');
});
  </script>";
}

/**
 *********************************************************************
 *   W P A C H I E V E M E N T S   A D D I T I O N A L   S T U F F   *
 *********************************************************************
 */
 function wpachievements_update_notice() {
  $update_response = get_option('external_updates-wpachievements');
  if( isset($update_response->update->license) ){
    if( $update_response->update->license->status == 'invalid' ){
      echo '&nbsp;<font color="#FF0000">'.$update_response->update->license->error.'</font>';
    }
  }
 }
 add_action( 'in_plugin_update_message-wpachievements/wpachievements.php', 'wpachievements_update_notice' );

function achievement_custom_admin_events() {
  global $typenow;

  echo '<optgroup label="'.__('Custom Achievement Events', 'wpachievements').'">
     <option value="custom_achievement">'. __('Manually Awarded', 'wpachievements') .'</option>';
  echo '<option value="activity_code_achievement">'. __('Activity Code Awarded', 'wpachievements') .'</option>';

  if ( ! WPAchievements()->is_request('ajax') && 'wpquests' != $typenow ) {
    echo '<option value="custom_trigger">'. __('Custom Trigger', 'wpachievements') .'</option>';
  }
  else {
    $achievements = WPAchievements_Query::get_achievements();

    if (  $achievements ) {
      echo '<option value="wpachievements_achievement">'. __('The user gains an achievement', 'wpachievements') .'</option>';
    }
  }

  echo '</optgroup>';
 }
 add_filter('wpachievements_admin_events', 'achievement_custom_admin_events', 10);

 /**
 * Retrieve a trigger description
 *
 * @param string $trigger
 * @return string
 */
function wpachievements_get_trigger_description( $trigger ) {
  return apply_filters( 'wpachievements_trigger_description', $trigger );
}

if( ! function_exists( 'in_array_r' ) ) {
  /**
   * Recursively look for Needle in Haystack
   *
   * @param   string  $needle
   * @param   array  $haystack
   * @param   boolean $strict
   * @return  boolean
   */
  function in_array_r($needle, $haystack, $strict = true) {
    if ( is_array( $haystack ) ) {
      foreach ( $haystack as $item ) {
        if ( ( $strict ? $item === $needle : $item == $needle ) || ( is_array( $item ) && in_array_r( $needle, $item, $strict ) ) ) {
          return true;
        }
      }
    }

    return false;
   }
}

/**
 * Ajax callback for user autocomplete
 *
 * @return void
 */
function wpachievements_autocomplete_user() {

  $return         = array();
  $exclude_users  = array();
  $post_id        = filter_input( INPUT_GET, 'post_id' );

  if ( $post_id ) {
    $exclude_users = WPAchievements()->achievement()->get_users( $post_id );
  }

  $users = get_users( array(
    'blog_id' => false,
    'search'  => '*' . $_REQUEST['term'] . '*',
    'exclude' => $exclude_users,
    'search_columns' => array( 'user_login', 'user_nicename', 'user_email' ),
  ) );

  foreach ( $users as $user ) {
    $return[] = array(
      'label' => sprintf( _x( '%1$s (%2$s)', 'user autocomplete result' ), $user->user_login, $user->user_email ),
      'value' => $user->user_login,
      'id'    => $user->ID,
    );
  }

  wp_die( wp_json_encode( $return ) );
}
add_action( 'wp_ajax_wpa_autocomplete_user', 'wpachievements_autocomplete_user' );