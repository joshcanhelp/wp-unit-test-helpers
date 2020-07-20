<?php
/**
 * Contains Trait WpDieHelper.
 *
 * @package WpUnitTestHelpers
 *
 * @since   0.9.0
 */

namespace WpUnitTestHelpers\Helpers;

use WpUnitTestHelpers\Exceptions\WpDieHaltException;

/**
 * Trait WpDieHelper.
 */
trait WpDieHelper {

	/**
	 * Start halting all wp_die calls.
	 * Use this at the top of tests that should check HTTP requests.
	 */
	public function startWpDieHalting() {
		add_filter( 'wp_die_handler', array( $this, 'wpDieHandler' ) );
	}

	/**
	 * Provide the function to handle wp_die.
	 *
	 * @return array
	 */
	public function wpDieHandler() {
		return array( $this, 'haltWpDie' );
	}

  /**
   * Handle wp_die().
   *
   * @param string $message - Message to display
   * @param string $title - Page title
   * @param int|array $args - Response code or array of options.
   *
   * @throws \Exception
   */
	public function haltWpDie( $message, $title, $args ) {
    throw new WpDieHaltException( wp_json_encode( [
      'message' => $message,
      'title'   => $title,
      'args'    => $args,
    ] ) );
  }

	/**
	 * Stop halting wp_die.
	 */
	public function stopWpDieHalting() {
		remove_filter( 'wp_die_handler', array( $this, 'wpDieHandler' ) );
	}
}
