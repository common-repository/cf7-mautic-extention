<?php
/**
 * Plugin Name: CF7 Mautic Extention
 * Version: 0.0.5
 * Description: Simple extention to subscribe Contact Form 7's information to Mautic Form.
 * Author: hideokamoto
 * Author URI: http://wp-kyoto.net/
 * Plugin URI: https://github.com/megumiteam/cf7-mautic/
 * Text Domain: cf7-mautic-extention
 * Support PHP Version: 5.6
 * Required Plugin: contact-form-7
 * Domain Path: /languages
 *
 * @package Cf7-mautic-extention
 */


$cf7_mautic_plugin_info = get_file_data( __FILE__, array(
	'minimum_php' => 'Support PHP Version',
) );

define( 'CF7_MAUTIC_ROOT', __FILE__ );
define( 'CF7_MAUTIC_REQUIRE_PHP_VERSION', $cf7_mautic_plugin_info['minimum_php'] );


require_once 'inc/class.environment-surveyor.php';
require_once 'inc/class.php-surveyor.php';
require_once 'inc/class.cf7-surveyor.php';


/**
 * Initialize.
 */
function cf7_mautic_init() {
	require_once 'inc/class.cf7-mautic.php';
	require_once 'inc/class.admin.php';
	require_once 'inc/class.submit.php';
	$cf7_mautic = CF7_Mautic::get_instance();
	$cf7_mautic->init();
}

/**
 * Check Environments.
 *
 * @return bool
 */
function cf7_mautic_check_environments() {
	$php_checker = new CF7_Mautic_PHP_Surveyor();
	$php_checker->register_notice();

	$cf7_checker = new CF7_Mautic_CF7_Surveyor();
	$cf7_checker->register_notice();

	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$cf7_checker->set_cf7_plugin_basename( WPCF7_PLUGIN );
	}

	if ( ! is_wp_error( $php_checker->check() ) and ! is_wp_error( $cf7_checker->check() ) ) {
		return true;
	}

	return false;
}

/**
 * Bootstrap.
 */
function cf7_mautic_bootstrap() {

	if ( cf7_mautic_check_environments() ) {
		cf7_mautic_init();
	}
}

/**
 * Check on activation.
 */
function cf7_mautic_check_on_activation() {

	if ( ! cf7_mautic_check_environments() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'Opps, CF7 Mautic Extention require PHP 5.6 or higher and contact form 7.' );
	}
}

add_action( 'plugins_loaded', 'cf7_mautic_bootstrap' );

register_activation_hook( __FILE__, 'cf7_mautic_check_on_activation' );
