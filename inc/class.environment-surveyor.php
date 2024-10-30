<?php

abstract class CF7_Mautic_Environment_Surveyor {

	/**
	 * CF7_Mautic_Environment_Surveyor constructor.
	 */
	public function __construct() {

	}

	/**
	 * Register admin notice.
	 */
	public function register_notice() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}


	/**
	 * Check method.
	 *
	 * @return bool|WP_Error
	 */
	abstract function check();

	/**
	 * Display notice on dashboard.
	 */
	public function admin_notices() {
		$result = $this->check();
		if ( is_wp_error( $result ) ) {
			$message = sprintf(
				__( '[CF7 Mautic Extention] %s', 'cf7-mautic-extention' ),
				esc_html( $result->get_error_message() )
			);

			echo sprintf( '<div class="error"><p>%s</p></div>', esc_html( $message ) );
		}
	}
}
