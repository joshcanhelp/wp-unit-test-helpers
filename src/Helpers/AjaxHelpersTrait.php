<?php
/**
 * Contains Trait AjaxHelpersTrait.
 *
 * @package WpUnitTestHelpers
 *
 * @since 0.9.0
 */

namespace WpUnitTestHelpers\Helpers;

use WpUnitTestHelpers\Exceptions\AjaxHaltException;

/**
 * Trait AjaxHelpersTrait.
 */
trait AjaxHelpersTrait {

	/**
	 * Set a filter to halt AJAX requests with an exception.
	 * Call at the top of tests that use AJAX handler functions.
	 */
	public function startAjaxHalting() {
		add_filter( 'wp_doing_ajax', '__return_true' );
		add_filter( 'wp_die_ajax_handler', array( $this, 'startAjaxHaltingHook' ) );
	}

	/**
	 * Returns the function used to halt an AJAX request.
	 *
	 * @return array
	 */
	public function startAjaxHaltingHook() {
		return array( $this, 'haltAjax' );
	}

	/**
	 * Stop AJAX requests by throwing an exception.
	 * Hooked to: wp_die_ajax_handler
	 *
	 * @param string|int $message - Message for die page.
	 * @param string     $title - Title for die page, not used.
	 * @param array      $args - Other args.
	 *
	 * @throws AjaxHaltException - Always, to stop AJAX process.
	 */
	public function haltAjax( $message, $title, $args ) {
		$is_bad_nonce = -1 === $message && ! empty( $args['response'] ) && 403 === $args['response'];
		throw new AjaxHaltException( $is_bad_nonce ? 'bad_nonce' : 'die_ajax' );
	}

	/**
	 * Remove the filter that halts AJAX requests.
	 * Call this in a test suites tearDown method.
	 */
	public function stopAjaxHalting() {
		remove_filter( 'wp_doing_ajax', '__return_true' );
		remove_filter( 'wp_die_ajax_handler', array( $this, 'startAjaxHaltingHook' ) );
	}

	/**
	 * Return AJAX request messages.
	 * Call at the top of tests that use AJAX handler functions.
	 */
	public function startAjaxReturn() {
		add_filter( 'wp_doing_ajax', '__return_true' );
		add_filter( 'wp_die_ajax_handler', array( $this, 'startAjaxReturnHook' ) );
	}

	/**
	 * Returns the function used to return an AJAX request message.
	 *
	 * @return array
	 */
	public function startAjaxReturnHook() {
		return array( $this, 'ajaxReturn' );
	}

	/**
	 * Prevent the wp_die page from dying and echo the message passed.
	 * Hooked to: wp_die_handler
	 *
	 * @param string $message - HTML to show on the wp_die page.
	 */
	public function ajaxReturn( $message ) {
		echo $message;
	}

	/**
	 * Remove the filter that returns AJAX messages.
	 * Call this in a test suites tearDown method.
	 */
	public function stopAjaxReturn() {
		remove_filter( 'wp_doing_ajax', '__return_true', 10 );
		remove_filter( 'wp_die_ajax_handler', array( $this, 'startAjaxReturnHook' ), 10 );
	}
}
