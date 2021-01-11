<?php
namespace WpUnitTestHelpers\Exceptions;

class HttpHaltException extends \Exception {

	public function getHttpRequest() {
		$message_decoded = json_decode( $this->message, true );

		$message_decoded['url_parsed'] = array();
		if ( $message_decoded['url'] ) {
			$message_decoded['url_parsed'] = parse_url( $message_decoded['url'] );
		}

		$message_decoded['url_queries'] = array();
		if ( ! empty( $message_decoded['url_parsed']['query'] ) ) {
			$message_decoded['url_queries'] = explode( '&', $message_decoded['url_parsed']['query'] );
		}

		return $message_decoded;
	}
}
