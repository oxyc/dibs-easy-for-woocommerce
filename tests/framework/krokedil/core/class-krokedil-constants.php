<?php


/**
 * KrokedilConstants represents unique place for constants
 *
 * @author Krokedil
 */
interface KrokedilConstants {

	const PLUGIN_BASENAME = '/app/public/wp-content/plugins/';
	const DS              = DIRECTORY_SEPARATOR;
	const SUPER_ADMIN     = 'super admin';
	const ADMINISTRATOR   = 'administrator';
	const EDITOR          = 'editor';
	const AUTHOR          = 'author';
	const CONTRIBUTOR     = 'contributor';
	const SUBSCRIBER      = 'subscriber';
	const SHOP_MANAGER    = 'shop_manager';
	// const SALESMAN        = 'salesman';
	const CUSTOMER = 'customer';


	const DEFAULT_WP_ROLES = array(
		self::SUPER_ADMIN,
		self::ADMINISTRATOR,
		self::EDITOR,
		self::AUTHOR,
		self::CONTRIBUTOR,
		self::SUBSCRIBER,
		self::SHOP_MANAGER,
		// self::SALESMAN,
		self::CUSTOMER,
	);
}
