<?php
/**
 * My Rank
 */
if ( !class_exists('WP_Widget_WPAchievements_Activity_Code_Widget') ) {
  class WP_Widget_WPAchievements_Activity_Code_Widget extends WP_Widget {

    function __construct() {
      $widget_ops   = array('description' => 'Unlock achievements with an activity code.');
      parent::__construct('WPAchievements_Activity_Code_Widget', 'WPAchievements Activity Code', $widget_ops);
    }

    function widget($args, $instance) {
      extract($args);

      $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
      $input_placeholder = empty( $instance['placeholder'] ) ? '' : esc_attr($instance['placeholder']);
      $submit_button_text = empty( $instance['submit_text'] ) ? __("Submit", "wpachievements") : esc_attr($instance['submit_text']);

      echo $before_widget;
      if($title) {
        echo $before_title . $title . $after_title;
      }

      echo do_shortcode('[wpa_activity_code input_placeholder="'.$input_placeholder.'" submit_button_text="'.$submit_button_text.'"]' );

      echo $after_widget;
    }

    function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['placeholder'] = strip_tags($new_instance['placeholder']);
      $instance['submit_text'] = strip_tags($new_instance['submit_text']);
      return $instance;
    }

    function form($instance) {

      $instance = wp_parse_args( (array) $instance, array(
        'title' => __('Enter a Code', 'wpachievements'),
        'placeholder' => __("Enter Activity Code", "wpachievements"),
        'submit_text' => __("Submit", "wpachievements")
        )
      );

      $title = esc_attr($instance['title']);
      $input_placeholder = esc_attr($instance['placeholder']);
      $submit_button_text = esc_attr($instance['submit_text']);

      echo '<p>
      <label for="'.$this->get_field_id('title').'">
      Title:
      <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />
      </label>
      </p>';

      echo '<p>
      <label for="'.$this->get_field_id('placeholder').'">
      Title:
      <input class="widefat" id="'.$this->get_field_id('placeholder').'" name="'.$this->get_field_name('placeholder').'" type="text" value="'.$input_placeholder.'" />
      </label>
      </p>';

      echo '<p>
      <label for="'.$this->get_field_id('submit_text').'">
      Title:
      <input class="widefat" id="'.$this->get_field_id('submit_text').'" name="'.$this->get_field_name('submit_text').'" type="text" value="'.$submit_button_text.'" />
      </label>
      </p>';
    }
  }
}

register_widget( 'WP_Widget_WPAchievements_Activity_Code_Widget' );