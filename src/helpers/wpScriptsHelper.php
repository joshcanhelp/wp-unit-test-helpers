<?php
/**
 * Contains Trait WpScriptsHelper.
 *
 * @package WpUnitTestHelpers
 *
 * @since   0.9.0
 */

namespace WpUnitTestHelpers\Helpers;

/**
 * Trait WpScriptsHelper.
 */
trait WpScriptsHelper {

	public function getScript( $script_name, $var_name = '' ) {
		$scripts = wp_scripts();
		$script  = $scripts->registered[ $script_name ];

		if ( $script && $var_name ) {
			$localization_json = trim(
				str_replace(
					'var ' . $var_name . ' = ',
					'',
					$script->extra['data']
				),
				';'
			);
			$localization      = json_decode( $localization_json, true );
			$script->$var_name = $localization;
		}

		return $script;
	}
}
