<?php
/**
 * Contains Trait HookHelpersTrait.
 *
 * @package WpUnitTestHelpers
 *
 * @since   0.9.0
 */

namespace WpUnitTestHelpers\Helpers;

/**
 * Trait HookHelpersTrait.
 */
trait HookHelpersTrait {

	/**
	 * Get all hooked functions from a hook name.
	 *
	 * @param string $hook - Hook to check.
	 *
	 * @return array
	 */
	public function get_hook( $hook = '' ) {
		global $wp_filter;

		if ( isset( $wp_filter[ $hook ]->callbacks ) ) {
			array_walk(
				$wp_filter[ $hook ]->callbacks,
				function( $callbacks, $priority ) use ( &$hooks ) {
					foreach ( $callbacks as $id => $callback ) {
						$hooks[] = array_merge(
							array(
								'id'       => $id,
								'priority' => $priority,
							),
							$callback
						);
					}
				}
			);
		} else {
			return array();
		}

		return $hooks;
	}

	/**
	 * Remove all hooked functions from a hook.
	 *
	 * @param string $hook - Hook to clear.
	 */
	public function clear_hooks( $hook = '' ) {
		global $wp_filter;
		unset( $wp_filter[ $hook ] );
	}

	/**
	 * Assert that hooked functions exists with the correct priority and arg numbers.
	 *
	 * @param string $hook_name - Hook name in WP.
	 * @param array  $hooked - Array of functions to check.
	 *
	 * @return void
	 */
	public function assertHooked( $hook_name, array $hooked ) {
		$hooks = $this->get_hook( $hook_name );
		$found = 0;

		foreach ( $hooks as $hook ) {
			if ( is_array( $hook['function'] ) ) {
				continue;
			}

			$function_name = $hook['function'];

			if ( ! empty( $hooked[ $function_name ] ) ) {
				$this->assertEquals( $hooked[ $function_name ]['priority'], $hook['priority'] );
				$this->assertEquals( $hooked[ $function_name ]['accepted_args'], $hook['accepted_args'] );
				$found++;
			}
		}
		$this->assertEquals( count( $hooked ), $found );
	}
}
