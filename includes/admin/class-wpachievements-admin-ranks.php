<?php
/**
 * Handles creating and editing of Ranks
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPAchievements_Admin_Ranks' ) ) :

class WPAchievements_Admin_Ranks {

  public static function init() {
    add_action( 'wp_ajax_wpachievements_remove_rank_ajax', array( __CLASS__, 'remove_callback' ) );
    add_action( 'wp_ajax_wpachievements_update_rank_ajax', array( __CLASS__, 'update_callback' ) );
  }

  /**
   * Show ranks admin page
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function admin_page() {
    global $wpdb;

    $ranks = (array) wpachievements_get_site_option('wpachievements_ranks_data');

    if ( $ranks[0]=='') {
      $ranks[0] = __('Newbie', 'wpachievements');

      if ( is_multisite() ) {
        update_blog_option( 1,'wpachievements_ranks_data', $ranks );
      }
      else {
        update_option('wpachievements_ranks_data', $ranks);
      }
    }

    ksort($ranks);

    echo '<div class="wrap">
    <h1>'.__('Ranks', 'wpachievements').'</h1>
    '.__('Setup ranks for your users', 'wpachievements').'<br /><br />
    <div id="error_holder"></div>
    <form name="wpachievements_ranks_data_form" method="post" id="add_rank_form">
      <input type="hidden" name="wpachievements_ranks_data_form_submit" value="Y" />
      <h3>'.__('Create New Rank', 'wpachievements').'</h3>
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><label for="wpachievements_ranks_data_rank">'.__('Rank Name', 'wpachievements').':</label></th>
          <td valign="middle"><input type="text" id="wpachievements_ranks_data_rank" name="wpachievements_ranks_data_rank" value="'.get_option('wpachievements_ranks_data_rank').'" size="40" /></td>
        </tr>
        <tr valign="top">';

    if ( is_multisite() ) {
      if ( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ) {
        $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status'));
      }
      else {
       $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status'));
      }
    }
    else {
      $ranktype = strtolower(get_option('wpachievements_rank_type'));
    }

    if ( $ranktype != 'achievements' ) {
      echo '<th scope="row"><label for="wpachievements_ranks_data_points">'.__('Points to reach this rank', 'wpachievements').':</label></th>';
    }
    else {
     echo '<th scope="row"><label for="wpachievements_ranks_data_points">'.__('No. Achievements', 'wpachievements').':</label></th>';
    }

    echo '<td valign="middle"><input type="number" min="0" id="wpachievements_ranks_data_points" name="wpachievements_ranks_data_points" value="0" /></td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="upload_image">'.__('Rank Image', 'wpachievements').':</label></th>
          <td id="rank_image" valign="middle"><input id="upload_image" type="text" name="upload_image" value="" />';
    echo '<div id="default-image-selection" style="display:none;">';
    $path = WPACHIEVEMENTS_URL . '/assets/img/ranks/';
    $handle = opendir( WPACHIEVEMENTS_PATH.'/assets/img/ranks/' );
    $count=0;
    while($file = readdir($handle)){
      if($file !== '.' && $file !== '..'){
        $count++;
        echo '<span><input type="radio" name="achievement_badge" value="'.$path.$file.'" /><img src="'.$path.$file.'" alt="'.__('Rank Image', 'wpachievements').' '.$count.'" class="radio_btn" /></span>';
      }
    }
    do_action('wpachievements_add_rank_icons', $count );

    echo '<div class="clear"><center><a href="#" class="button button-secondary" id="rank_image_close">'.__('Close', 'wpachievements').'</a></center></br></div></div>';

    echo '<span id="no-image-links"><a href="#" id="rank_image_pick" class="button button-secondary">'.__('Select Image', 'wpachievements').'</a> <input class="button button-primary" id="upload_image_button" type="button" value="'.__('Upload Image', 'wpachievements').'" /></span>';

    echo '</tr>
      </table>
      <p class="submit">
        <input type="submit" name="Submit" id="rank_save" class="button button-primary" value="'.__('Add Rank', 'wpachievements').'" />
        <div class="clear"></div>
      </p>
    </form>
    <br /><br />
    <table id="wpachievements_table" class="widefat datatables rank_table">
      <thead>
        <tr>
          <th scope="col">'.__('Rank', 'wpachievements').'</th>
          <th scope="col" width="150" style="text-align:center;">'.__('Image', 'wpachievements').'</th>';

    if ( is_multisite() ) {
      if ( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ) {
        $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status'));
      }
      else {
        $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status'));
      }
    }
    else {
      $ranktype = strtolower(get_option('wpachievements_rank_type'));
    }

    if ( $ranktype != 'achievements' ) {
      echo '<th scope="col" width="150" style="text-align:center;">'.__('Points', 'wpachievements').'</th>';
    }
    else {
      echo '<th scope="col" width="150" style="text-align:center;">'.__('Achievements', 'wpachievements').'</th>';
    }

    echo '<th scope="col" width="150">'.__('Action', 'wpachievements').'</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th scope="col">'.__('Rank', 'wpachievements').'</th>
          <th scope="col" width="150" style="text-align:center;">'.__('Image', 'wpachievements').'</th>';

    if ( is_multisite() ) {
      if ( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ) {
        $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status'));
      }
      else {
        $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status'));
      }
    }
    else {
      $ranktype = strtolower(get_option('wpachievements_rank_type'));
    }

    if ( $ranktype != 'achievements' ) {
      echo '<th scope="col" style="text-align:center;">'.__('Points', 'wpachievements').'</th>';
    }
    else {
     echo '<th scope="col" style="text-align:center;">'.__('Achievements', 'wpachievements').'</th>';
    }

    echo '<th scope="col">'.__('Action', 'wpachievements').'</th>
        </tr>
      </tfoot>';

    $count=0;

    foreach( $ranks as $points=>$rank ) {
      if ( get_bloginfo('version') >= 3.8 ) {
        $count++;
        if ($count % 2 === 0) {$alt='';} else{$alt=' class="alt"';}
      }

      echo '<tr id="rank_'.$points.'"'.$alt.'>
          <td><strong><div id="rank_edit_'.$points.'">'; if (is_array($rank)) { echo $rank[0]; } else{ echo $rank; } echo '</div></strong></td>
          <td style="text-align:center;"><strong><div id="image_edit_'.$points.'">'; if (is_array($rank)) { echo '<img src="'.$rank[1].'" alt="Rank '.$rank[0].' Image" style="max-width:150px;max-height:30px;" />'; } else{ echo 'None'; } echo '</div></strong></td>
          <td style="text-align:center;"><div id="points_edit_'.$points.'">'.$points.'</div></td>
          <td>
            <a href="javascript:void(0);" id="wpachievements_ranks_action_edit_'.$points.'" class="rank_edit_link">'.__('Edit', 'wpachievements').'</a>
            <a href="javascript:void(0);" id="wpachievements_ranks_action_save_'.$points.'" class="rank_save_link" style="display:none;">'.__('Save', 'wpachievements').'</a>
            <form method="post" name="wpachievements_ranks_action_remove_'.$points.'" id="wpachievements_ranks_action_remove_'.$points.'" style="display:inline;">
              <input type="hidden" name="wpachievements_rank_remove" value="'.$points.'" />
               | <a href="javascript:void(0);" id="ranks_action_remove_'.$points.'" class="wpachievements_rank_remove">'.__('Remove', 'wpachievements').'</a>
              <a href="javascript:void(0);" id="rank_cancel_link_'.$points.'" class="rank_cancel_link" style="display:none;">'.__('Cancel', 'wpachievements').'</a>
            </form>
          </td>
        </tr>';
    }

    echo '</table>
    </div>';
  }

  /**
   * Remove rank ajax callback
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function remove_callback() {

    $remove_rank = intval( filter_input( INPUT_POST, 'wpachievements_rank_remove', FILTER_SANITIZE_NUMBER_INT ) );

    if ( ! $remove_rank ) {
      echo '<div class="error"><p><strong>'. __('A rank name is needed for users with 0 points!', 'wpachievements') .'<br /><br />'. __('Click the edit link to edit this rank.', 'wpachievements').'</strong></p></div>';
    }
    else {
      $ranks = wpachievements_get_site_option('wpachievements_ranks_data');
      
      unset( $ranks[ $remove_rank ] );

      wpachievements_update_site_option( 'wpachievements_ranks_data', $ranks );

      echo '<div class="updated"><p><strong>'. __('Rank removed', 'wpachievements') .'</strong></p></div>';
    }

    wp_die();
  }

  public static function update_callback() {

    $rank_name = trim( filter_input( INPUT_POST, 'wpachievements_ranks_data_rank', FILTER_SANITIZE_STRING ) );
    
    if ( ! $rank_name ) {
      wp_die();
    }

    $rank_points = intval( filter_input( INPUT_POST, 'wpachievements_ranks_data_points', FILTER_VALIDATE_INT, array( 
      'options' => array( 'min_range' => 0) ) ) );

    $ranks = wpachievements_get_site_option('wpachievements_ranks_data');

    if ( isset( $ranks[ $rank_points ] ) ) {
      $message = __('Rank Updated', 'wpachievements');
    }
    else {
      $message = __('Rank Added', 'wpachievements');
    }

    $edit_this = filter_input( INPUT_POST, 'editthis');

    if ( $edit_this && isset( $ranks[ intval( $edit_this ) ] ) ) {      
      unset( $ranks[ intval( $edit_this ) ] );
    }

    $rank_image = trim( filter_input( INPUT_POST, 'wpachievements_ranks_data_image', FILTER_SANITIZE_URL ) );

    $ranks[ $rank_points ] = ( $rank_image ) ? array( $rank_name, $rank_image ) : $rank_name;

    wpachievements_update_site_option( 'wpachievements_ranks_data', $ranks );

    echo '<div class="updated"><p><strong>'. $message .'</strong></p></div>';

    wp_die();
  }
}

endif;

WPAchievements_Admin_Ranks::init();