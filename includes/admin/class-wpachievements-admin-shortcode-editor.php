<?php
/**
 * Shortcode Editor
 */

if( !defined( 'ABSPATH' ) ) {
  // Exit if accessed directly
  exit;
}

class WPAchievements_Admin_Shortcode_Editor {

  /**
   * Init required hooks
   */
  public static function init() {
    add_action( 'admin_init', array( __CLASS__, 'init_shortcode_editor' ) );
  }

  /**
   * Initialise required hooks for the shortcode editor
   *
   * @static
   * @access  public
   */
  public static function init_shortcode_editor() {
    global $pagenow;

    if ( 'customize.php' == $pagenow ) {
      // Skip on customizer
      return;
    }

    // Skip if option disabled
    if ( 'no' == wpachievements_get_site_option( 'wpachievements_shortcode_editor', 'yes' ) ) {
      return;
    }

    add_action( 'media_buttons', array( __CLASS__, 'add_button' ) );
    add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );
    add_action( 'admin_footer', array( __CLASS__, 'modal_box' ) );
    // Extend admin fields
    add_action( 'wpachievements_admin_field_custom_achievement_select', array( __CLASS__, 'custom_achievement_select' ) );
    add_action( 'wpachievements_admin_field_achievement_select', array( __CLASS__, 'achievement_select' ) );
    add_action( 'wpachievements_admin_field_rank_select', array( __CLASS__, 'rank_select' ) );
    add_action( 'wpachievements_admin_field_quest_select', array( __CLASS__, 'quest_select' ) );
  }

  /**
   * Add a shortcode edit button
   */
  public static function add_button() {
    echo '<a id="wpachievements_shortcode_editor" href="#TB_inline?width=750&height=800&inlineId=select_wpachievements_shortcode" class="thickbox button wpachievements_media_link" data-width="800">' . __( 'WPAchievements', 'wpachievements' ) . '</a>';
  }

  public static function admin_scripts( $hook ) {

    if ( ! self::is_supported_page($hook) ) {
      return;
    }

    wp_enqueue_script( 'wpachievements-shortcde-editor', WPAchievements()->plugin_url() . '/assets/js/shortcode-editor.js', array('jquery'), '', true );
  }

  private static function is_supported_page( $hook ) {
    global $post_type;

		if ( in_array( $hook, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) {
      if ( post_type_supports( $post_type, 'editor' ) ) {
        return true;
      }
    }

    return false;
  }

  /**
   * Display the shortcode modal box content
   */
  public static function modal_box( $hook ) {
    if ( ! self::is_supported_page($hook) ) {
      //return;
    }

    $shortcode_parameters = WPAchievements_Shortcodes::get_parameters();
    ?>
    <div id="select_wpachievements_shortcode" style="display:none">
			<div class="wrap">
				<h3><?php _e( 'Insert a WPAchievements shortcode', 'wpachievements' ); ?></h3>
        <form method="post" id="wpachievements-shortcode-editor-form" action="" enctype="multipart/form-data">
          <div class="alignleft">
            <select id="wpa_select_shortcode">
              <?php
              foreach ( $shortcode_parameters as $shortcode => $data ) {
                printf( '<option value="%1$s">%2$s</option>', $shortcode, $data['title'] );
              }
              ?>
            </select>
          </div>
          <div class="alignright">
            <a id="wpachievements_insert" class="button-primary" href="#" style="color:#fff;"><?php esc_attr_e( 'Insert Shortcode', 'wpachievements' ); ?></a>
            <a id="wpachievements_cancel" class="button-secondary" href="#"><?php esc_attr_e( 'Cancel', 'wpachievements' ); ?></a>
          </div>
          <div id="shortcode_options" class="alignleft clear">
            <?php foreach ( $shortcode_parameters as $shortcode => $data ) : ?>
              <div class="wpa-shortcode-section alignleft" id="<?php echo $shortcode; ?>_wrapper">
                <p><strong>[<?php echo $shortcode; ?>]</strong> - <?php echo $data['description']; ?></p>
                <?php if ( ! empty( $data['fields'] ) ) : ?>
                <table class="form-table">
                  <?php WPAchievements_Admin_Settings::output_fields( $data['fields'] ); ?>
                </table>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </form>
			</div>
		</div>
    <?php
  }

  /**
   * Create a drop down for custom achievements
   *
   * @static
   * @access  public
   * @param   array $value
   * @return  void
   */
  public static function custom_achievement_select( $value ) {
    self::generate_select_output( $value, WPAchievements_Query::get_custom_achievements(), 'custom_achievement' );
  }

  /**
   * Create a drop down for achievements
   *
   * @static
   * @access  public
   * @param   array $value
   * @return  void
   */
  public static function achievement_select( $value ) {
    self::generate_select_output( $value, WPAchievements_Query::get_achievements(), 'achievement' );
  }

  /**
   * Generate the select dropdown for achievements
   *
   * @static
   * @access  public
   * @param   array $value [description]
   * @param   array  $data  [description]
   * @param   string $what  [description]
   * @return  void
   */
  public static function generate_select_output( $value, $data = array(), $what = 'achievement' ) {

    $defaults = array(
      'id'      => '',
      'title'   => '',
      'class'   => '',
      'css'     => '',
      'default' => '',
      'desc'    => '',
    );

    $value = wp_parse_args( $value, $defaults );

    ?>
    <tr valign="top" class="single_select_achievement">
      <th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?></th>
      <td class="forminp">
        <?php
        if ( $data ) {
          ?>
          <select name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" class="<?php echo esc_attr( $value['class'] ); ?>">
            <?php
            foreach( $data as $item ) {
              $value_id = ( 'custom_achievement' == $what) ? get_post_meta( $item->ID, '_achievement_trigger_id', true ) : $item->ID;
              ?>
              <option value="<?php echo $value_id; ?>"><?php echo $item->post_title; ?></option>
              <?php
            }
            ?>
          </select> <?php echo $value['desc']; ?>
          <?php
        }
        else {
          _e( "Nothing found.", 'wpachievements' );
        }
        ?>
      </td>
    </tr>
    <?php
  }

  /**
   * Display a rank selection
   *
   * @static
   * @access  public
   * @param   array $value
   * @return  void
   */
  public static function rank_select( $value ) {

    $defaults = array(
      'id'      => '',
      'title'   => '',
      'class'   => '',
      'css'     => '',
      'default' => '',
      'desc'    => '',
    );

    $value = wp_parse_args( $value, $defaults );

    $ranks = (array) wpachievements_get_site_option( 'wpachievements_ranks_data' );
    ksort($ranks);
    $ranks = array_reverse($ranks, 1);
    ?>
    <tr valign="top" class="single_select_rank">
      <th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?></th>
      <td class="forminp">
        <select name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" class="<?php echo esc_attr( $value['class'] ); ?>">
          <?php
          foreach( $ranks as $points => $rank_data ) {
            if ( is_array( $rank_data ) ) {
              $rank = $rank_data[0];
            }
            else {
              $rank = $rank_data;
            }
            ?>
            <option value="<?php echo $rank ?>"><?php echo $rank; ?></option>
            <?php
          }
          ?>
        </select> <?php echo $value['desc']; ?>
      </td>
    </tr>
    <?php
  }

  /**
   * Display a quest selection
   *
   * @static
   * @access  public
   * @param   array $value
   * @return  void
   */
  public static function quest_select( $value ) {
    $defaults = array(
      'id'      => '',
      'title'   => '',
      'class'   => '',
      'css'     => '',
      'default' => '',
      'desc'    => '',
    );

    $value = wp_parse_args( $value, $defaults );
    ?>
    <tr valign="top" class="single_select_quest">
      <th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?></th>
      <td class="forminp">
        <select name="<?php echo esc_attr( $value['id'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" class="<?php echo esc_attr( $value['class'] ); ?>">
          <?php echo wpa_quest_list(); ?>
        </select> <?php echo $value['desc']; ?>
      </td>
    </tr>
    <?php
  }
}

WPAchievements_Admin_Shortcode_Editor::init();