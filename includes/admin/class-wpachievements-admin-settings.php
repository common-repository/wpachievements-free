<?php
/**
 * Admin Settings Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPAchievements_Admin_Settings' ) ) :

/**
 * Mapti_Admin_Settings
 */
class WPAchievements_Admin_Settings {

  private static $slug  = 'wpachievements';

  private static $settings = array();
  private static $errors   = array();
  private static $messages = array();
  /**
   * @var The single instance
   */
  protected static $_instance = null;
  /**
   * Main Instance
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return Main instance
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  /**
   * Include the settings page classes
   *
   * @version 1.0.0
   * @since   1.0.0
   * @access public
   * @return array Settings
   */
  public static function get_settings_pages() {
    if ( empty( self::$settings ) ){
      $settings = array();

      include_once( 'settings/class-'.self::$slug.'-settings-page.php' );

      $settings[] = include( 'settings/class-'.self::$slug.'-settings-general.php' );
      $settings[] = include( 'settings/class-'.self::$slug.'-settings-modules.php' );

      self::$settings = apply_filters( self::$slug.'_get_settings_pages', $settings );
    }

    return self::$settings;
  }

  /**
   * Save settings
   *
   * @version 1.0.0
   * @since   1.0.0
   * @access  public
   * @return  void
   */
  public static function save() {
    global $current_tab;

    if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], self::$slug.'-settings' ) ) {
      wp_die( __( 'Action failed. Please refresh the page and retry.', self::$slug ) );
    }

    // Trigger actions
    do_action( self::$slug.'_settings_save_' . $current_tab );
    do_action( self::$slug.'_update_options_' . $current_tab );
    do_action( self::$slug.'_update_options' );

    self::add_message( __( 'Your settings have been saved.', self::$slug ) );

    flush_rewrite_rules();

    do_action( self::$slug.'_settings_saved' );
  }

  /**
   * Add a message
   * @version 1.0.0
   * @since   1.0.0
   * @access  public
   * @param string $text
   */
  public static function add_message( $text ) {
    self::$messages[] = $text;
  }

  /**
   * Add an error
   * @version 1.0.0
   * @since   1.0.0
   * @access  public
   * @param string $text
   */
  public static function add_error( $text ) {
    self::$errors[] = $text;
  }

  /**
   * Output messages + errors
   * @version 1.0.0
   * @since   1.0.0
   * @access  public
   * @return  void
   */
  public static function show_messages() {
    if ( sizeof( self::$errors ) > 0 ) {
      foreach ( self::$errors as $error ) {
        echo '<div id="message" class="error fade"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
      }
    }
    elseif ( sizeof( self::$messages ) > 0 ) {
      foreach ( self::$messages as $message ) {
        echo '<div id="message" class="updated fade"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
      }
    }
  }

  /**
   * Settings page.
   *
   * Handles the display of the main settings page in admin.
   *
   * @version 1.0.0
   * @since   1.0.0
   * @access  public
   * @return  void
   */
  public static function output() {
    global $current_section, $current_tab;

    // Project dependent vars
    //$plugin_url = MyArcadeStats()->plugin_url();
    //$version    = MyArcadeStats()->version;

    do_action( self::$slug.'_settings_start' );

    //wp_enqueue_script( self::$slug.'_settings', $plugin_url . '/assets/js/admin/settings.js', array( 'jquery' ), $version, true );
    wp_enqueue_script( 'admin_settings', WPACHIEVEMENTS_URL . '/assets/js/admin-settings.js', array( 'jquery', 'iris' ) );

    // Include settings pages
    self::get_settings_pages();

    // Get current tab/section
    $current_tab      = sanitize_title( filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'general' ) ) ) );
    $current_section  = empty( $_REQUEST['section'] ) ? '' : sanitize_title( $_REQUEST['section'] );

    // Save settings if data has been posted
    if ( ! empty( $_POST ) ) {
      self::save();
    }

    self::show_messages();

    // Get tabs for the settings page
    $tabs = apply_filters( self::$slug.'_settings_tabs_array', array() );

    include 'views/html-admin-settings.php';
  }

  /**
   * Get a setting from the settings API.
   *
   * @version 1.0.0
   * @since   1.0.0
   * @access  public
   * @param   mixed $option
   * @return  string
   */
  public static function get_option( $option_name, $default = '' ) {
    // Array value
    if ( strstr( $option_name, '[' ) ) {

      parse_str( $option_name, $option_array );

      // Option name is first key
      $option_name = current( array_keys( $option_array ) );

      // Get value
      $option_values = get_option( $option_name, '' );

      $key = key( $option_array[ $option_name ] );

      $option_value = ( isset( $option_values[ $key ] ) ) ? $option_values[ $key ] : null;

    // Single value
    }
    else {
      $option_value = get_option( $option_name, null );
    }

    if ( is_array( $option_value ) ) {
      $option_value = array_map( 'stripslashes', $option_value );
    } elseif ( ! is_null( $option_value ) ) {
      $option_value = stripslashes( $option_value );
    }

    return $option_value === null ? $default : $option_value;
  }

  /**
   * Output admin fields.
   *
   * Loops though the MyArcadePlugin options array and outputs each field.
   *
   * @version 1.0.0
   * @since   1.0.0
   * @access  public
   * @param   array $options Opens array to output
   * @return  void
   */
  public static function output_fields( $options ) {

    // Project dependent vars
    $plugin_url = WPACHIEVEMENTS_URL;

    foreach ( $options as $value ) {
      if ( ! isset( $value['type'] ) ) continue;
      if ( ! isset( $value['id'] ) ) $value['id'] = '';
      if ( ! isset( $value['title'] ) ) $value['title'] = isset( $value['name'] ) ? $value['name'] : '';
      if ( ! isset( $value['class'] ) ) $value['class'] = '';
      if ( ! isset( $value['css'] ) ) $value['css'] = '';
      if ( ! isset( $value['default'] ) ) $value['default'] = '';
      if ( ! isset( $value['desc'] ) ) $value['desc'] = '';
      if ( ! isset( $value['desc_tip'] ) ) $value['desc_tip'] = false;

      // Custom attribute handling
      $custom_attributes = array();

      if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
        foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
          $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
        }
      }

      // Description handling
      if ( $value['desc_tip'] === true ) {
        $description = '';
        $tip = $value['desc'];
      } elseif ( ! empty( $value['desc_tip'] ) ) {
        $description = $value['desc'];
        $tip = $value['desc_tip'];
      } elseif ( ! empty( $value['desc'] ) ) {
        $description = $value['desc'];
        $tip = '';
      } else {
        $description = $tip = '';
      }

      if ( $description && in_array( $value['type'], array( 'textarea', 'radio' ) ) ) {
        $description = '<p style="margin-top:0">' . wp_kses_post( $description ) . '</p>';
      } elseif ( $description && in_array( $value['type'], array( 'checkbox' ) ) ) {
        $description =  wp_kses_post( $description );
      } elseif ( $description ) {
        $description = '<span class="description">' . wp_kses_post( $description ) . '</span>';
      }

      if ( $tip && in_array( $value['type'], array( 'checkbox' ) ) ) {

        $tip = '<p class="description">' . $tip . '</p>';

      } elseif ( $tip ) {

        $tip = '<img class="help_tip" data-tip="' . esc_attr( $tip ) . '" src="' . $plugin_url . '/assets/img/help.png" height="16" width="16" />';

      }

      // Switch based on type
      switch( $value['type'] ) {

        // Section Titles
        case 'title':
          if ( ! empty( $value['title'] ) ) {
            echo '<h3>' . esc_html( $value['title'] ) . '</h3>';
          }
          if ( ! empty( $value['desc'] ) ) {
            echo wpautop( wptexturize( wp_kses_post( $value['desc'] ) ) );
          }
          echo '<table class="form-table">'. "\n\n";
          if ( ! empty( $value['id'] ) ) {
            do_action( self::$slug.'_settings_' . sanitize_title( $value['id'] ) );
          }
        break;

        // Section Ends
        case 'sectionend':
          if ( ! empty( $value['id'] ) ) {
            do_action( self::$slug.'_settings_' . sanitize_title( $value['id'] ) . '_end' );
          }
          echo '</table>';
          if ( ! empty( $value['id'] ) ) {
            do_action( self::$slug.'_settings_' . sanitize_title( $value['id'] ) . '_after' );
          }
        break;

        // Standard text inputs and subtypes like 'number'
        case 'text':
        case 'email':
        case 'number':
        case 'color' :
        case 'password' :

          $type       = $value['type'];
          $option_value   = self::get_option( $value['id'], $value['default'] );

          if ( $value['type'] == 'color' ) {
            $type = 'text';
            $value['class'] .= 'colorpick';
            $description .= '<div id="colorPickerDiv_' . esc_attr( $value['id'] ) . '" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>';
          }

          ?><tr valign="top">
            <th scope="row" class="titledesc">
              <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
              <?php echo $tip; ?>
            </th>
            <td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
              <?php
              if ( 'color' == $value['type'] ) {
                echo '<span class="colorpickpreview" style="background: ' . esc_attr( $option_value ) . ';"></span>';
              }
              ?>
              <input
              name="<?php echo esc_attr( $value['id'] ); ?>"
              id="<?php echo esc_attr( $value['id'] ); ?>"
              type="<?php echo esc_attr( $type ); ?>"
              style="<?php echo esc_attr( $value['css'] ); ?>"
              value="<?php echo esc_attr( $option_value ); ?>"
              class="<?php echo esc_attr( $value['class'] ); ?>"
              <?php echo implode( ' ', $custom_attributes ); ?>
              /> <?php echo $description; ?>
            </td>
          </tr><?php
        break;

        // Textarea
        case 'textarea':

          $option_value   = self::get_option( $value['id'], $value['default'] );

          ?><tr valign="top">
            <th scope="row" class="titledesc">
              <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
              <?php echo $tip; ?>
            </th>
            <td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
              <?php echo $description; ?>

              <textarea
              name="<?php echo esc_attr( $value['id'] ); ?>"
              id="<?php echo esc_attr( $value['id'] ); ?>"
              style="<?php echo esc_attr( $value['css'] ); ?>"
              class="<?php echo esc_attr( $value['class'] ); ?>"
              <?php echo implode( ' ', $custom_attributes ); ?>
              ><?php echo esc_textarea( $option_value );  ?></textarea>
            </td>
          </tr><?php
        break;

        // Select boxes
        case 'select' :
        case 'multiselect' :

          $option_value   = self::get_option( $value['id'], $value['default'] );

          ?><tr valign="top">
            <th scope="row" class="titledesc">
              <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
              <?php echo $tip; ?>
            </th>
            <td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
              <select
              name="<?php echo esc_attr( $value['id'] ); ?><?php if ( $value['type'] == 'multiselect' ) echo '[]'; ?>"
              id="<?php echo esc_attr( $value['id'] ); ?>"
              style="<?php echo esc_attr( $value['css'] ); ?>"
              class="<?php echo esc_attr( $value['class'] ); ?>"
              <?php echo implode( ' ', $custom_attributes ); ?>
              <?php if ( $value['type'] == 'multiselect' ) echo 'multiple="multiple"'; ?>
              >
              <?php
              foreach ( $value['options'] as $key => $val ) {
                ?>
                <option value="<?php echo esc_attr( $key ); ?>" <?php

                  if ( is_array( $option_value ) )
                    selected( in_array( $key, $option_value ), true );
                  else
                    selected( $option_value, $key );

                  ?>><?php echo $val ?></option>
                  <?php
                }
                ?>
              </select> <?php echo $description; ?>
            </td>
          </tr><?php
        break;

        // Radio inputs
        case 'radio' :

          $option_value   = self::get_option( $value['id'], $value['default'] );

          ?><tr valign="top">
            <th scope="row" class="titledesc">
              <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
              <?php echo $tip; ?>
            </th>
            <td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
              <fieldset>
                <?php echo $description; ?>
                <ul>
                  <?php
                  foreach ( $value['options'] as $key => $val ) {
                    ?>
                    <li>
                      <label><input
                        name="<?php echo esc_attr( $value['id'] ); ?>"
                        value="<?php echo $key; ?>"
                        type="radio"
                        style="<?php echo esc_attr( $value['css'] ); ?>"
                        class="<?php echo esc_attr( $value['class'] ); ?>"
                        <?php echo implode( ' ', $custom_attributes ); ?>
                        <?php checked( $key, $option_value ); ?>
                        /> <?php echo $val ?></label>
                    </li>
                    <?php
                  }
                  ?>
                </ul>
              </fieldset>
            </td>
          </tr><?php
        break;

        // Checkbox input
        case 'checkbox' :

          $option_value    = self::get_option( $value['id'], $value['default'] );
          $visbility_class = array();

          if ( ! isset( $value['hide_if_checked'] ) ) {
            $value['hide_if_checked'] = false;
          }
          if ( ! isset( $value['show_if_checked'] ) ) {
            $value['show_if_checked'] = false;
          }
          if ( $value['hide_if_checked'] == 'yes' || $value['show_if_checked'] == 'yes' ) {
            $visbility_class[] = 'hidden_option';
          }
          if ( $value['hide_if_checked'] == 'option' ) {
            $visbility_class[] = 'hide_options_if_checked';
          }
          if ( $value['show_if_checked'] == 'option' ) {
            $visbility_class[] = 'show_options_if_checked';
          }

          if ( ! isset( $value['checkboxgroup'] ) || 'start' == $value['checkboxgroup'] ) {
            ?>
            <tr valign="top" class="<?php echo esc_attr( implode( ' ', $visbility_class ) ); ?>">
              <th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?></th>
              <td class="forminp forminp-checkbox">
                <fieldset>
                  <?php
                } else {
                  ?>
                  <fieldset class="<?php echo esc_attr( implode( ' ', $visbility_class ) ); ?>">
                    <?php
                  }

                  if ( ! empty( $value['title'] ) ) {
                    ?>
                    <legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ) ?></span></legend>
                    <?php
                  }

                  ?>
                  <label for="<?php echo $value['id'] ?>">
                    <input
                    name="<?php echo esc_attr( $value['id'] ); ?>"
                    id="<?php echo esc_attr( $value['id'] ); ?>"
                    type="checkbox"
                    value="1"
                    <?php checked( $option_value, 'yes'); ?>
                    <?php echo implode( ' ', $custom_attributes ); ?>
                    /> <?php echo $description ?>
                  </label> <?php echo $tip; ?>
                  <?php

                  if ( ! isset( $value['checkboxgroup'] ) || 'end' == $value['checkboxgroup'] ) {
                    ?>
                  </fieldset>
                </td>
              </tr>
              <?php
          } else {
            ?>
            </fieldset>
            <?php
          }
        break;

        // Single page selects
        case 'single_select_page' :

          $args = array( 'name'       => $value['id'],
           'id'         => $value['id'],
           'sort_column'    => 'menu_order',
           'sort_order'     => 'ASC',
           'show_option_none'   => __( 'Select a page&hellip;', self::$slug ),
           'option_none_value' => '0',
           'class'        => $value['class'],
           'echo'         => false,
           'selected'     => absint( self::get_option( $value['id'] ) )
           );

          if( isset( $value['args'] ) ) {
            $args = wp_parse_args( $value['args'], $args );
          }

          ?><tr valign="top" class="single_select_page">
            <th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?> <?php echo $tip; ?></th>
            <td class="forminp">
              <?php echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;', self::$slug ) .  "' style='" . $value['css'] . "' class='" . $value['class'] . "' id=", wp_dropdown_pages( $args ) ); ?> <?php echo $description; ?>
            </td>
          </tr><?php
        break;

        // Default: run an action
        default:
          do_action( self::$slug.'_admin_field_' . $value['type'], $value );
        break;
      }
    }
  }

  /**
   * Save admin fields.
   *
   * Loops though the options array and outputs each field.
   *
   * @access public
   * @param array $options Opens array to output
   * @return bool
   */
  public static function save_fields( $options ) {

    if ( empty( $_POST ) ) {
      return false;
    }

    // Options to update will be stored here
    $update_options = array();

    // Loop options and get values to save
    foreach ( $options as $value ) {
      if ( ! isset( $value['id'] ) || ! isset( $value['type'] ) ) {
        continue;
      }

      // Get posted value
      if ( strstr( $value['id'], '[' ) ) {
        parse_str( $value['id'], $option_name_array );

        $option_name  = current( array_keys( $option_name_array ) );
        $setting_name = key( $option_name_array[ $option_name ] );

          $option_value = isset( $_POST[ $option_name ][ $setting_name ] ) ? stripslashes_deep( $_POST[ $option_name ][ $setting_name ] ) : null;
      } else {
        $option_name  = $value['id'];
        $setting_name = '';
        $option_value = isset( $_POST[ $value['id'] ] ) ? stripslashes_deep( $_POST[ $value['id'] ] ) : null;
      }

      // Format value
      switch ( sanitize_title( $value['type'] ) ) {
        case "checkbox" :
          $option_value = is_null( $option_value ) ? 'no' : 'yes';
        break;
        case "textarea" :
          $option_value = wp_kses_post( trim( $option_value ) );
        break;
        case "text" :
        case 'email':
            case 'number':
        case "select" :
        case "color" :
            case 'password' :
        case "single_select_page" :
        case 'radio' :
          $option_value = sanitize_text_field( $option_value );
        break;
        case "multiselect" :
        case "multi_select_countries" :
        $option_value = array_filter( array_map( 'sanitize_text_field', (array) $option_value ) );
        break;
        case "image_width" :
          if ( isset( $option_value['width'] ) ) {
          $update_options[ $value['id'] ]['width']  = sanitize_text_field( $option_value['width'] );
          $update_options[ $value['id'] ]['height'] = sanitize_text_field( $option_value['height'] );
          $update_options[ $value['id'] ]['crop']   = isset( $option_value['crop'] ) ? 1 : 0;
              } else {
          $update_options[ $value['id'] ]['width']  = $value['default']['width'];
          $update_options[ $value['id'] ]['height'] = $value['default']['height'];
          $update_options[ $value['id'] ]['crop']   = $value['default']['crop'];
              }
        break;
        default :
          do_action( self::$slug.'_update_option_' . sanitize_title( $value['type'] ), $value );
        break;
      }

      if ( ! is_null( $option_value ) ) {
        // Check if option is an array
        if ( $option_name && $setting_name ) {
          // Get old option value
          if ( ! isset( $update_options[ $option_name ] ) ) {
            $update_options[ $option_name ] = get_option( $option_name, array() );
          }

          if ( ! is_array( $update_options[ $option_name ] ) ) {
            $update_options[ $option_name ] = array();
          }

          $update_options[ $option_name ][ $setting_name ] = $option_value;

        // Single value
        } else {
          $update_options[ $option_name ] = $option_value;
        }
      }

      // Custom handling
      do_action( self::$slug.'_update_option', $value );
    }

    // Now save the options
    foreach( $update_options as $name => $value ) {
      update_option( $name, $value );
    }

    return true;
  }
} // END Class
endif;

/**
 * Returns the main instance to prevent the need to use globals.
 *
 * @version 1.0.0
 * @since   1.0.0
 * @access  public
 * @return  WPAchievement_Admin_SettingsClass
 */
function WPAchievements_Admin_Settings() {
  return WPAchievements_Admin_Settings::instance();
}

// Create a new instance
WPAchievements_Admin_Settings();
