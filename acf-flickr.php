<?php
/*
Plugin Name: Advanced Custom Fields: Flickr
Plugin URI: https://github.com/phuisman88/flickrfield
Description: Flickr field for Advanced Custom Fields. Display items from your photostream or entire sets/galleries along with your WordPress content.
Version: 1.0
Author: Paul Huisman
Author URI: www.paulhuisman.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// set text domain
// Reference: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
load_plugin_textdomain( 'acf-flickr', false, dirname( plugin_basename(__FILE__) ) . '/lang/' ); 

// Include field type for ACF5
// $version = 5 and can be ignored until ACF6 exists
function include_field_types_flickr( $version ) {
	include_once('acf-flickr-v5.php');
}
add_action('acf/include_field_types', 'include_field_types_flickr');	

// Include field type for ACF4
function register_fields_flickr() {
	include_once('acf-flickr-v4.php');
}
add_action('acf/register_fields', 'register_fields_flickr');	

?>