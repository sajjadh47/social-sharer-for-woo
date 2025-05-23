<?php
/**
 * This file contains the definition of the Social_Sharer_For_Woo_I18n class, which
 * is used to load the plugin's internationalization.
 *
 * @package       Social_Sharer_For_Woo
 * @subpackage    Social_Sharer_For_Woo/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since    2.0.0
 */
class Social_Sharer_For_Woo_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'social-sharer-for-woo',
			false,
			dirname( SOCIAL_SHARER_FOR_WOO_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
