<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since         2.0.0
 * @package       Social_Sharer_For_Woo
 * @subpackage    Social_Sharer_For_Woo/public/views
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Filters the display style of the social sharing buttons.
 *
 * This filter allows developers to choose how the social icons are rendered,
 * such as 'icons_only' or showing text alongside the icons.
 *
 * @since    2.0.0
 * @param    string $icon_style The display style for the social icons (e.g., 'icons_only').
 */
$icon_style = apply_filters(
	'ssfwc_social_icon_style',
	Social_Sharer_For_Woo::get_option( 'ssfwc_buttons_icontext_field', 'ssfwc_settings', 'icons_only' )
);

/**
 * Filters the style of the social sharing buttons.
 *
 * This filter allows developers to customize the visual style of the buttons,
 * such as 'square', 'round', or 'circle'.
 *
 * @since    2.0.0
 * @param    string $button_style The style of the social sharing buttons (e.g., 'square').
 */
$button_style = apply_filters(
	'ssfwc_social_buttons_style',
	Social_Sharer_For_Woo::get_option( 'ssfwc_buttons_style_field', 'ssfwc_settings', 'square' )
);

/**
 * Filters the text displayed immediately before the social sharing buttons.
 *
 * This filter allows developers to customize the introductory text that appears
 * just above the social sharing icons.
 *
 * @since 2.0.0
 * @param string $text_before_buttons The text content to display before the buttons. Default is an empty string.
 */
$text_before_buttons = apply_filters(
	'ssfwc_text_before_social_share_buttons',
	Social_Sharer_For_Woo::get_option( 'ssfwc_text_before_buttons', 'ssfwc_settings', '' )
);

/**
 * Filters the list of social sharing icons displayed by the plugin.
 *
 * Developers can use this filter to add, remove, or reorder social icons.
 * The default list includes 'facebook', 'whatsapp', 'linkedin', and 'x'.
 *
 * @since    2.0.0
 * @param    array $social_icons_list An array of social icon slugs (e.g., 'facebook', 'twitter').
 */
$social_icons_list = apply_filters(
	'ssfwc_social_icons_list',
	Social_Sharer_For_Woo::get_option( 'ssfwc_buttons_list_field', 'ssfwc_settings', array( 'facebook', 'whatsapp', 'linkedin', 'x' ) )
);

if ( 'text_icons' === $icon_style ) {
	$class = 'text_icons';
} else {
	$class = 'icons_only';
}

/**
 * Fires before the social sharing buttons are displayed.
 *
 * This action hook allows developers to insert custom content or perform
 * actions immediately before the social sharing buttons HTML is rendered.
 *
 * @since    2.0.0
 */
do_action( 'ssfwc_before_social_share_buttons' );

$html = '<div class="ssfwc_social_share_buttons_wrapper">';

if ( ! empty( $text_before_buttons ) ) {
	$html .= sprintf( '<p class="%1$s">%2$s</p>', esc_attr( 'ssfwc_text_before_buttons' ), esc_html( $text_before_buttons ) );
}

$html .= '<div class="ssfwc_social_share_buttons ' . esc_attr( $class ) . ' a2a_kit a2a_kit_size_32 a2a_default_style">';

if ( ! empty( $social_icons_list ) ) {
	foreach ( $social_icons_list as $key => $value ) {
		$text  = 'text_icons' === $icon_style ? ucwords( str_replace( '_', ' ', $value ) ) : '';
		$html .= sprintf(
			'<a class="%1$s %2$s %3$s %4$s"><img src="%5$s" class="img-icon" />%6$s</a>',
			esc_attr( "a2a_s_$value" ),
			esc_attr( "a2a_button_$value" ),
			esc_attr( $button_style ),
			esc_attr( $class ),
			esc_url( SOCIAL_SHARER_FOR_WOO_PLUGIN_URL . "admin/images/icons/{$value}.svg" ),
			esc_html( $text )
		);
	}
}

$html .= '</div></div>';

/**
 * Filters the final HTML output of the social sharing buttons.
 *
 * This filter provides a way to completely customize or replace the entire
 * HTML structure of the social sharing buttons.
 *
 * @since    2.0.0
 * @param    string $html              The generated HTML for the social sharing buttons.
 * @param    array  $social_icons_list The list of social icon slugs being displayed.
 * @param    string $icon_style        The display style of the icons ('icons_only' or 'text_icons').
 */
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo apply_filters( 'ssfwc_social_share_buttons_html', $html, $social_icons_list, $icon_style );

/**
 * Fires after the social sharing buttons are displayed.
 *
 * This action hook allows developers to insert custom content or perform
 * actions immediately after the social sharing buttons HTML is rendered.
 *
 * @since    2.0.0
 */
do_action( 'ssfwc_after_social_share_buttons' );
