<?php
/**
* Achievements Leaderboard
*/

class WP_Widget_WPAchievements_Widget extends WP_Widget {

  function __construct() {
    $widget_ops   = array('description' => 'Shows a leaderboard of achievements gained by users.');
    parent::__construct('WPAchievements_Widget', 'WPAchievements Leaderboard', $widget_ops);
  }

  function widget($args, $instance) {
    global $wpdb;

    extract($args);

    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
    $type = empty( $instance['type'] ) ? 'achievements' : esc_attr( $instance['type'] );
    $limit = empty( $instance['limit'] ) ? 5 : intval( $instance['limit'] );

    if( is_multisite() ) {
      switch_to_blog(1);
    }

    $table = $wpdb->prefix.'usermeta';

    if ( is_multisite() ) {
      restore_current_blog();
    }

    $hide_admin = wpachievements_get_site_option('wpachievements_hide_admin');
    $admins = array();
    $admins[] = 0;

    if ( $hide_admin == 'yes' ) {
      $user_query = new WP_User_Query( array( 'role' => 'Administrator' ) );
      $users = $user_query->get_results();
      $admins = array();
      foreach( $users as $user ) {
        $admins[] = $user->ID;
      }
    }

    $meta_key = 'achievements_count';

    if ( strtolower($type) == 'points' ) {
      $meta_key = 'achievements_points';
      $meta_key = apply_filters( 'wpachievements_meta_key', $meta_key );
    }

    $user_achievements = $wpdb->get_results( $wpdb->prepare("SELECT user_id,meta_value FROM ".$table." WHERE meta_key=%s AND user_id NOT IN (".implode(',', $admins).") ORDER BY meta_value * 1 DESC LIMIT %d", $meta_key, $limit ) );

    $trophies = array('','gold','silver','bronze');
    $count=0;
    if ( !empty($user_achievements) && $user_achievements!='') {
      echo $before_widget;
      if($title) {
        echo $before_title . $title . $after_title;
      }
      foreach( $user_achievements as $user_info ):
        if ( ! $user_info->meta_value ) {
          continue;
        }

        $user_inf = get_userdata($user_info->user_id);
        $count++;

        $trophy = ( $count < 4 ) ? $trophies[$count] : 'default';

        echo '<center>';
        echo '<div class="myus_user wpach_leaderboard">'. get_avatar($user_info->user_id, '50') .'<div class="myus_title">';
        if( isset($user_inf->nickname) ){
          $showName = $user_inf->nickname;
        } elseif( isset($user_inf->display_name) ){
          $showName = $user_inf->display_name;
        } else{
          $showName = $user_inf->user_login;
        }

        $profile_url = apply_filters( 'wpachievements_user_profile_url', false, $user_info->user_id );

        if ( $profile_url ) {
          echo '<a href="' . $profile_url . '" title="' . sprintf( __( "View %s Profile", 'wpachievements'), $showName ) . '">' . $showName . '</a>';
        }
        else {
          echo $showName;
        }

        $count_title = __('Achievements', 'wpachievements');

        if( strtolower($type) == 'points' ){
          $count_title = __('Total Points', 'wpachievements');
        }

        echo '</div><div class="myus_count">'.$count_title.': '.$user_info->meta_value.'</div>';
        echo '<div class="myus_icon trophy_'.$trophy.'">';

        if($count>3){echo '<div class="myus_num">'.$count.'<span>th</span></div>';}

        echo '</div><div class="user_finish"></div></div></center>';
      endforeach;
    }
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['limit'] = intval($new_instance['limit']);
    $instance['type'] = strip_tags($new_instance['type']);
    return $instance;
  }

  function form($instance) {

    $instance = wp_parse_args((array) $instance, array('title' => 'Achievements Leaderboard', 'type' => 'achievements', 'limit' => 5));
    $title = esc_attr($instance['title']);
    $limit = intval($instance['limit']);
    $type = esc_attr($instance['type']);
    echo '<p>
    <label for="'.$this->get_field_id('title').'">
    Title:
    <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />
    </label>
    </p>
    <p>
    <label for="'.$this->get_field_id('type').'">
    Type:
    <select class="widefat" id="'.$this->get_field_id('type').'" name="'.$this->get_field_name('type').'" type="text" value="'.$type.'">';
    if( strtolower($type) == 'points' ){
      echo '<option value="achievements">Achievements</option>
      <option value="points" selected>Points</option>';
    } else{
      echo '<option value="achievements" selected>Achievements</option>
      <option value="points">Points</option>';
    }
    echo '
    </select>
    </label>
    </p>
    <p>
    <label for="'.$this->get_field_id('limit').'">
    Limit:
    <input class="widefat" id="'.$this->get_field_id('limit').'" name="'.$this->get_field_name('limit').'" type="text" value="'.$limit.'" />
    </label>
    </p>';
  }
}

register_widget( 'WP_Widget_WPAchievements_Widget' );