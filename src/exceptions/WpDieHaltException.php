<?php
namespace WpUnitTestHelpers\Exceptions;

class WpDieHaltException extends \Exception {

	public function getDecodedMessage() {
		return json_decode( $this->message, TRUE );
	}
}
