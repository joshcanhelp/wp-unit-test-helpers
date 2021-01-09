<?php

namespace WpUnitTestHelpers;

/**
 * Class WpTestCaseAbstract.
 * Extend this class to use it as a template for your own test cases.
 */
abstract class WpTestCaseAbstract extends \PHPUnit\Framework\TestCase {

	use Helpers\AjaxHelpers;
	use Helpers\HookHelpers;
	use Helpers\HttpHelpers;
	use Helpers\RedirectHelpers;
	use Helpers\WpDieHelper;
	use Helpers\WpScriptsHelper;

	/**
	 * Runs before test suite starts.
	 * Use this to setup reusable static vars.
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
	}

	/**
	 * Runs before each test method.
	 * Use this to reset the environment for each test.
	 */
	public function setUp() {
		parent::setUp();

		// This helps the DB play nicely in tests.
		global $wpdb;
		$wpdb->suppress_errors = false;
		$wpdb->show_errors     = true;
		$wpdb->db_connect();
		ini_set( 'display_errors', '1' );

		// Reset plugin options?
		// Reset test user data?
		// Clear error log?
		// Delete transients?
		// Reset core WP options?
	}

	/**
	 * Runs after each test method.
	 * Use this to turn off anything started during a test.
	 */
	public function tearDown() {
		parent::tearDown();

		$_GET     = array();
		$_POST    = array();
		$_REQUEST = array();

		if ( method_exists( $this, 'stopAjaxHalting' ) ) {
			$this->stopAjaxHalting();
		}

		if ( method_exists( $this, 'stopAjaxReturn' ) ) {
			$this->stopAjaxReturn();
		}

		if ( method_exists( $this, 'stopHttpHalting' ) ) {
			$this->stopHttpHalting();
		}

		if ( method_exists( $this, 'stopHttpMocking' ) ) {
			$this->stopHttpMocking();
		}

		if ( method_exists( $this, 'stopRedirectHalting' ) ) {
			$this->stopRedirectHalting();
		}

		if ( method_exists( $this, 'stopWpDieHalting' ) ) {
			$this->stopWpDieHalting();
		}
	}

	/**
	 * Runs after test suite finishes.
	 * Use this to reset the test environment, if needed.
	 */
	public static function tearDownAfterClass() {
		parent::tearDownAfterClass();
	}
}
