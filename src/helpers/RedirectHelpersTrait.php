<?php
/**
 * Contains Trait RedirectHelpers.
 *
 * @package WpUnitTestHelpers
 *
 * @since   0.9.0
 */

namespace WpUnitTestHelpers\Helpers;

use WpUnitTestHelpers\Exceptions\RedirectHaltException;

/**
 * Trait RedirectHelpers.
 */
trait RedirectHelpers {

	/**
	 * Start halting all redirects.
	 * Use this at the top of tests that should check redirects.
	 */
	public function startRedirectHalting() {
		add_filter( 'wp_redirect', array( $this, 'haltRedirect' ), 1, 2 );
	}

	/**
	 * Halt all redirects with request data serialized in the error message.
	 *
	 * @param string  $location - Original redirect URL.
	 * @param integer $status   - HTTP status code.
	 *
	 * @throws RedirectHaltException - Always.
	 */
	public function haltRedirect( $location, $status ) {
		throw new RedirectHaltException(
			json_encode(
				array(
					'location' => $location,
					'status'   => $status,
				)
			)
		);
	}

	/**
	 * Stop halting redirects.
	 * Use this in a tearDown() method in the test suite.
	 */
	public function stopRedirectHalting() {
		remove_filter( 'wp_redirect', array( $this, 'haltRedirect' ), 1 );
	}
}
