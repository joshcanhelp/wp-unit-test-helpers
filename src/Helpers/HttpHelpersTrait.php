<?php
/**
 * Contains Trait HttpHelpersTrait.
 *
 * @package WpUnitTestHelpers
 *
 * @since   0.9.0
 */

namespace WpUnitTestHelpers\Helpers;

use WP_Error;
use WpUnitTestHelpers\Exceptions\HttpHaltException;

/**
 * Trait HttpHelpersTrait.
 */
trait HttpHelpersTrait {

	/**
	 * Mocked HTTP response(s) to return.
	 *
	 * @var array
	 */
	protected $http_request_type = array();

	/**
	 * Start halting all HTTP requests.
	 * Use this at the top of tests that should check HTTP requests.
	 */
	public function startHttpHalting() {
		add_filter( 'pre_http_request', array( $this, 'httpHalt' ), 1, 3 );
	}

	/**
	 * Halt all HTTP requests with request data serialized in the error message.
	 *
	 * @param false|array $preempt - Original preempt value.
	 * @param array       $args - HTTP request arguments.
	 * @param string      $url - The request URL.
	 *
	 * @throws \Exception - Always.
	 */
	public function httpHalt( $preempt, $args, $url ) {
		throw new HttpHaltException(
			json_encode(
				array(
					'url'     => $url,
					'method'  => $args['method'],
					'headers' => $args['headers'],
					'body'    => json_decode( $args['body'], true ),
					'preempt' => $preempt,
				)
			)
		);
	}

	/**
	 * Stop halting HTTP requests.
	 * Use this in a tearDown() method in the test suite.
	 */
	public function stopHttpHalting() {
		remove_filter( 'pre_http_request', array( $this, 'httpHalt' ), 1 );
	}

	/**
	 * Start mocking all HTTP requests.
	 * Use this at the top of tests that should test behavior for different HTTP responses.
	 */
	public function startHttpMocking() {
		add_filter( 'pre_http_request', array( $this, 'httpMock' ), 1, 3 );
	}

	public function setHttpMockResponse( ?int $code, string $body = '' ) {
		$this->http_request_type[] = is_null( $code )
			? new WP_Error( '__test_wp_error_message__' )
			: array(
				'response' => array( 'code' => $code ),
				'body'     => $body,
			);
	}

	public function httpMock() {
		return array_shift( $this->http_request_type );
	}

	/**
	 * Stop mocking API calls.
	 * Use this in a tearDown() method in the test suite.
	 */
	public function stopHttpMocking() {
		remove_filter( 'pre_http_request', array( $this, 'httpMock' ), 1 );
	}
}
