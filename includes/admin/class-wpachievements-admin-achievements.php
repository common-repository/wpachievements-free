<?php
/**
 * Handles creating and editing Achievements
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPAchievements_Admin_Achievements' ) ) :

class WPAchievements_Admin_Achievements {


  public static function init() {
    add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ), 1 );
    add_action( 'save_post', array( __CLASS__, 'save_post' ) );
    add_action( 'before_delete_post', array( __CLASS__, 'delete_post' ), 1 );
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
      '<strong>'. __( 'Achievement Text', 'wpachievements' )  .'</strong> - <small>'. __('This text is displayed when a user get the achievement.', 'wpachievements').'</small>',
      'wpachievements_descrition_editor', 'wpachievements', 'normal', 'high'
    );

    add_meta_box(
      'achievement_details',
      '<strong>'. __( 'Achievement Details', 'wpachievements' )  .'</strong> - <small>'. __('Setup the detials of the achievement.', 'wpachievements').'</small>',
      array( __CLASS__, 'how_to_achieve' ), 'wpachievements', 'normal', 'high'
    );

    add_meta_box(
      'achievement_image',
      '<strong>'. __( 'Achievement Image', 'wpachievements' )  .'</strong>',
      'wpachievements_image_box', 'wpachievements', 'side', 'high', array( 'title' => __("Achievement", "wpachievements"), 'id' => 'achievement' )
    );

    add_meta_box(
      'achievement_gained',
      '<strong>'. __( 'Users', 'wpachievements' )  .'</strong>',
      array( __CLASS__, 'gained_by'), 'wpachievements', 'side'
    );
  }

  public static function get_title_for_trigger( $trigger ) {

    switch( $trigger ) {
      case 'ld_lesson_complete':
      case 'ld_course_complete':
      case 'sensei_lesson_complete': {
        return __('Lesson ID', 'wpachievements');
      } break;

      case 'ld_course_complete':
      case 'sensei_course_complete': {
        return __('Course ID', 'wpachievements');
      } break;

      case 'ld_quiz_pass':
      case 'wpcw_quiz': {
        return __('Quiz ID', 'wpachievements');
      } break;

      case 'wpcw_module_complete': {
        return __('Module ID', 'wpachievements');
      } break;

      case 'wpcw_course_complete': {
        return __('Course ID', 'wpachievements');
      } break;

      case 'gform_sub': {
        return __('Form ID', 'wpachievements');
      } break;

      case 'user_post_view':
      case 'user_page_view': {
        return __('Post ID', 'wpachievements');
      } break;

      case 'comment_added': {
        return __('Comment Post ID', 'wpachievements');
      } break;

      default: {
        return  __('Form ID', 'wpachievements');
      } break;
    }
  }

  /**
   * Defines the triggers to gain an achievement
   *
   * @static
   * @access  public
   * @param   WP_POST $post
   * @return  void
   */
  public static function how_to_achieve( $post ) {

    $cur_rank = get_post_meta( $post->ID, '_achievement_rank', true );
    $cur_trigger = get_post_meta( $post->ID, '_achievement_type', true );
    $cur_points = get_post_meta( $post->ID, '_achievement_points', true );
    $cur_woopoints = intval( get_post_meta( $post->ID, '_achievement_woo_points', true ) );
    $cur_post = get_post_meta( $post->ID, '_achievement_associated_id', true );
    $cur_occurences = get_post_meta( $post->ID, '_achievement_occurrences', true );
    $cur_order_limit = intval( get_post_meta( $post->ID, '_achievement_woo_order_limit', true ) );
    $cur_ass_title = get_post_meta( $post->ID, '_achievement_associated_title', true );
    $cur_trigger_id = get_post_meta( $post->ID, '_achievement_trigger_id', true );
    $cur_activity_code = get_post_meta( $post->ID, '_achievement_activity_code', true );
    $cur_trigger_desc = get_post_meta( $post->ID, '_achievement_trigger_desc', true );
    $cur_recurring = get_post_meta( $post->ID, '_achievement_recurring', true );

    if ( empty( $cur_points ) ) {
      $cur_points = 1;
    }

    if ( empty( $cur_occurences ) ) {
      $cur_occurences=1;
    }

    do_action ('wpachievements_achievements_how_to_achieve_header', $post->ID, $cur_trigger_id, $cur_trigger, $cur_trigger_desc );

    //
    // Display the rank selection if enabled
    //

    if ( 'Disable' != wpachievements_get_site_option('wpachievements_rank_status') ) {
      echo '<span class="pullleft first-select">
      <label for="wpachievements_achievements_data_rank">'.__('Limit to Rank', 'wpachievements').':</label><br/>
      <select name="wpachievements_achievements_data_rank" id="wpachievements_achievements_data_rank">';

      if ( $cur_rank ) {
        $current = $cur_rank;

        if ( $cur_rank == 'any' ) {
          $current = 'any';
          $cur_rank = 'Any Rank';
        }

        echo '
          <optgroup label="'.__('Currently Selected', 'wpachievements').'">
          <option value="'.$current.'" selected>'.$cur_rank.'</option>
        </optgroup>';
      }

      echo'
        <optgroup label="'.__('Available Ranks', 'wpachievements').'">
        <option value="any">'.__('Any Rank', 'wpachievements').'</option>';

      $ranks = (array) wpachievements_get_site_option('wpachievements_ranks_data');

      foreach( $ranks as $points=>$rank ) {
        $rank_text = is_array( $rank ) ? $rank[0] : $rank;
        echo '<option value="'.$rank_text.'">'.$rank_text.'</option>';
      }

      echo '</optgroup></select></span>';
    }

    //
    // Display the Trigger Events selection
    //

    $disabled = ( $cur_trigger ) ? ' disabled title="This cannot be changed once the achievement is created."' : '';
    $extra_classes = '';

    echo '<span class="pullleft'.$extra_classes.'">
      <label for="wpachievements_achievements_data_event">'.__('Trigger Event', 'wpachievements').':</label><br/>
      <select id="wpachievements_achievements_data_event" name="wpachievements_achievements_data_event"'.$disabled.'>';

    if ( $cur_trigger ) {
      echo '<optgroup label="'.__('Currently Selected', 'wpachievements').'">
        <option value="'.$cur_trigger.'" selected>'.wpachievements_get_trigger_description( $cur_trigger ).'</option>
        </optgroup>';
    }
    else {
      echo '<option value="" selected>---------------- '.__('Select', 'wpachievements').' ----------------</option>';
      do_action('wpachievements_admin_events');
    }

    echo '</select></span>';

    $checked = ( $cur_recurring == 1 ) ? ' checked' : '';

    echo '<span class="pullleft wpa_checkbox">
      <label for="wpachievements_achievements_recurring">'.__('Recurring Achievement', 'wpachievements').':
      <input type="checkbox" id="wpachievements_achievements_recurring" name="wpachievements_achievements_recurring"'.$checked.' /></label><br/>
    </span>';

    do_action ('wpachievements_achievements_event_data_footer', $post->ID, $cur_trigger_id, $cur_trigger, $cur_trigger_desc );

    echo '<div class="clear"></div>';
    echo '<div id="event_details" style="display:none;">';

    do_action ('wpachievements_achievements_event_details_header', $post->ID, $cur_trigger_id, $cur_trigger, $cur_trigger_desc );

    // Display limit to blog on multisite installations
    if ( is_multisite() ) {
      $cur_blog_limit = intval( get_post_meta( $post->ID, '_achievement_blog_limit', true ) );
      // Get all sites
      $blog_list = get_sites( array( 'number' => 1000 ) );

      echo '<span id="blog_limit" class="pullleft">
        <label for="wpachievements_achievement_blog_limit">'.__('Limit to Blog', 'wpachievements').':</label>
        <select id="wpachievements_achievement_blog_limit" name="wpachievements_achievement_blog_limit">';
      echo '<option value="0" '.selected( $cur_blog_limit, 0, false ).'>'.__("Any Blog", 'wpachievements').'</option>';

      foreach( $blog_list as $blog ) {
        echo '<option value="'.$blog->id.'" '.selected( $cur_blog_limit, $blog->id, false ).'>'.$blog->blogname.'</option>';
      }

      echo '</select></span>';
    }

    $show = ( $cur_ass_title && $cur_trigger == 'cp_bp_group_joined' ) ? ' style="display:block !important;"' : '';

    echo '<span id="ass_title"'.$show.'>
      <label for="wpachievements_achievement_bp_group_title">'.__('Group Title', 'wpachievements').': <small>(Optional)</small></label>
      <input type="text" id="wpachievements_achievement_bp_group_title" name="wpachievements_achievement_bp_group_title" value="'.$cur_ass_title.'" />
    </span>';

    $postid_title = self::get_title_for_trigger( $cur_trigger );
    $postid_title = apply_filters('wpachievements_achievements_event_details_data_post_id_text', $postid_title, $cur_post, $cur_trigger);

    $show = ($cur_post) ? ' style="display:block !important;"': '';
    $show = apply_filters('wpachievements_achievements_event_details_data_post_id_show', $show, $cur_post, $cur_trigger);

    echo '<span id="post_id"'.$show.'>
      <label for="wpachievements_achievements_data_post_id">'.$postid_title.': <small>(Optional)</small></label>
      <input type="text" id="wpachievements_achievements_data_post_id" name="wpachievements_achievements_data_post_id" value="'.$cur_post.'" />
    </span>';

    $show = ( $cur_trigger_id ) ? ' style="display:block !important;"' : ' style="display:none;"';

    echo '<div id="custom_event_details"'.$show.'>';
    echo '<span>
      <label for="wpachievements_achievements_custom_trigger_id">'.__('Unique Trigger ID', 'wpachievements').': &nbsp;&nbsp;<small>'.__('(This must be completey unique and start with a letter!)', 'wpachievements').'</small></label>
        <input type="text" id="wpachievements_achievements_custom_trigger_id" name="wpachievements_achievements_custom_trigger_id" value="'.$cur_trigger_id.'" />
    </span>';
    echo '<span>
      <label for="wpachievements_achievements_custom_trigger_desc">'.__('Get this achievement for...', 'wpachievements').' &nbsp;&nbsp;<small>'.__('(Example: "adding a comment")', 'wpachievements').'</small></label>
        <input type="text" id="wpachievements_achievements_custom_trigger_desc" name="wpachievements_achievements_custom_trigger_desc" value="'.$cur_trigger_desc.'" />
    </span>';
    echo '</div>';

    $show_code = ( $cur_trigger == 'activity_code_achievement' ) ? ' style="display:block !important;"' : ' style="display:none;"';

    echo '<div id="activity_code_event_details"'.$show_code.'>';
    echo '<span>
      <label for="wpachievements_achievements_activity_code_trigger_id">'.__('Activity Code', 'wpachievements').': &nbsp;&nbsp;<small>'.__('Allow users to earn achievement by entering this code.', 'wpachievements').'</small></label>
        <input type="text" id="wpachievements_achievements_activity_code_trigger_id" name="wpachievements_achievements_activity_code_trigger_id" value="'.$cur_activity_code.'" />
    </span>';
    echo '</div>';

    echo '<label for="wpachievements_achievements_data_event_no">'.__('Number of Occurrences', 'wpachievements').':</label>
      <div class="spinner-holder">
        <input type="number" min="1" id="wpachievements_achievements_data_event_no" name="wpachievements_achievements_data_event_no" value="'.$cur_occurences.'" />
      </div>
      <label for="wpachievements_achievements_data_points">'.__('Points Awarded / Deducted', 'wpachievements').':</label>
      <div class="spinner-holder">
        <input type="number" id="wpachievements_achievements_data_points" name="wpachievements_achievements_data_points" value="'.$cur_points.'" />
      </div>';

    do_action ('wpachievements_achievements_event_details_footer', $post->ID, $cur_trigger_id, $cur_trigger, $cur_trigger_desc );

    echo'</div>';

    do_action ('wpachievements_achievements_how_to_achieve_footer', $post->ID, $cur_trigger_id, $cur_trigger, $cur_trigger_desc );
    echo '<div class="clear"></div>';
  }

  /**
   * Show who has already gained this achievement
   *
   * @param WP_Post $post
   * @return void
   */
   public static function gained_by( $post ) {
    global $wpdb;

    if ( ! $post->ID ) {
      return;
    }

    wp_enqueue_script( 'jquery-ui-autocomplete' );

    ?>
    <h4><?php _e( "Award manually to user", 'wpachievements' ); ?></h4>
    <form method="post" name="manuallyaward" id="award">
      <input name="post_id" id="to_award_post_id" type="hidden" value="<?php echo $post->ID; ?>" />
      <input name="user_id" id="to_award_user_id" type="hidden" value="" />
      <input name="user_login" type="text" id="user_login" value="" class="wpa-suggest-user" />
      <input type="button" value="<?php _e( "Award", 'wpachievements' ); ?>" id="award_user" />
    </form>
    <h4><?php _e( "Already gained", 'wpachievements' ); ?></h4>
    <div class="already_gained">
    <?php

    $users = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value as user_id FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_user_gained_%' AND post_id = '%d'", $post->ID ) );

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
      echo '<p><span class="nobody">' . __("Nobody has gained this achievement, yet!", "wpachievements" ) . '</span</p>';
    }
    ?>
    </div>
    <?php
  }

  /**
   * Save achievement
   *
   * @static
   * @access  public
   * @param   int $post_id Post ID
   * @return  int Post ID
   */
  public static function save_post( $post_id ) {
    global $wpdb;

    $nonce = filter_input( INPUT_POST, 'wpachievements_achievement_nonce' );
    $quest_nonce = filter_input( INPUT_POST, 'wpachievements_quest_nonce' );

    if (  $quest_nonce || ! $nonce || ! wp_verify_nonce( $nonce, 'wpachievements_achievement_save' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
      return $post_id;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return $post_id;
    }

    $ach_title = sanitize_text_field( filter_input( INPUT_POST, 'post_title' ) );
    $ach_desc = filter_input( INPUT_POST, 'achievement_desc_editor' );
    $ach_rank = filter_input( INPUT_POST, 'wpachievements_achievements_data_rank', FILTER_SANITIZE_STRING, array( "options" => array( "default" => 'any', ) ) );
    $ach_type = filter_input( INPUT_POST, 'wpachievements_achievements_data_event', FILTER_SANITIZE_STRING );

    if ( $ach_type == 'custom_trigger' ) {
      $ach_trigger_id = filter_input( INPUT_POST, 'wpachievements_achievements_custom_trigger_id' );
      $ach_trigger_desc = filter_input( INPUT_POST, 'wpachievements_achievements_custom_trigger_desc');
    }

    $ach_img = filter_input( INPUT_POST, 'upload_image', FILTER_SANITIZE_URL );
    $ach_postid = filter_input( INPUT_POST, 'wpachievements_achievements_data_post_id', FILTER_SANITIZE_STRING );
    $ach_occur = filter_input( INPUT_POST, 'wpachievements_achievements_data_event_no' );
    $ach_points = filter_input( INPUT_POST, 'wpachievements_achievements_data_points' );
    $ach_wcpoints = filter_input( INPUT_POST, 'wpachievements_achievements_data_wc_points' );
    $ach_order_limit = filter_input( INPUT_POST, 'wpachievements_achievement_woo_order_limit' );
    $ach_first_try = filter_input( INPUT_POST, 'wpachievements_achievement_ld_first_try' );
    $ach_wplms_evaluate = filter_input( INPUT_POST, 'wpachievements_achievement_wplms_evaluate_limit' );
    $ach_ass_title = filter_input( INPUT_POST, 'wpachievements_achievement_bp_group_title' );
    $ach_blog_Limit = filter_input( INPUT_POST, 'wpachievements_achievement_blog_limit' );
    $ach_recurring = filter_input( INPUT_POST, 'wpachievements_achievements_recurring' );
    if ( $ach_recurring ) {
      $ach_recurring = 1;
    }

    $already_exists = get_post_meta( $post_id, '_achievement_points', true );

    if ( $already_exists ) {
      $ach_prev_points = get_post_meta( $post_id, '_achievement_points', true );
      $ach_prev_wcpoints = get_post_meta( $post_id, '_achievement_woo_points', true );
      $ach_prev_rank = get_post_meta( $post_id, '_achievement_rank', true );
      $ach_prev_occur = get_post_meta( $post_id, '_achievement_occurrences', true );
      $ach_prev_postid = get_post_meta( $post_id, '_achievement_associated_id', true );
      $ach_prev_ass_title = get_post_meta( $post_id, '_achievement_associated_title', true );

      if ( $ach_rank != $ach_prev_rank || $ach_points != $ach_prev_points || $ach_wcpoints != $ach_prev_wcpoints || $ach_prev_occur != $ach_occur || $ach_prev_postid != $ach_postid || $ach_ass_title != $ach_prev_ass_title ) {
        $ach_data = $ach_title.': '.$ach_desc;

        $users_gained = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key LIKE '_user_gained_%%'", $post_id) );

        if ( $users_gained ) {
          foreach( $users_gained as $user ) {
            $remove_ach = false;

            if ( $ach_rank != $ach_prev_rank ) {
              $usersrank = wpachievements_getRank($user->meta_value);
              $userrank_lvl = wpachievements_rankToPoints($usersrank);
              $ach_rank_lvl = wpachievements_rankToPoints($ach_rank);

              if ( $userrank_lvl < $ach_rank_lvl ) {
                $remove_ach = true;
                $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
                $user_ach_count = (int)sizeof($userachievements);

                if ( $user_ach_count > 1 ) {
                  foreach($userachievements as $key => $value) {
                    if ( $value == $post_id )
                      unset($userachievements[$key]);
                  }
                  update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );
                }
                else {
                  delete_user_meta( $user->meta_value, 'achievements_gained' );
                }

                do_action( 'wpachievements_remove_achievement', $user->meta_value, $post_id );

                $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_prev_points) );

                $user_ach_count = (int)$user_ach_count - 1;

                WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_removed',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => -$ach_prev_points,
                  'reference'         => 'wpachievements_removed',
                  'log_entry'         => 'for Achievement Removed: '.$ach_title,
                ) );

                delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
                update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);
              }
            }

            if ( $ach_ass_title != $ach_prev_ass_title && !$remove_ach ) {
              $group_id = BP_Groups_Group::group_exists($ach_ass_title);

              if ( $ach_rank ) {
                $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type='%s' AND uid=$user->meta_value AND rank='%s' AND postid=%d", $ach_type,$ach_rank,$group_id) );
              }
              else {
                $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type='%s' AND uid=$user->meta_value AND postid=%d", $ach_type,$group_id) );
              }

              if ( $activities_count < $ach_occur ) {
                $remove_ach = true;
                $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
                $user_ach_count = (int)sizeof($userachievements);

                if ( $user_ach_count > 1 ) {
                  foreach($userachievements as $key => $value) {
                    if ( $value == $post_id )
                      unset($userachievements[$key]);
                  }

                  update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );
                }
                else {
                  delete_user_meta( $user->meta_value, 'achievements_gained' );
                }

                do_action( 'wpachievements_remove_achievement', $user->meta_value, $post_id );

                $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_prev_points) );

                $user_ach_count = (int)$user_ach_count - 1;

                WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_removed',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => -$ach_prev_points,
                  'reference'         => 'wpachievements_removed',
                  'log_entry'         => 'for Achievement Removed: '.$ach_title,
                ) );

                delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
                update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);
              }
            }

            if ( $ach_prev_postid != $ach_postid && !$remove_ach ) {
              if ( $ach_rank ) {
                $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type='$ach_type' AND uid=$user->meta_value AND rank='$ach_rank' AND postid=%d", $ach_postid) );
              }
              else {
                $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type='$ach_type' AND uid=$user->meta_value AND postid=%d", $ach_postid) );
              }

              if ( $activities_count < $ach_occur ) {
                $remove_ach = true;
                $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
                $user_ach_count = (int)sizeof($userachievements);

                if ( $user_ach_count > 1 ) {
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

                $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_prev_points) );

                $user_ach_count = (int)$user_ach_count - 1;

                WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_removed',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => -$ach_prev_points,
                  'reference'         => 'wpachievements_removed',
                  'log_entry'         => 'for Achievement Removed: '.$ach_title,
                ) );

                delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
                update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);
              }
            }

            if ( $ach_prev_occur != $ach_occur && !$remove_ach ) {
              $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM ".WPAchievements()->get_table()." WHERE type='%s' AND uid=$user->meta_value AND rank='%s'", $ach_type,$ach_rank) );

              if ( $activities_count < $ach_occur ) {
                $remove_ach = true;
                $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
                $user_ach_count = (int)sizeof($userachievements);

                if ( $user_ach_count > 1 ) {
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

                $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_prev_points) );

                $user_ach_count = (int)$user_ach_count - 1;

                WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_removed',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => -$ach_prev_points,
                  'reference'         => 'wpachievements_removed',
                  'log_entry'         => 'for Achievement Removed: '.$ach_title,
                ) );

                delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
                update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);
              }
            }

            if ( $ach_points != $ach_prev_points && !$remove_ach ) {
              if ( $ach_points < $ach_prev_points ) {
                $deduct_points = $ach_prev_points - $ach_points;

                WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_edited_remove',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => -$deduct_points,
                  'reference'         => 'wpachievements_changed',
                  'log_entry'         => 'Achievement Modified: '.$ach_title,
                ) );
              }
              else {
                $add_points = $ach_points - $ach_prev_points;

                WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_edited_add',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => $add_points,
                  'reference'         => 'wpachievements_changed',
                  'log_entry'         => 'Achievement Modified: '.$ach_title,
                ) );
              }
            }

            $wcpr_sync = wpachievements_get_site_option( 'wpachievements_wcpr_sync_enabled' );

            if ( $wcpr_sync != 'yes' ) {
              if ( $ach_wcpoints != $ach_prev_wcpoints && !$remove_ach ) {
                if ( $ach_points < $ach_prev_points ) {

                  $deduct_points = $ach_prev_wcpoints - $ach_wcpoints;
                }
                else {
                  $add_points = $ach_wcpoints - $ach_prev_wcpoints;

                }
              }
            }
          }
        }
      }
    }

    remove_action('save_post', array( __CLASS__, 'save_post' ) );

    $wpa_args = array(
      'ID'           => $post_id,
      'post_content' => $ach_desc,
      'post_status'  => 'publish'
    );

    wp_update_post( $wpa_args );

    add_action('save_post', array( __CLASS__, 'save_post' ) );

    update_post_meta( $post_id, '_achievement_woo_order_limit', $ach_order_limit );
    update_post_meta( $post_id, '_achievement_rank', $ach_rank );
    update_post_meta( $post_id, '_achievement_type', $ach_type );
    update_post_meta( $post_id, '_achievement_points', $ach_points );
    update_post_meta( $post_id, '_achievement_woo_points', $ach_wcpoints );
    update_post_meta( $post_id, '_achievement_associated_id', $ach_postid );
    update_post_meta( $post_id, '_achievement_occurrences', $ach_occur );
    update_post_meta( $post_id, '_achievement_image', $ach_img );
    update_post_meta( $post_id, '_achievement_ld_first_attempt_only', $ach_first_try );
    update_post_meta( $post_id, '_achievement_wplms_evaluate_limit', $ach_wplms_evaluate );
    update_post_meta( $post_id, '_achievement_associated_title', $ach_ass_title );
    update_post_meta( $post_id, '_achievement_postid', $post_id );

    if ( isset( $ach_trigger_id ) ) {
      update_post_meta( $post_id, '_achievement_trigger_id', $ach_trigger_id );
      update_post_meta( $post_id, '_achievement_trigger_desc', $ach_trigger_desc );
    }

    $activity_code = filter_input( INPUT_POST, 'wpachievements_achievements_activity_code_trigger_id' );
    if ( $activity_code ) {
      update_post_meta( $post_id, '_achievement_activity_code', $activity_code );
    }

    update_post_meta( $post_id, '_achievement_recurring', $ach_recurring );

    if ( $ach_blog_Limit ) {
      update_post_meta( $post_id, '_achievement_blog_limit', $ach_blog_Limit );
    }
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

    if ( $post->post_type != 'wpachievements' ) {
      return;
    }

    $gained_users = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d", '%_user_gained_%', $post_id) );

    if ( $gained_users ) {
      foreach( $gained_users as $user ) {
        $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );

        $ach_ID = get_the_ID();
        $ach_title = get_the_title();
        $ach_desc = get_the_content();
        $ach_data = $ach_title.': '.$ach_desc;
        $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
        $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
        $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );

        WPAchievements_User::handle_points( array(
                  'activity'          => 'wpachievements_achievement_removed',
                  'user_id'           => $user->meta_value,
                  'post_id'           => $post_id,
                  'points'            => -$ach_points,
                  'reference'         => 'wpachievements_removed',
                  'log_entry'         => 'for Achievement Removed: '.$ach_title,
                ) );

        do_action( 'wpachievements_remove_achievement', $user->meta_value, $ach_ID );
        do_action( 'wpachievements_admin_remove_achievement', $user->meta_value, 'wpachievements_removed', $ach_points );

        $wpdb->query( $wpdb->prepare("INSERT INTO ".WPAchievements()->get_table()." (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_points) );

        delete_post_meta( $ach_ID, '_user_gained_'.$user->meta_value );

        $ach_meta = get_user_meta( $user->meta_value, 'wpachievements_got_new_ach', true );

        if ( in_array_r( $ach_title, $ach_meta ) && in_array_r( $ach_desc, $ach_meta ) && in_array_r( $ach_img, $ach_meta ) ) {
          foreach( $ach_meta as $key => $value ) {
            if ( $value["title"] == $ach_title && $value["text"] == $ach_desc && $value["image"] == $ach_img ) { unset($ach_meta[$key]); }
          }

          update_user_meta( $user->meta_value, 'wpachievements_got_new_ach', $ach_meta );
        }

        foreach($userachievements as $key => $value) {
          if ( $value == $post_id ) {
            unset($userachievements[$key]);
          }
        }

        update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );

        $user_ach_count = (int)sizeof($userachievements);
        $user_ach_count = $user_ach_count - 1;
        update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);
      }
    }

    $args = array(
      'meta_query' => array(
        'relation' => 'AND',
        array(
          'key' => '_quest_details',
          'value' => 'wpachievements_achievement',
          'compare' => 'LIKE'
        ),
        array(
          'key' => '_quest_details',
          'value' => $post_id,
          'compare' => 'LIKE'
        )
      )
    );

    $quests = WPAchievements_Query::get_quests( $args );

    if ( $quests ) {
      foreach( $quests as $quest ) {
        $quest_ID = $quest->ID;
        $quest_details = get_post_meta( $quest_ID, '_quest_details', true );

        foreach( $quest_details as $quest_item ) {
          if ( $quest_item['ach_id'] == $post_id ) {
            wp_delete_post($quest_ID);
          }
        }
      }
    }
  }
}

endif;

WPAchievements_Admin_Achievements::init();
