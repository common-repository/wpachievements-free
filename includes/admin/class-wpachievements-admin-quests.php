<?php
/**
 * Handles creating and editing of Quests
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPAchievements_Admin_Quests' ) ) :

class WPAchievements_Admin_Quests {

  public static function init() {
    add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ), 1 );
    add_action( 'save_post', array( __CLASS__, 'save_post' ) );
    add_action( 'before_delete_post', array( __CLASS__, 'delete_post' ), 1 );
    add_action( 'wp_ajax_wpachievements_quest_html', array( __CLASS__, 'get_quest_html' ) );
  }

  /**
   * Register required meta boxes
   *
   * @static
   * @access  public
   */
  public static function add_meta_boxes() {
    add_meta_box(
      'achievement_desc',
      '<strong>'. __( 'Quest Text', 'wpachievements' )  .'</strong> - <small>'. __('This text is displayed when a user get the quest.', 'wpachievements').'</small>',
      'wpachievements_descrition_editor', 'wpquests', 'normal', 'high'
    );

    add_meta_box(
      'achievement_details',
      '<strong>'. __( 'Quest Details', 'wpachievements' )  .'</strong> - <small>'. __('Setup the detials of the quest.', 'wpachievements').'</small>',
      array( __CLASS__, 'how_to_achieve' ), 'wpquests', 'normal', 'high'
    );
    add_meta_box(
      'achievement_image',
      '<strong>'. __( 'Quest Image', 'wpachievements' )  .'</strong>',
      'wpachievements_image_box', 'wpquests', 'side', 'high', array( 'title' => __("Quest"), 'id' => 'quest' )
    );

    add_meta_box(
      'achievement_gained',
      '<strong>'. __( 'Gained By', 'wpachievements' )  .'</strong>',
      array( __CLASS__, 'gained_by'), 'wpquests', 'side'
    );
  }

  /**
   * Defines the triggers to complete the quest
   *
   * @static
   * @access  public
   * @param   WP_POST $post
   * @return  void
   */
  public static function how_to_achieve( $post ) {
    wp_nonce_field( 'wpachievements_quest_save', 'wpachievements_quest_nonce' );
    $cur_details = (array) get_post_meta( $post->ID, '_quest_details', true );
    $cur_rank = get_post_meta( $post->ID, '_quest_rank', true );
    $cur_points = get_post_meta( $post->ID, '_quest_points', true );

    if ( empty($cur_points) ) {
      $cur_points=1;
    }

    if ( empty( $cur_detail['blog_limit'] ) ) {
      $cur_detail['blog_limit'] = 0;
    }

    $rankstatus = wpachievements_get_site_option('wpachievements_rank_status');

    if ( $rankstatus != 'Disable' ) {
      echo '<span class="pullleft first-select">
        <label for="wpachievements_achievements_data_rank">'.__('Limit to Rank', 'wpachievements').':</label><br/>
        <select name="wpachievements_achievements_data_rank" id="wpachievements_achievements_data_rank">';

      if ( $cur_rank ) {
        if ( $cur_rank == 'any' ) {
          $current = 'any';
          $cur_rank = 'Any Rank';
        }
        else {
          $current = $cur_rank;
        }

        echo '<optgroup label="'.__('Currently Selected', 'wpachievements').'">
          <option value="'.$current.'" selected>'.$cur_rank.'</option></optgroup>';
      }

      echo'<optgroup label="'.__('Available Ranks', 'wpachievements').'">
        <option value="any">'.__('Any Rank', 'wpachievements').'</option>';

      $ranks = (array)wpachievements_get_site_option('wpachievements_ranks_data');

      foreach ( $ranks as $points=>$rank ) {
        if ( is_array( $rank ) ) {
          echo '<option value="'.$rank[0].'">'.$rank[0].'</option>';
        }
        else {
         echo '<option value="'.$rank.'">'.$rank.'</option>';
        }
      }

      echo '</optgroup></select></span>';
    }

    echo '<label for="wpachievements_achievements_data_points">'.__('Points Awarded / Deducted', 'wpachievements').':</label>
        <input type="number" id="wpachievements_achievements_data_points" name="wpachievements_achievements_data_points" value="'.$cur_points.'" />';

    echo '<div class="clear"></div>';

    $count = count($cur_details);

    if ( $count < 2 ) {
      $disabled = ' disabled';
      $count = 2;
    }
    else {
      $disabled = '';
    }

    echo '<input type="hidden" name="quest_item_counter" id="quest_item_counter" value="'.$count.'" />';
    echo '<div id="quest_item_holder"><h3>' . __("Quest Steps", 'wpachievements') . '</h3>';

    $cur_details_keys = array_keys( $cur_details );

    for ( $iii = 0; $iii < $count; $iii++ ) {
      $data = array();

      if ( ! empty( $cur_details ) ) {
        if ( ! empty( $cur_details_keys[ $iii ] ) ) {
          $index = $cur_details_keys[ $iii ];
          $data = $cur_details[ $index ];
        }
      }

      echo self::get_quest_html( $iii, $data );
    }

    echo '</div><div class="event_details_holder">
      <div class="clear"></div><br/><div class="quest_sep"></div><br/>
      <div class="clear"></div><a href="#" class="button button-primary" id="quest_add">'.__('Add Another Trigger', 'wpachievements').'</a><div id="quest_spinner" class="spinner"></div>
      </div><div class="clear"></div><br/>';
  }

 /**
   * Show who has already gained this achievement
   *
   * @param WP_Post $post
   * @return void
   */
  public static function gained_by( $post ) {
    global $wpdb;

    if ( empty( $post->ID ) ) {
      return;
    }

    $users = $wpdb->get_results( "SELECT meta_value as user_id FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_user_gained_%' AND post_id = '{$post->ID}'" );

    if ( $users ) {
      $gained_by = array();

      foreach ( $users as $user ) {
        $user_data = get_userdata( $user->user_id );

        if ( $user_data ) {
          $gained_by[] = "<a href='".get_edit_user_link( $user->user_id )."' title='".__("Edit")."'>".$user_data->user_nicename."</a>";
        }
      }

      echo "<p>" . implode( ', ' , $gained_by ) . "</p>";
    }
    else {
      echo "<p>" . __("Nobody has solved this quest, yet!", "wpachievements" ) . "</p>";
    }
  }

  /**
   * Adds new steps to a quest
   *
   * @static
   * @access  public
   * @return  void
   */
  public static function get_quest_html( $count = 0, $data = array() ) {

    $defaults = array(
      'type'              => 0,
      'step_description'  => '',
      'ld_first_attempt_only' => false,
      'blog_limit'        => 0,
      'associated_title'  => '',
      'associated_id'     => '',
      'data_post_id'      => '',
      'ach_id'            => '',
      'woo_order_limit'   => 0,
      'occurrences'       => 1,
    );

    $data = wp_parse_args( $data, $defaults);

    $count = filter_input( INPUT_POST, 'quest_count', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => $count ) ) );

    // Increment the count for the new element
    $count++;

    $extra_classes = '';

    ob_start();
    ?>
    <div id="quest_item_<?php echo $count; ?>" class="quest_step_wrap">
      <span class="pullleft <?php echo $extra_classes; ?>">
        <label for="wpachievements_achievements_data_event_<?php echo $count; ?>">
            <?php _e('Trigger Event:', 'wpachievements'); ?>
        </label><br/>

        <select id="wpachievements_achievements_data_event_<?php echo $count; ?>" name="wpachievements_achievements_data_event_<?php echo $count; ?>" class="trigger_select" <?php if ( $data['type'] ) : echo 'disabled' . ' title="' . __('This cannot be changed once the quest is created.', 'wpachievements') . '"'; endif; ?>>

          <?php if ( $data['type'] ) : ?>
          <optgroup label="<?php _e('Currently Selected', 'wpachievements'); ?>">
            <option value="<?php echo $data['type']; ?>" selected><?php echo wpachievements_get_trigger_description( $data['type'] ); ?></option>
          </optgroup>
          <?php else : ?>
            <option value="0" selected>---------------- <?php _e('Select', 'wpachievements'); ?> ----------------</option>
            <?php do_action('wpachievements_admin_events'); ?>
          <?php endif; ?>
        </select>
      </span>

      <span class="step_description">
        <label for="wpachievements_achievements_data_step_description_<?php echo $count; ?>">
          <?php _e("Step description", 'wpachievements'); ?>
        </label>
        <input type="text" id="wpachievements_achievements_data_step_description_<?php echo $count; ?>" name="wpachievements_achievements_data_step_description_<?php echo $count; ?>" value="<?php echo $data['step_description']; ?>" />
      </span>

      <div class="clear"></div>

      <?php if ( is_multisite() ) :
        $blog_list = get_sites( array( 'number' => 1000 ) ); ?>

        <span id="blog_limit" class="pullleft">
          <label for="wpachievements_achievement_blog_limit_<?php echo $count; ?>">
            <?php _e('Limit to Blog:', 'wpachievements'); ?>
          </label>

          <select id="wpachievements_achievement_blog_limit_<?php echo $count; ?>" name="wpachievements_achievement_blog_limit_<?php echo $count; ?>">
            <option value="0" <?php selected( $data['blog_limit'], 0 ); ?>><?php _e("Any Blog", 'wpachievements'); ?></option>
            <?php foreach( $blog_list as $blog ) : ?>
              <option value="<?php echo $blog->id; ?>" <?php selected( $data['blog_limit'], $blog->id ); ?>><?php echo $blog->blogname; ?></option>
              <?php
            endforeach; ?>
          </select>
        </span>
        <?php
      endif; ?>

      <?php
      $show = '';
      if ( $data['associated_title'] && 'cp_bp_group_joined' == $data['type'] ) {
        $show = ' style="display:block !important;"';
      }
      ?>

      <span id="ass_title<?php echo $show; ?>">
        <label for="wpachievements_achievement_bp_group_title_<?php echo $count; ?>">
          <?php _e('Group Title: <small>(Optional)</small>', 'wpachievements'); ?>
        </label>
        <input type="text" id="wpachievements_achievement_bp_group_title_<?php echo $count; ?>" name="wpachievements_achievement_bp_group_title_<?php echo $count; ?>" value="<?php echo $data['associated_title']; ?>" />
      </span>

      <?php
      switch ( $data['type'] ) {
        case 'ld_lesson_complete':
        case 'ld_course_complete':
        case 'sensei_lesson_complete': {
          $postid_title = __('Lesson ID: <small>(Optional)</small>', 'wpachievements');
          $show = ' style="display:block !important;"';
        } break;

        case 'ld_course_complete':
        case 'wpcw_course_complete':
        case 'sensei_course_complete': {
          $postid_title = __('Course ID: <small>(Optional)</small>', 'wpachievements');
          $show = ' style="display:block !important;"';
        } break;

        case 'ld_quiz_pass':
        case 'wpcw_quiz':
        case 'sensei_quiz_pass': {
          $postid_title = __('Quiz ID: <small>(Optional)</small>', 'wpachievements');
          $show = ' style="display:block !important;"';
        } break;

        case 'wpcw_module_complete': {
          $postid_title = __('Module ID: <small>(Optional)</small>', 'wpachievements');
          $show = ' style="display:block !important;"';
        } break;

        case 'gform_sub': {
          $postid_title = __('Form ID: <small>(Optional)</small>', 'wpachievements');
          $show = ' style="display:block !important;"';
        } break;

        default: {
          $postid_title = __('Post ID: <small>(Optional)</small>', 'wpachievements');
          $show = '';
        } break;
      }
      ?>

      <span id="post_id">
        <label for="wpachievements_achievements_data_post_id_<?php echo $count; ?>">
          <?php echo $postid_title; ?>
        </label>
        <input type="text" id="wpachievements_achievements_data_post_id_<?php echo $count; ?>" name="wpachievements_achievements_data_post_id_<?php echo $count; ?>" value="<?php echo $data['associated_id']; ?>" />
      </span>

      <?php
      $show = ( empty( $data['ach_id'] ) ) ? 'display:none;' : 'display:block !important;';
      $disabled = ( empty( $data['ach_id'] ) ) ? '' : 'disabled title="'.__('This cannot be changed once the quest is created.', 'wpachievements').'"';
      $achievements_list = wpa_quest_achievement_list();

      if ( $achievements_list ) : ?>
        <span id="custom_event_details" style="<?php echo $show; ?>">
          <label for="wpachievements_achievements_data_ach_id_<?php echo $count; ?>">,
            <?php _e('Select Achievement:', 'wpachievements'); ?>
          </label>
          <select id="wpachievements_achievements_data_ach_id_<?php echo $count; ?>" name="wpachievements_achievements_data_ach_id_<?php echo $count; ?>" <?php echo $disabled; ?>>

            <?php if ( $data['ach_id'] ) : ?>
            <optgroup label="<?php _e('Currently Selected', 'wpachievements'); ?>">
            <option value="<?php echo $data['ach_id']; ?>" selected><?php echo get_the_title( $data['ach_id'] ); ?></option>
            </optgroup>
            <?php else: ?>
            <option value="" selected>---------------- <?php _e('Select', 'wpachievements'); ?> ----------------</option>
            <?php endif; ?>

            <?php echo $achievements_list; ?>
          </select>
        </span>
        <?php
      endif; ?>

      <label for="wpachievements_achievements_data_event_no_<?php echo $count; ?>">
        <?php _e('Number of Occurrences', 'wpachievements'); ?>:
      </label>
      <div class="spinner-holder">
        <input type="number" min="1" id="wpachievements_achievements_data_event_no_<?php echo $count; ?>" name="wpachievements_achievements_data_event_no_<?php echo $count; ?>" value="<?php echo $data['occurrences']; ?>" />
      </div>

      <a href="#" class="button_quest_remove <?php echo ( $count <= 2 ) ? 'disabled' : ''; ?>"><?php _e('Remove Trigger', 'wpachievements'); ?></a>
      <div class="clear"></div>
    </div>
    <?php

    $output = ob_get_clean();

    // Output the content if we are doing ajax. Otherwise return the content
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX && ( 'wpachievements_quest_html' == filter_input( INPUT_POST, 'action' ) ) ) {
      echo $output;
      wp_die();
    }

    return $output;
  }

  /**
   * Save a quest
   *
   * @static
   * @access  public
   * @param   int $post_id Post ID
   * @return  int Post ID
   */
  public static function save_post( $post_id ) {
    global $wpdb;

    $nonce = filter_input( INPUT_POST, 'wpachievements_quest_nonce' );

    if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wpachievements_quest_save' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
      return $post_id;
    }

    if ( 'page' == $_POST['post_type'] ) {
      if ( !current_user_can( 'edit_page', $post_id ) ) {
        return $post_id;
      }
    }
    else {
      if ( !current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
      }
    }

    $quest_title = sanitize_text_field( $_POST['post_title'] );
    $quest_desc = $_POST['achievement_desc_editor'];

    if ( isset($_POST['wpachievements_achievements_data_rank']) ) {
      $quest_rank = $_POST['wpachievements_achievements_data_rank'];
    }
    else {
      $quest_rank = 'any';
    }

    $quest_points = sanitize_text_field( $_POST['wpachievements_achievements_data_points'] );

    if ( isset($_POST['wpachievements_achievements_data_wc_points']) ) {
      $quest_wcpoints = sanitize_text_field( $_POST['wpachievements_achievements_data_wc_points'] );
    }
    else {
      $quest_wcpoints = '';
    }

    $quest_img = $_POST['upload_image'];

    $quest=array(); $count=0;
    $quest_count = filter_input( INPUT_POST, 'quest_item_counter', FILTER_VALIDATE_INT );

    while( $count<=$quest_count ) {
      $count++;
      if ( !isset($_POST['wpachievements_achievements_data_event_'.$count]) ) {
        continue;
      }

      $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['type'] = $_POST['wpachievements_achievements_data_event_'.$count];

      $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['occurrences'] = sanitize_text_field( $_POST['wpachievements_achievements_data_event_no_'.$count] );

      if ( isset($_POST['wpachievements_achievements_data_ach_id_'.$count]) ) {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['ach_id'] =  sanitize_text_field( $_POST['wpachievements_achievements_data_ach_id_'.$count] );
      }
      else {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['ach_id'] = '';
      }

      if ( isset($_POST['wpachievements_achievements_data_post_id_'.$count]) ) {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['associated_id'] =  sanitize_text_field( $_POST['wpachievements_achievements_data_post_id_'.$count] );
      }
      else {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['associated_id'] = '';
      }

      if ( isset($_POST['wpachievements_achievements_data_step_description_'.$count]) ) {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['step_description'] =  sanitize_text_field( $_POST['wpachievements_achievements_data_step_description_'.$count] );
      }
      else {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['step_description'] = '';
      }

      if ( isset($_POST['wpachievements_achievement_woo_order_limit_'.$count]) ) {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['woo_order_limit'] = sanitize_text_field( $_POST['wpachievements_achievement_woo_order_limit_'.$count] );
      }
      else {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['woo_order_limit'] = '';
      }

      if ( isset($_POST['wpachievements_achievement_ld_first_try_'.$count]) ) {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['ld_first_attempt_only'] = sanitize_text_field( $_POST['wpachievements_achievement_ld_first_try_'.$count] );
      }
      else {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['ld_first_attempt_only'] = '';
      }

      if ( isset($_POST['wpachievements_achievement_bp_group_title_'.$count]) ) {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['associated_title'] = sanitize_text_field( $_POST['wpachievements_achievement_bp_group_title_'.$count] );
      }
      else {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['associated_title'] = '';
      }

      if ( isset($_POST['wpachievements_achievement_blog_limit_'.$count]) ) {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['blog_limit'] = sanitize_text_field( $_POST['wpachievements_achievement_blog_limit_'.$count] );
      }
      else {
        $quest[$count.'_'.$_POST['wpachievements_achievements_data_event_'.$count]]['blog_limit'] = '';
      }
    }

    $already_exists = get_post_meta( $post_id, '_quest_details', true );

    if ( $already_exists ) {

      $quest_prev_points = get_post_meta( $post_id, '_quest_points', true );
      $quest_prev_wcpoints = get_post_meta( $post_id, '_quest_woo_points', true );
      $quest_prev_rank = get_post_meta( $post_id, '_quest_rank', true );

      if ( $quest_rank != $quest_prev_rank || $quest_points != $quest_prev_points || $quest_wcpoints != $quest_prev_wcpoints ) {
        $quest_data = $quest_title.': '.$quest_desc;

        $users_gained = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key LIKE '_user_gained_%%'", $post_id) );

        if ( $users_gained ) {
          foreach( $users_gained as $user ) {
            $remove_ach = false;

            if ( $quest_rank != $quest_prev_rank ) {
              $usersrank = wpachievements_getRank($user->meta_value);
              $userrank_lvl = wpachievements_rankToPoints($usersrank);
              $quest_rank_lvl = wpachievements_rankToPoints($quest_rank);

              if ( $userrank_lvl < $quest_rank_lvl ) {
                $remove_ach = true;
                $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
                $user_quest_count = (int)sizeof($userachievements);

                if ( $user_quest_count > 1 ) {
                  foreach($userachievements as $key => $value) {
                    if ( $value == $post_id ) {
                      unset($userachievements[$key]);
                    }
                  }

                  update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );
                }
                else {
                  delete_user_meta( $user->meta_value, 'achievements_gained' );
                }

                do_action( 'wpachievements_remove_achievement', $user->meta_value, $post_id );

                $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$quest_data', '-%d', '')", $quest_prev_points) );

                $user_quest_count = (int)$user_quest_count - 1;

                WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_removed',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => -$quest_prev_points,
                  'reference'         => 'wpachievements_removed',
                  'log_entry'         => 'for Achievement Removed: '.$quest_title,
                ) );

                delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
                update_user_meta( $user->meta_value, 'achievements_count', $user_quest_count);
              }
            }

            if ( $quest_points != $quest_prev_points && !$remove_ach ) {
              if ( $quest_points < $quest_prev_points ) {

                $deduct_points = $quest_prev_points - $quest_points;

                WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_edited_remove',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => -$deduct_points,
                  'reference'         => 'wpachievements_changed',
                  'log_entry'         => 'Achievement Modified: '.$quest_title,
                ) );
              }
              else {
                $add_points = $quest_points - $quest_prev_points;

                WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_edited_add',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => $add_points,
                  'reference'         => 'wpachievements_changed',
                  'log_entry'         => 'Achievement Modified: '.$quest_title,
                ) );
              }
            }

            $wcpr_sync = wpachievements_get_site_option( 'wpachievements_wcpr_sync_enabled' );

            if ( $wcpr_sync != 'yes' ) {
              if ( $quest_wcpoints != $quest_prev_wcpoints && !$remove_ach ) {
                if ( $quest_points < $quest_prev_points ) {

                  $deduct_points = $quest_prev_wcpoints - $quest_wcpoints;

                }
                else {
                  $add_points = $quest_wcpoints - $quest_prev_wcpoints;
                }
              }
            }
          }
        }
      }
    }

    remove_action( 'save_post', array( __CLASS__, 'save_post' ) );

    $wpa_args = array(
      'ID'           => $post_id,
      'post_content' => $quest_desc,
      'post_status'  => 'publish'
    );

    wp_update_post( $wpa_args );

    add_action('save_post', array( __CLASS__, 'save_post' ) );

    update_post_meta( $post_id, '_quest_points', $quest_points );
    update_post_meta( $post_id, '_quest_woo_points', $quest_wcpoints );
    update_post_meta( $post_id, '_quest_rank', $quest_rank );
    update_post_meta( $post_id, '_quest_image', $quest_img );
    update_post_meta( $post_id, '_quest_details', $quest );
  }

  /**
   * Delete an achievement
   *
   * @static
   * @access  public
   * @param   int $post_id Post ID
   * @return  void
   */
  public static function delete_post( $post_id ) {
    global $wpdb;

    $post = get_post($post_id);

    if ( $post->post_type != 'wpquests' ) {
      return;
    }

    $gained_users = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d", '%_user_gained_%', $post_id) );

    if ( $gained_users ) {
      foreach( $gained_users as $user ) {

        $userquests = get_user_meta( $user->meta_value, 'quests_gained', true );
        $quest_ID = get_the_ID();
        $quest_title = get_the_title();
        $quest_desc = get_the_content();
        $quest_data = $quest_title.': '.$quest_desc;
        $quest_points = get_post_meta( $quest_ID, '_quest_points', true );
        $quest_woopoints = get_post_meta( $quest_ID, '_quest_woo_points', true );
        $quest_img = get_post_meta( $quest_ID, '_quest_image', true );

        WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_quest_removed',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => -$quest_points,
                  'reference'         => 'wpachievements_quest_removed',
                  'log_entry'         => 'for Quest Removed: '.$quest_title,
                ) );

        do_action( 'wpachievements_admin_remove_quest', $user->meta_value, 'wpachievements_quest_removed', $quest_points );

        $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_quest_removed', '$quest_data', '-%d', '')", $quest_points) );

        delete_post_meta( $quest_ID, '_user_gained_'.$user->meta_value );

        $quest_meta = get_user_meta( $user->meta_value, 'wpachievements_got_new_quest', true );

        if ( in_array_r( $quest_title, $quest_meta ) && in_array_r( $quest_desc, $quest_meta ) && in_array_r( $quest_img, $quest_meta )  ) {
          foreach( $quest_meta as $key => $value ) {
            if ( $value["title"] == $quest_title && $value["text"] == $quest_desc && $value["image"] == $quest_img ) {
              unset($quest_meta[$key]);
            }
          }
        }

        update_user_meta( $user->meta_value, 'wpachievements_got_new_quest', $quest_meta );

        foreach($userquests as $key => $value) {
          if ( $value == $post_id ) {
            unset($userquests[$key]);
          }
        }

        update_user_meta( $user->meta_value, 'quests_gained', $userquests );

        $user_ach_count = (int)sizeof($userquests);
        $user_ach_count = $user_ach_count - 1;
        update_user_meta( $user->meta_value, 'quests_count', $user_ach_count);
      }
    }
  }
}

endif;

WPAchievements_Admin_Quests::init();