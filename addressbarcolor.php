<?php
/*
Plugin Name: Mobile address bar color changer
Plugin URI: https://spacewisecoder.com/wordpress-plugins/
Description: Change the color of the address bar on chome on mobile devices
Version: 1.0.0
Author: spacewisecoder
Author URI: https://spacewisecoder.com
Text Domain: mabcc
*/

// Register hooks 

add_action('admin_menu', 'spacewisecoder_mabcc_admin_menu'); // Add the page to the admin menu
add_action('admin_init', 'spacewisecoder_mabcc_register_mysettings' );
add_action('wp_head', 'spacewisecoder_mabcc_meta_header_output' ); // output meta code for color change
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'spacewisecoder_mabcc__add_settings_link' ); // add Setting link on plugins page

// add menu page
function spacewisecoder_mabcc_admin_menu() {
	add_options_page(
		__('Mobile address bar color changer'),
		__('Mobile bar color changer'),
		'manage_options', 
		'spacewisecoder_mabcc_display_settings_page',
		'spacewisecoder_mabcc_display_settings_page');

	// Css rules for Color Picker
	wp_enqueue_style( 'wp-color-picker' );
	     
    // Register javascript
    add_action('admin_enqueue_scripts', 'spacewisecoder_mabcc_enqueue_admin_js'  );	
}


function spacewisecoder_mabcc_display_settings_page() {
	// output color changer picker
	?>
    <div class="wrap">
        <h2>Mobile address bar color</h2>
        <form method="post" action="options.php">     
        <?php
            settings_fields('spacewisecoder_mabcc_display_settings_page'); 		// nounce and other stuff
            do_settings_sections('spacewisecoder_mabcc_display_settings_page'); // output rest of them to the page
            submit_button();  // output submit button
        ?>
        </form>
    </div> <!-- /wrap -->
    <?php   
}

function spacewisecoder_mabcc_register_mysettings() { // whitelist options
  register_setting( 'spacewisecoder_mabcc_display_settings_page', 'spacewisecoder_mabcc_values' ); // field_color_value the stored options as array
  add_settings_section('color_section', 'Select color ', '', 'spacewisecoder_mabcc_display_settings_page');
  add_settings_field('my_field','Adress bar color : ','spacewisecoder_mabcc_my_field_callback','spacewisecoder_mabcc_display_settings_page','color_section');
}

function spacewisecoder_mabcc_my_field_callback() {
	//$values = esc_attr( get_option('ads_values') );
	$values = get_option('spacewisecoder_mabcc_values');
	$val = isset( $values['color'] ) ? $values['color'] : '';
	echo '<input type="text" name="spacewisecoder_mabcc_values[color]" value="'.$val.'" class="cmabc-color-picker">';
}

function spacewisecoder_mabcc_enqueue_admin_js() { 
     
    // Make sure to add the wp-color-picker dependecy to js file
    wp_enqueue_script( 'ads_custom_js', plugins_url( 'jquery.custom.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '', true  );
}

// add color for mobile browsing for Chrome, Firefox OS, Opera and Vivaldi
function spacewisecoder_mabcc_meta_header_output() {

	$values = get_option('spacewisecoder_mabcc_values');
	$color = isset( $values['color'] ) ? $values['color'] : '#FFFFFF';

	//$color = "#008509";

	// Chrome, Firefox OS, Opera and Vivaldi
	echo '<meta name="theme-color" content="'.$color.'">';
}


function spacewisecoder_mabcc__add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=spacewisecoder_mabcc_display_settings_page">' . __( 'Settings' ) . '</a>';
    array_unshift($links,$settings_link);
  	return $links;
}

?>