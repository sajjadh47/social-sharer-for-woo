<?php
/**
 * This file contains the definition of the Social_Sharer_For_Woo_Widget class, which
 * is used to register a widget.
 *
 * @package       Social_Sharer_For_Woo
 * @subpackage    Social_Sharer_For_Woo/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Widget class for displaying social sharing buttons.
 *
 * This class defines a WordPress widget that displays the social sharing buttons.
 * It extends the WP_Widget class and provides methods for constructing the widget,
 * displaying its content, handling form input in the admin panel, and updating widget settings.
 *
 * @since    2.0.0
 */
class Social_Sharer_For_Woo_Widget extends WP_Widget {
	/**
	 * Constructor for the widget.
	 *
	 * Initializes the widget's ID, name, and description. Calls the parent
	 * constructor.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function __construct() {
		parent::__construct(
			'ssfwc_widget',
			__( 'Social Sharing Buttons', 'social-sharer-for-woo' ),
			array( 'description' => __( 'Add the social sharing buttons anywhere on your site.', 'social-sharer-for-woo' ) )
		);
	}

	/**
	 * Displays the content of the widget.
	 *
	 * This method generates the HTML output for the widget, including the
	 * title (if provided) and the social sharing buttons.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $args     Widget arguments provided by WordPress.
	 * @param     array $instance Instance settings for the widget.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];

		// If a title is present, display it.
		if ( ! empty( $title ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		$version     = defined( 'SOCIAL_SHARER_FOR_WOO_PLUGIN_VERSION' ) ? SOCIAL_SHARER_FOR_WOO_PLUGIN_VERSION : '1.0.0';
		$plugin_name = 'social-sharer-for-woo';

		$plugin_public = new Social_Sharer_For_Woo_Public( $plugin_name, $version );
		$plugin_public->show_social_buttons();

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget'];
	}

	/**
	 * Displays the widget form in the admin panel.
	 *
	 * This method generates the HTML for the widget's settings form, which
	 * allows administrators to configure the widget's title.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $instance Current instance settings.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Social Sharing Buttons', 'social-sharer-for-woo' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'social-sharer-for-woo' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	/**
	 * Updates the widget settings.
	 *
	 * This method processes and saves the widget's settings when the form
	 * is submitted in the admin panel.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $new_instance New settings for this instance as input by the user.
	 * @param     array $old_instance Old settings for this instance.
	 * @return    array               The filtered instance settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}