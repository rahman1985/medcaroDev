<?php
/*
Plugin Name: Skype Live Chat
Plugin URI: https://www.thewebsitedev.com/
Description: Connect with your customers like never before. Video Call and Chat on your website.
Version: 0.3
Author: Gautam Thapar
Author URI: http://gautamthapar.me/
License: GPLv2 or later
Text Domain: skype-lc
Domain Path: /languages/
*/

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) ){
	exit;
}

define( 'SKYPE_LC__VERSION', '0.3' );
define( 'SKYPE_LC__MIN_WP_VERSION', '4.0' );
define( 'SKYPE_LC__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SKYPE_LC__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

global $wp_version;
if ( $wp_version >= SKYPE_LC__MIN_WP_VERSION ) {
    add_action( 'activated_plugin', 'skype_lc_welcome_redirect', 10, 2 );
}

/**
 * Redirect to options page when plugin is activated.
 * @since  0.1
 *
 * @param $plugin
 * @param $network_activate
 */
function skype_lc_welcome_redirect( $plugin, $network_activate ) {
    if( $network_activate === false ){
        if( $plugin == plugin_basename( __FILE__ ) ) {
            wp_redirect( admin_url('options-general.php?page=skype-live-chat') );
            exit;
        }
    }
}

/**
 * Display chat on the website
 * Hooked to wp_footer
 * @since  0.1
 */
function skype_lc_markup() {
    // get all our options
    $options = get_option( 'skype_lc_options' );
	// if not enabled, return
	if( !isset( $options['enable_chat'] ) || !$options['enable_chat'] ) {
		return;
	}
    // if no skype id, return
    if( !isset( $options['skype_id'] ) || empty( $options['skype_id'] ) ) {
        return;
    }
    // else add the markup
    ?>
    <span class="skype-button <?php echo esc_attr( isset( $options['button_style'] ) ? $options['button_style'] : 'bubble' ); ?>" data-contact-id="<?php echo esc_attr( isset( $options['skype_id'] ) ? $options['skype_id'] : '' ); ?>" data-color="<?php echo esc_attr( isset( $options['button_color'] ) ? $options['button_color'] : '#00AFF0' ); ?>"></span>
    <span class="skype-chat"
          data-color-message="<?php echo esc_attr( isset( $options['message_color'] ) ? $options['message_color'] : '#80DDFF' ); ?>"
          data-can-collapse="<?php echo esc_attr( isset( $options['collapse'] ) ? 'true' : 'false' ); ?>"
          data-can-close="<?php echo esc_attr( isset( $options['close'] ) ? 'true' : 'false' ); ?>"
          data-can-upload-file="<?php echo esc_attr( isset( $options['upload_file'] ) ? 'true' : 'false' ); ?>"
          data-show-header="<?php echo esc_attr( isset( $options['show_header'] ) ? 'true' : 'false' ); ?>"
          data-entry-animation="<?php echo esc_attr( isset( $options['entry_animation'] ) ? 'true' : 'false' ); ?>"
    ></span>
    <script src="https://swc.cdn.skype.com/sdk/v1/sdk.min.js"></script>
	<?php
}
add_action( 'wp_footer', 'skype_lc_markup' );

function skype_lc_admin_script() {
    // initiate iris color picker ?>
    <script type="text/javascript">
        jQuery( document ).ready( function ( $ ) {
            $( '.color-picker' ).wpColorPicker();
        });
    </script>
    <?php
}
add_action( 'admin_head-settings_page_skype-live-chat', 'skype_lc_admin_script' );

function skype_lc_admin_scripts($hook) {
	if ( 'settings_page_skype-live-chat' != $hook ) {
		return;
	}
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts',  'skype_lc_admin_scripts' );

/**
 * Custom option and settings
 * Register our skype_lc_settings_init to the admin_init action hook
 * @since  0.1
 */
function skype_lc_settings_init() {
	// register a new setting for "skype_lc" page
	register_setting( 'skype_lc', 'skype_lc_options' );

	// register a new section in the "skype_lc" page
	add_settings_section(
		'skype_lc_general',
		__( 'General', 'skype-lc' ),
		'skype_lc_general_cb',
		'skype_lc'
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'enable_chat', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Enable Live Chat?', 'skype-lc' ),
		'skype_lc_enable_chat_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'enable_chat',
			'class' => 'skype_lc_row'
		]
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'skype_id', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Skype ID', 'skype-lc' ),
		'skype_lc_skype_id_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'skype_id',
			'class' => 'skype_lc_row'
		]
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'button_style', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Button Style', 'skype-lc' ),
		'skype_lc_button_style_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'button_style',
			'class' => 'skype_lc_row'
		]
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'button_color', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Button Color', 'skype-lc' ),
		'skype_lc_button_color_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'button_color',
			'class' => 'skype_lc_row'
		]
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'message_color', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Message Color', 'skype-lc' ),
		'skype_lc_message_color_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'message_color',
			'class' => 'skype_lc_row'
		]
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'collapse', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Enable minimization functionality?', 'skype-lc' ),
		'skype_lc_collapse_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'collapse',
			'class' => 'skype_lc_row'
		]
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'close', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Enable close button?', 'skype-lc' ),
		'skype_lc_close_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'close',
			'class' => 'skype_lc_row'
		]
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'upload_file', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Enable file upload?', 'skype-lc' ),
		'skype_lc_upload_file_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'upload_file',
			'class' => 'skype_lc_row'
		]
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'show_header', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Show header?', 'skype-lc' ),
		'skype_lc_show_header_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'show_header',
			'class' => 'skype_lc_row'
		]
	);

	// register a new field in the "skype_lc_general" section, inside the "skype_lc" page
	add_settings_field(
		'entry_animation', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Entry Animation?', 'skype-lc' ),
		'skype_lc_entry_animation_cb',
		'skype_lc',
		'skype_lc_general',
		[
			'label_for' => 'entry_animation',
			'class' => 'skype_lc_row'
		]
	);
}
add_action( 'admin_init', 'skype_lc_settings_init' );

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.

/**
 * General section callback
 * @since  0.1
 *
 * @param $args
 */
function skype_lc_general_cb( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Web Control Generator.', 'skype-lc' ); ?></p>
	<?php
}

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// WordPress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.

/**
 * Enable chat option callback
 * @since  0.1
 *
 * @param $args
 */
function skype_lc_enable_chat_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	// output the field
	?>
	<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]" <?php echo isset( $options[ 'enable_chat' ] ) ? ( checked( $options[ 'enable_chat' ], $options[ 'enable_chat' ], false ) ) : ''; ?>>
	<p class="description">
		<?php esc_html_e( 'Power it on.', 'skype-lc' ); ?>
	</p>
	<?php
}

/**
 * Skype id option callback
 * @since  0.1
 *
 * @param $args
 */
function skype_lc_skype_id_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	// output the field
	?>
	<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( isset( $options['skype_id'] ) ? $options['skype_id'] : '' ); ?>">
	<p class="description">
		<?php esc_html_e( 'Insert your Skype ID.', 'skype-lc' ); ?>
	</p>
	<?php
}

/**
 * Button style option callback
 * @since  0.1
 *
 * @TODO - rounded and rectangle style options support
 *
 * @param $args
 */
function skype_lc_button_style_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	// output the field
	?>
	<select id="<?php echo esc_attr( $args['label_for'] ); ?>" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
		<option value="bubble" <?php echo isset( $options[ 'button_style' ] ) ? ( selected( $options[ 'button_style' ], 'bubble', false ) ) : ( '' ); ?>>
			<?php esc_html_e( 'Bubble', 'skype-lc' ); ?>
		</option>
	</select>
	<p class="description">
		<?php esc_html_e( 'Select the button style. Support for rounded and rectnagle buttons coming soon.', 'skype-lc' ); ?>
	</p>
	<?php
}

/**
 * Button background color option callback
 * @since  0.1
 *
 * @param $args
 */
function skype_lc_button_color_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	// output the field
	?>
	<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" class="color-picker" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( isset( $options['button_color'] ) ? $options['button_color'] : '#00AFF0' ); ?>">
	<p class="description">
		<?php esc_html_e( 'Select the button color.', 'skype-lc' ); ?>
	</p>
	<?php
}

/**
 * Message background color option callback
 * @since  0.1
 *
 * @param $args
 */
function skype_lc_message_color_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	// output the field
	?>
	<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" class="color-picker" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( isset( $options['message_color'] ) ? $options['message_color'] : '#80DDFF' ); ?>">
	<p class="description">
		<?php esc_html_e( 'Select the message color.', 'skype-lc' ); ?>
	</p>
	<?php
}

/**
 * Minimization functionality option callback
 * @since  0.3
 *
 * @param $args
 */
function skype_lc_collapse_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	// output the field
	?>
    <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]" <?php echo isset( $options[ 'collapse' ] ) ? ( checked( $options[ 'collapse' ], $options[ 'collapse' ], false ) ) : ''; ?>>
    <p class="description">
		<?php esc_html_e( 'Enables minimization functionality.', 'skype-lc' ); ?>
    </p>
	<?php
}

/**
 * Close button option callback
 * @since  0.3
 *
 * @param $args
 */
function skype_lc_close_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	// output the field
	?>
    <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]" <?php echo isset( $options[ 'close' ] ) ? ( checked( $options[ 'close' ], $options[ 'close' ], false ) ) : ''; ?>>
    <p class="description">
		<?php esc_html_e('Enables the close button.', 'skype-lc' ); ?>
    </p>
	<?php
}

/**
 * Upload file button option callback
 * @since  0.3
 *
 * @param $args
 */
function skype_lc_upload_file_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	?>
    <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]" <?php echo esc_attr( isset( $options[ 'upload_file' ] ) ? ( checked( $options[ 'upload_file' ], $options[ 'upload_file' ], false ) ) : '' ); ?>>
    <p class="description">
		<?php esc_html_e('Enables the upload file button.', 'skype-lc' ); ?>
    </p>
	<?php
}

/**
 * Show header option callback
 * @since  0.3
 *
 * @param $args
 */
function skype_lc_show_header_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	// output the field
	?>
    <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]" <?php echo isset( $options[ 'show_header' ] ) ? ( checked( $options[ 'show_header' ], $options[ 'show_header' ], false ) ) : ''; ?>>
    <p class="description">
		<?php esc_html_e('Enables the conversation header.', 'skype-lc' ); ?>
    </p>
	<?php
}

/**
 * Entry Animation option callback
 * @since  0.3
 *
 * @param $args
 */
function skype_lc_entry_animation_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'skype_lc_options' );
	// output the field
	?>
    <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="skype_lc_options[<?php echo esc_attr( $args['label_for'] ); ?>]" <?php echo isset( $options[ 'entry_animation' ] ) ? ( checked( $options[ 'entry_animation' ], $options[ 'entry_animation' ], false ) ) : ''; ?>>
    <p class="description">
		<?php esc_html_e('Enables the opening animation.', 'skype-lc' ); ?>
    </p>
	<?php
}

/**
 * Register options page
 * register our skype_lc_options_page to the admin_menu action hook
 * @since  0.1
 */
function skype_lc_options_page() {
	// add top level menu page
	add_submenu_page(
		'options-general.php',
		'Skype Live Chat',
		'Skype Live Chat',
		'manage_options',
		'skype-live-chat',
		'skype_lc_options_page_html'
	);
}
add_action( 'admin_menu', 'skype_lc_options_page' );

/**
 * Options page markup
 * @since  0.1
 */
function skype_lc_options_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	// show error / update messages
	settings_errors( 'skype_lc_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "skype_lc"
			settings_fields( 'skype_lc' );
			// output setting sections and their fields
			// (sections are registered for "skype_lc", each field is registered to a specific section)
			do_settings_sections( 'skype_lc' );
			// output save settings button
			submit_button( __( 'Save Settings', 'skype-lc' ) );
			?>
		</form>
	</div>
	<?php
}

