<?php
namespace WpUnitTestHelpers\Exceptions;

class RedirectHaltException extends \Exception {

	public function getRedirect() {
		$message_decoded                    = json_decode( $this->message, true );
		$message_decoded['location_parsed'] = parse_url( $message_decoded['location'] );
		return $message_decoded;
	}
}
