<?php
/**
 * Class/File Autoloader
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class WPAchievements_Autoloader {

  /**
   * Project prefix
   * @var string
   */
  private $slug = 'wpachievements_';

  /**
   * Path to the includes directory
   * @var string
   */
  private $include_path = '';

  /**
   * The Constructor
   *
   * @access  public
   * @return  void
   */
  public function __construct() {

    if ( function_exists( "__autoload" ) ) {
      spl_autoload_register( "__autoload" );
    }

    spl_autoload_register( array( $this, 'autoload' ) );

    $this->include_path = untrailingslashit( WPACHIEVEMENTS_PATH ) . '/includes/';
  }

  /**
   * Take a class name and turn it into a file name
   *
   * @access  private
   * @param   string $class
   * @return  string
   */
  private function get_file_name_from_class( $class ) {
    return 'class-' . str_replace( '_', '-', $class ) . '.php';
  }

  /**
   * Include a class file
   *
   * @access  private
   * @param   string $path
   * @return  bool successful or not
   */
  private function load_file( $path ) {

    if ( $path && is_readable( $path ) ) {
      include_once( $path );
      return true;
    }
    else {
      return false;
    }
  }

  /**
   * Auto-load classes on demand to reduce memory consumption.
   *
   * @access  public
   * @param   string $class Class name
   * @return  void
   */
  public function autoload( $class ) {

    $class = strtolower( $class );

    if ( strpos( $class, $this->slug ) === false ) {
      return;
    }

    $file  = $this->get_file_name_from_class( $class );
    $path = '';

    if ( strpos( $class, $this->slug . 'admin' ) === 0 ) {
      $path = $this->include_path . 'admin/';
    }

    if ( empty( $path ) || ( ! $this->load_file( $path . $file ) && strpos( $class, $this->slug ) === 0 ) ) {
      $this->load_file( $this->include_path . $file );
    }
  }
}

new WPAchievements_Autoloader();