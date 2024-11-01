<?php
/**
 * My Rank
 */
if ( !class_exists('WP_Widget_WPAchievements_Ranks_Widget') ) {
  class WP_Widget_WPAchievements_Ranks_Widget extends WP_Widget {

    function __construct() {
      $widget_ops   = array('description' => 'Shows the current rank of the user.');
      parent::__construct('WPAchievements_Ranks_Widget', 'WPAchievements My Rank', $widget_ops);
    }

    function widget($args, $instance) {
      extract($args);

      $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
      $show_img = empty( $instance['show_img'] ) ? 'hide' : intval( $instance['show_img'] );

      echo $before_widget;
      if($title) {
        echo $before_title . $title . $after_title;
      }
      echo do_shortcode('[wpa_myranks rank_image="'.$show_img.'"]');
      echo '<div class="clear"></div>';
      echo $after_widget;
    }

    function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['show_img'] = strip_tags($new_instance['show_img']);
      return $instance;
    }

    function form($instance) {

      $instance = wp_parse_args((array) $instance, array('title' => 'My Rank', 'show_img' => 'hide'));
      $title = esc_attr($instance['title']);
      $show_img = esc_attr($instance['show_img']);

      echo '<p>
      <label for="'.$this->get_field_id('title').'">
      Title:
      <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />
      </label>
      </p>
      <p>
      <label for="'.$this->get_field_id('show_img').'">
      Show Rank Image:
      <select id="'.$this->get_field_id('show_img').'" name="'.$this->get_field_name('show_img').'" class="widefat" style="background:#fff;">';
      if( $show_img == 'show' ){
        echo '<option value="show" selected>Show</option><option value="hide">Hide</option>';
      } else{
        echo'<option value="hide" selected>Hide</option><option value="show">Show</option>';
      }
      echo'
      </select>
      </label>
      </p>';
    }
  }
}

register_widget( 'WP_Widget_WPAchievements_Ranks_Widget' );