<?php
/**
 * My Quests
 */
if ( !class_exists('WP_Widget_WPAchievements_Quests_Widget') ) {
  class WP_Widget_WPAchievements_Quests_Widget extends WP_Widget {

    function __construct() {
      $widget_ops   = array('description' => 'Shows a list of quests gained by the user.');
      parent::__construct('WPAchievements_Quests_Widget', 'WPAchievements My Quests', $widget_ops);
    }

    function widget($args, $instance) {
      extract($args);

      $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
      $limit = empty( $instance['limit'] ) ? 12 : intval( $instance['limit'] );
      $image_holder_class = empty($instance['image_holder_class']) ? 'wpa_horizontal_list_align' : esc_attr($instance['image_holder_class']);
      $image_class =  empty($instance['image_class']) ? 'wpa_a_image' : esc_attr($instance['image_class']);
      $image_width =  empty($instance['image_width']) ? '30' : intval($instance['image_width']);

      echo $before_widget;
      if($title) {
        echo $before_title . $title . $after_title;
      }
      echo do_shortcode('[wpa_myquests image_holder_class="'.$image_holder_class.'" image_class="'.$image_class.'" image_width="'.$image_width.'" achievement_limit="'.$limit.'"]');
      echo $after_widget;
    }

    function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['limit'] = intval($new_instance['limit']);
      $instance['image_holder_class'] = esc_attr($new_instance['image_holder_class']);
      $instance['image_class'] = esc_attr($new_instance['image_class']);
      $instance['image_width'] = intval($new_instance['image_width']);
      return $instance;
    }

    function form($instance) {

      $instance = wp_parse_args((array) $instance, array('title' => 'My Quests', 'limit' => 12, 'image_holder_class' => 'wpa_horizontal_list_align', 'image_class' => 'wpa_a_image', 'image_width' => '30'));
      $title = esc_attr($instance['title']);
      $limit = intval($instance['limit']);
      $image_holder_class = esc_attr($instance['image_holder_class']);
      $image_class = esc_attr($instance['image_class']);
      $image_width = intval($instance['image_width']);
      ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">
          Title
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      
      <p>
        <label for="<?php echo $this->get_field_id('limit'); ?>">
          Limit: (Default: 12)
          <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" />
        </label>
      </p>
      
      <p>
        <label for="<?php echo $this->get_field_id('image_holder_class'); ?>">
          Image Holder Class: (Default: wpa_horizontal_list_align)
          <input class="widefat" id="<?php echo $this->get_field_id('image_holder_class'); ?>" name="<?php echo $this->get_field_name('image_holder_class'); ?>" type="text" value="<?php echo $image_holder_class; ?>" />
        </label>
      </p>
      
      <p>
        <label for="<?php echo $this->get_field_id('image_class'); ?>">
          Image Class: (Default: wpa_a_image)
          <input class="widefat" id="<?php echo $this->get_field_id('image_class'); ?>" name="<?php echo $this->get_field_name('image_class'); ?>" type="text" value="<?php echo $image_class; ?>" />
        </label>
      </p>
      
      <p>
        <label for="<?php echo $this->get_field_id('image_width'); ?>">
          Image Width: (Default: 30px)
          <input class="widefat" id="<?php echo $this->get_field_id('image_width'); ?>" name="<?php echo $this->get_field_name('image_width'); ?>" type="text" value="<?php echo $image_width; ?>" />
        </label>
      </p>
      <?php
    }
  }
}

register_widget('WP_Widget_WPAchievements_Quests_Widget');