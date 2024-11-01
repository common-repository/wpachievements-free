<?php
/**
 * My Rank
 */
if ( !class_exists('WP_Widget_WPAchievements_Quest_Progress') ) {
  class WP_Widget_WPAchievements_Quest_Progress extends WP_Widget {

    function __construct() {
      $widget_ops   = array('description' => __("Shows user's progress on a specific quest.", 'wpachievements' ) );
      parent::__construct('WPAchievements_Quest_Progress_Widget', 'WPAchievements Quest Progress', $widget_ops);
    }

    function widget($args, $instance) {
      extract($args);

      $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
      $quest_id = empty( $instance['quest_id'] ) ? false : intval( $instance['quest_id'] );
      $limit_rank = empty( $instance['limit_rank'] ) ? false : (bool)( $instance['limit_rank'] );

      echo $before_widget;
      if ( $title ) {
        echo $before_title . $title . $after_title;
      }

      if ( ! $quest_id ) {
        ?>
        <div class="wpa-alert wpa-alert-danger">
          <?php _e("Please add a quest ID to your widget in order to display the progress!", "wpachievements" ); ?>
        </div>
        <?php
      }
      else {
        $limit_rank = ($limit_rank) ? 'true' : 'false';
        echo do_shortcode( '[wpa_quest_steps show_title="false" quest_id="'.$quest_id.'" class="vertical" limit_rank="'.$limit_rank.'"]' );
      }

      echo '<div class="clear"></div>';
      echo $after_widget;
    }

    function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['title'] = strip_tags( $new_instance['title'] );
      $instance['quest_id'] = intval( $new_instance['quest_id'] );
      $instance['limit_rank'] = (bool)($new_instance['limit_rank']);
      return $instance;
    }

    function form($instance) {

      $instance = wp_parse_args( (array) $instance, array('title' => 'Quest Progress', 'quest_id' => '', 'limit_rank' => false ) );
      $title = esc_attr( $instance['title'] );
      $quest_id = intval( $instance['quest_id'] );
      $limit_rank = (bool) ( $instance['limit_rank'] );

      echo '<p>
      <label for="'.$this->get_field_id('title').'">
      Title:
      <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />
      </label>
      </p>
      <p>
      <label for="'.$this->get_field_id('quest_id').'">'.__('Select Quest', 'wpachievements').':</label><br/>
        <select name="'.$this->get_field_name('quest_id').'" id="'.$this->get_field_id('quest_id').'">';
        echo wpa_quest_list($quest_id); 
      echo '</select>
      </p>';

      echo '<p>
      <label for="'.$this->get_field_name('limit_rank').'">
      <input type="checkbox" name="'.$this->get_field_name('limit_rank').'" value="1" '.checked( $limit_rank, 1, false ).'" />'.__('Limit visibility by ranks', 'wpachievements').'
      </label></p>';
    }
  }
}

register_widget( 'WP_Widget_WPAchievements_Quest_Progress' );