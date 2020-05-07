<?php

abstract class Krokedil_Unit_Test_Case extends WP_UnitTestCase implements KrokedilConstants {
	protected $isMultiSite = false;
	/**
	 * Class constructor.
	 * Initialize required plugins
	 *
	 * @see WP_UnitTestCase
	 */
	public function __construct() {
		$this->activate_plugins();
		parent::__construct();
	}

	public $plugins = array(
		// key is a dir and a value is filename.php
		'woocommerce' => 'woocommerce.php',
	);

	/**
	 * Activate plugins
	 *
	 * @return void
	 */
	protected function activate_plugins() {
		foreach ( $this->plugins as $dir => $plugin_file ) {
			// if plugin is not already active, activate it then
			if ( ! is_plugin_active( self::DS . $dir . self::DS . $plugin_file ) ) {
				// activate plugin
				do_action( 'activate_' . self::PLUGIN_BASENAME . self::DS . $dir . self::DS . $plugin_file );
			}
		}
	}

	/**
	 * For instance.
	 * $attributes = array(
	 *   'user_login'   => 'wp_master',
	 *   'user_pass'    => '$P$BuWBsZJAjNdqBuaQ3XRR1k0o8GIoGJ1',
	 *   'user_email'   => 'wp_master@example.com',
	 *   'display_name' => 'Web user',
	 *   'user_status'  => 0,
	 *
	 * );
	 *
	 * @param array $attributes
	 * @return void
	 */
	public function create_user( $role = null, array $attributes = null ) {
		$user = null; // wp_user
		if ( empty( $attributes ) ) {
			$user = $this->factory()->user->create_and_get();
		} else {
			$user = $this->factory()->create( $attributes );
		}
		if ( ! empty( $role ) && in_array( $role, self::DEFAULT_WP_ROLES ) ) {
			$user->set_role( $role );
		}

		return $user;

	}

	/**
	 * Undocumented function
	 *
	 * @param array $data
	 * @return void
	 */
	public function createPost( array $data ) {
		$this->factory->users->create( $data );
	}

	/**
	 * Allows to add plugin on the fly
	 *
	 * @param string $plugin_dir plugin directory.
	 * @param string $plugin_file_name name of plugin file.
	 * @return void
	 */
	protected function add_plugin( $plugin_dir, $plugin_file_name ) {
		if ( ! array_key_exists( $plugin_dir, $this->plugins ) ) {
			$this->plugins[ $plugin_dir ] = $plugin_file_name;
		}
	}
}
