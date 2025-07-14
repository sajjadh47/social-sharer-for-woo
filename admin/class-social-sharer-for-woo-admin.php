<?php
/**
 * This file contains the definition of the Social_Sharer_For_Woo_Admin class, which
 * is used to load the plugin's admin-specific functionality.
 *
 * @package       Social_Sharer_For_Woo
 * @subpackage    Social_Sharer_For_Woo/admin
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Social_Sharer_For_Woo_Admin {
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
	 * The plugin options api wrapper object.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       array $settings_api Holds the plugin options api wrapper class object.
	 */
	private $settings_api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of this plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->settings_api = new Sajjad_Dev_Settings_API();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_styles() {
		$current_screen = get_current_screen();

		// check if current page is plugin settings page.
		if ( 'toplevel_page_social-sharer-for-woo' === $current_screen->id ) {
			wp_enqueue_style( $this->plugin_name, SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . 'admin/css/admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		$current_screen = get_current_screen();

		// check if current page is plugin settings page.
		if ( 'toplevel_page_social-sharer-for-woo' === $current_screen->id ) {
			wp_enqueue_script( $this->plugin_name, SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'SocialSharerForWoo',
				array(
					'ajaxurl'   => admin_url( 'admin-ajax.php' ),
					'pluginurl' => SOCIAL_SHARER_FOR_WOO_PLUGIN_URL,
				)
			);
		}
	}

	/**
	 * Adds a settings link to the plugin's action links on the plugin list table.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $links The existing array of plugin action links.
	 * @return    array $links The updated array of plugin action links, including the settings link.
	 */
	public function add_plugin_action_links( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=social-sharer-for-woo' ) ), __( 'Settings', 'social-sharer-for-woo' ) );

		return $links;
	}

	/**
	 * Adds the plugin settings page to the WordPress dashboard menu.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Social Sharer For WooComerce', 'social-sharer-for-woo' ),
			__( 'Social Sharer', 'social-sharer-for-woo' ),
			'manage_options',
			'social-sharer-for-woo',
			array( $this, 'menu_page' ),
			'dashicons-share'
		);
	}

	/**
	 * Renders the plugin menu page content.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function menu_page() {
		$this->settings_api->show_forms();
	}

	/**
	 * Initializes admin-specific functionality.
	 *
	 * This function is hooked to the 'admin_init' action and is used to perform
	 * various administrative tasks, such as registering settings, enqueuing scripts,
	 * or adding admin notices.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_init() {
		// set the settings.
		$this->settings_api->set_sections( $this->get_settings_sections() );

		$this->settings_api->set_fields( $this->get_settings_fields() );

		// initialize settings.
		$this->settings_api->admin_init();
	}

	/**
	 * Returns the settings sections for the plugin settings page.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array An array of settings sections, where each section is an array
	 *                  with 'id' and 'title' keys.
	 */
	public function get_settings_sections() {
		$settings_sections = array(
			array(
				'id'    => 'ssfwc_settings',
				'title' => __( 'General Settings', 'social-sharer-for-woo' ),
			),
		);

		/**
		 * Filters the plugin settings sections.
		 *
		 * This filter allows you to modify the plugin settings sections.
		 * You can use this filter to add/remove/edit any settings sections.
		 *
		 * @since     2.0.0
		 * @param     array $settings_sections Default settings sections.
		 * @return    array $settings_sections Modified settings sections.
		 */
		return apply_filters( 'ssfwoo_settings_sections', $settings_sections );
	}

	/**
	 * Returns all the settings fields for the plugin settings page.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array An array of settings fields, organized by section ID.  Each
	 *                  section ID is a key in the array, and the value is an array
	 *                  of settings fields for that section. Each settings field is
	 *                  an array with 'name', 'label', 'type', 'desc', and other keys
	 *                  depending on the field type.
	 */
	public function get_settings_fields() {
		$ssfwc_buttons_style_fields = array(
			'rounded' => SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . '/admin/images/rounded.png',
			'square'  => SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . '/admin/images/square.png',
			'circle'  => SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . '/admin/images/circle.png',
		);

		$ssfwc_buttons_list_fields = array(
			'facebook'           => 'Facebook',
			'mastodon'           => 'Mastodon',
			'bluesky'            => 'Bluesky',
			'whatsapp'           => 'WhatsApp',
			'email'              => 'Email',
			'pinterest'          => 'Pinterest',
			'telegram'           => 'Telegram',
			'sms'                => 'Message',
			'facebook_messenger' => 'Messenger',
			'reddit'             => 'Reddit',
			'google_gmail'       => 'Gmail',
			'pocket'             => 'Pocket',
			'threads'            => 'Threads',
			'linkedin'           => 'LinkedIn',
			'microsoft_teams'    => 'Teams',
			'mix'                => 'Mix',
			'google_translate'   => 'Google Translate',
			'amazon_wish_list'   => 'Amazon Wish List',
			'aol_mail'           => 'AOL Mail',
			'balatarin'          => 'Balatarin',
			'bibsonomy'          => 'BibSonomy',
			'blogger'            => 'Blogger',
			'blogmarks'          => 'BlogMarks',
			'bookmarks_fr'       => 'Bookmarks.fr',
			'box_net'            => 'Box.net',
			'buffer'             => 'Buffer',
			'copy_link'          => 'Copy Link',
			'diary_ru'           => 'Diary.Ru',
			'diaspora'           => 'Diaspora',
			'digg'               => 'Digg',
			'diigo'              => 'Diigo',
			'douban'             => 'Douban',
			'draugiem'           => 'Draugiem',
			'evernote'           => 'Evernote',
			'fark'               => 'Fark',
			'flipboard'          => 'Flipboard',
			'folkd'              => 'Folkd',
			'google_classroom'   => 'Google Classroom',
			'hacker_news'        => 'Hacker News',
			'hatena'             => 'Hatena',
			'houzz'              => 'Houzz',
			'instapaper'         => 'Instapaper',
			'kakao'              => 'Kakao',
			'kindle_it'          => 'Push to Kindle',
			'known'              => 'Known',
			'line'               => 'Line',
			'livejournal'        => 'LiveJournal',
			'mail_ru'            => 'Mail.Ru',
			'mendeley'           => 'Mendeley',
			'meneame'            => 'Meneame',
			'mewe'               => 'MeWe',
			'micro_blog'         => 'Micro.blog',
			'mixi'               => 'Mixi',
			'myspace'            => 'MySpace',
			'odnoklassniki'      => 'Odnoklassniki',
			'outlook_com'        => 'Outlook.com',
			'papaly'             => 'Papaly',
			'pinboard'           => 'Pinboard',
			'plurk'              => 'Plurk',
			'print'              => 'Print',
			'printfriendly'      => 'PrintFriendly',
			'pusha'              => 'Pusha',
			'qzone'              => 'Qzone',
			'raindrop_io'        => 'Raindrop.io',
			'rediff'             => 'Rediff MyPage',
			'refind'             => 'Refind',
			'sina_weibo'         => 'Sina Weibo',
			'sitejot'            => 'SiteJot',
			'skype'              => 'Skype',
			'slashdot'           => 'Slashdot',
			'snapchat'           => 'Snapchat',
			'stocktwits'         => 'StockTwits',
			'svejo'              => 'Svejo',
			'symbaloo_bookmarks' => 'Symbaloo Bookmarks',
			'threema'            => 'Threema',
			'trello'             => 'Trello',
			'tumblr'             => 'Tumblr',
			'twiddla'            => 'Twiddla',
			'twitter'            => 'Twitter',
			'typepad_post'       => 'TypePad',
			'viber'              => 'Viber',
			'vk'                 => 'VK',
			'wechat'             => 'WeChat',
			'wordpress'          => 'WordPress',
			'wykop'              => 'Wykop',
			'x'                  => 'X',
			'xing'               => 'XING',
			'yahoo_mail'         => 'Yahoo Mail',
			'yummly'             => 'Yummly',
		);

		// get icons style.
		$icon_style  = Social_Sharer_For_Woo::get_option( 'ssfwc_buttons_icontext_field', 'ssfwc_settings', 'icons_only' );
		$preview_src = SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . 'admin/images/icons_only.png'; // if not icon_style set default to Icons Only.

		// if style is set.
		if ( $icon_style ) {
			$preview_src = SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . "admin/images/$icon_style.png";
		}

		$settings_fields = array(
			'ssfwc_settings' => array(
				array(
					'name'  => 'ssfwc_show_hide_field',
					'label' => __( 'Enable Social Sharing', 'social-sharer-for-woo' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will enable the plugin functionality.', 'social-sharer-for-woo' ),
				),
				array(
					'name'    => 'ssfwc_buttons_style_field',
					'label'   => __( 'Icons Style', 'social-sharer-for-woo' ),
					'type'    => 'radio_image',
					'options' => $ssfwc_buttons_style_fields,
				),
				array(
					'name'    => 'ssfwc_buttons_position_field',
					'label'   => __( 'Buttons Position In The Page', 'social-sharer-for-woo' ),
					'type'    => 'select',
					'options' => array(
						'55' => __( 'Default', 'social-sharer-for-woo' ),
						'10' => __( 'After Product Image', 'social-sharer-for-woo' ),
						'8'  => __( 'After Product Title', 'social-sharer-for-woo' ),
						'3'  => __( 'Before Product Title', 'social-sharer-for-woo' ),
						'25' => __( 'After Short Description', 'social-sharer-for-woo' ),
						'35' => __( 'After Add To Cart Button', 'social-sharer-for-woo' ),
						'5'  => __( 'Before Tab Information', 'social-sharer-for-woo' ),
					),
				),
				array(
					'name'    => 'ssfwc_buttons_list_field',
					'label'   => __( 'Social Buttons To Show', 'social-sharer-for-woo' ),
					'type'    => 'multiselect',
					'options' => $ssfwc_buttons_list_fields,
				),
				array(
					'name'    => 'ssfwc_buttons_icontext_field',
					'label'   => __( 'Icons Content', 'social-sharer-for-woo' ),
					'type'    => 'select',
					'options' => array(
						'icons_only' => __( 'Icons Only', 'social-sharer-for-woo' ),
						'text_icons' => __( 'Icons With Name', 'social-sharer-for-woo' ),
					),
					'default' => 'icons_only',
					'desc'    => sprintf( '<div class="icons-style-preview" style="background-image: url(%s);"></div>', $preview_src ),
				),
				array(
					'name'        => 'ssfwc_text_before_buttons',
					'label'       => __( 'Text Before Buttons', 'social-sharer-for-woo' ),
					'type'        => 'text',
					'placeholder' => __( 'Share the product', 'social-sharer-for-woo' ),
					'desc'        => __( 'Optional text to display above the social share buttons on the product page.', 'social-sharer-for-woo' ),
				),
				array(
					'name'  => 'ssfwc_buttons_shortcode',
					'label' => __( 'Shortcode', 'social-sharer-for-woo' ),
					'type'  => 'html',
					'desc'  => '<code>[ssfwc_social_sharing_buttons]</code>',
				),
			),
		);

		/**
		 * Filters the plugin settings fields.
		 *
		 * This filter allows you to modify the plugin settings fields.
		 * You can use this filter to add/remove/edit any settings field.
		 *
		 * @since     2.0.0
		 * @param     array $settings_fields Default settings fields.
		 * @return    array $settings_fields Modified settings fields.
		 */
		return apply_filters( 'ssfwoo_settings_fields', $settings_fields );
	}

	/**
	 * Displays admin notices in the admin area.
	 *
	 * This function checks if the required plugin is active.
	 * If not, it displays a warning notice and deactivates the current plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_notices() {
		// Check if required plugin is active.
		if ( ! class_exists( 'WooCommerce', false ) ) {
			sprintf(
				'<div class="notice notice-warning is-dismissible"><p>%s <a href="%s">%s</a> %s</p></div>',
				__( 'Social Sharer For WooComerce requires', 'social-sharer-for-woo' ),
				esc_url( 'https://wordpress.org/plugins/woocommerce/' ),
				__( 'WooCommerce', 'social-sharer-for-woo' ),
				__( 'plugin to be active!', 'social-sharer-for-woo' ),
			);

			// Deactivate the plugin.
			deactivate_plugins( SOCIAL_SHARER_FOR_WOO_PLUGIN_BASENAME );
		}
	}

	/**
	 * Registers the Social Sharing Buttons widget.
	 *
	 * This function registers the `Social_Sharer_For_Woo_Widget` widget, making it available for
	 * use in WordPress sidebars.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function register_widget() {
		register_widget( 'Social_Sharer_For_Woo_Widget' );
	}

	/**
	 * Declares compatibility with WooCommerce's custom order tables feature.
	 *
	 * This function is hooked into the `before_woocommerce_init` action and checks
	 * if the `FeaturesUtil` class exists in the `Automattic\WooCommerce\Utilities`
	 * namespace. If it does, it declares compatibility with the 'custom_order_tables'
	 * feature. This is important for ensuring the plugin works correctly with
	 * WooCommerce versions that support this feature.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function declare_compatibility_with_wc_custom_order_tables() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
}
