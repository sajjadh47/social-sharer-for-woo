<?php
/**
 * This file contains the definition of the Social_Sharer_For_Woo_Public class, which
 * is used to load the plugin's public-facing functionality.
 *
 * @package       Social_Sharer_For_Woo
 * @subpackage    Social_Sharer_For_Woo/public
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Social_Sharer_For_Woo_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of the plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		/**
		 * Filters the position of the social sharing buttons.
		 *
		 * This filter allows developers to customize where the social sharing
		 * buttons appear on the page. The default value is 55.
		 *
		 * @since    2.0.0
		 * @param    int $social_icons_position The numerical position for the social icons.
		 */
		$social_icons_position = (int) apply_filters(
			'ssfwc_social_icons_position',
			Social_Sharer_For_Woo::get_option( 'ssfwc_buttons_position_field', 'ssfwc_settings', 55 )
		);

		if ( 5 === $social_icons_position ) {
			add_action( 'woocommerce_after_single_product_summary', array( $this, 'show_social_buttons' ), $social_icons_position );
		} elseif ( 10 === $social_icons_position ) {
			add_action( 'woocommerce_product_thumbnails', array( $this, 'show_social_buttons' ), $social_icons_position );
		} else {
			add_action( 'woocommerce_single_product_summary', array( $this, 'show_social_buttons' ), $social_icons_position );
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_styles() {
		wp_register_style( $this->plugin_name, SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . 'public/css/public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		wp_register_script( 'ssfwc-addtoany-script', '//static.addtoany.com/menu/page.js', array( 'jquery' ), $this->version, true );

		wp_register_script( $this->plugin_name, SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . 'public/js/public.js', array( 'ssfwc-addtoany-script' ), $this->version, true );

		wp_localize_script(
			$this->plugin_name,
			'SocialSharerForWoo',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	/**
	 * Adds an 'async' attribute to a specific script tag to load it asynchronously.
	 *
	 * This method is intended to be hooked into WordPress's `script_loader_tag` filter.
	 * It checks if the script handle matches 'ssfwc-addtoany-script' and, if so,
	 * modifies the script tag to include the `async` attribute, improving page load performance.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $tag    The <script> tag for the enqueued script.
	 * @param     string $handle The script's registered handle.
	 * @return    string         The modified (or original) <script> tag.
	 */
	public function add_async_tag_to_script( $tag, $handle ) {
		if ( 'ssfwc-addtoany-script' !== $handle ) {
			return $tag;
		}

		return str_replace( '<script', '<script async', $tag );
	}

	/**
	 * Registers a shortcode to display social sharing buttons.
	 *
	 * This method acts as a shortcode callback. It first checks if the social sharing
	 * feature is enabled via plugin options. If disabled, it returns an empty string.
	 * Otherwise, it captures the output of `self::show_social_buttons()` and returns it,
	 * allowing the social sharing buttons to be displayed wherever the shortcode is used.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The HTML output of the social sharing buttons, or an empty string if disabled.
	 */
	public function add_shortcode() {
		ob_start();

			$this->show_social_buttons();

		return ob_get_clean();
	}

	/**
	 * Displays the social sharing buttons.
	 *
	 * This method is responsible for rendering the social sharing buttons' HTML.
	 * It checks if the feature is enabled. If so, it includes the plugin's public view
	 * file, which contains the actual HTML structure for the buttons.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    void|string Returns void if the feature is enabled and the view file is included.
	 *                        Returns an empty string if the feature is disabled.
	 */
	public function show_social_buttons() {
		$enabled = Social_Sharer_For_Woo::get_option( 'ssfwc_show_hide_field', 'ssfwc_settings', 'off' );

		if ( 'on' !== $enabled ) {
			return '';
		}

		wp_enqueue_style( $this->plugin_name );

		wp_enqueue_script( $this->plugin_name );

		require SOCIAL_SHARER_FOR_WOO_PLUGIN_PATH . '/public/views/plugin-public-display.php';
	}
}
