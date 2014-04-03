<?php
/*
Plugin Name: Advanced Custom Fields: Flickr
Plugin URI: https://github.com/phuisman88
Description: Flickr field for Advanced Custom Fields
Version: 1.0
Author: Paul Huisman
Author URI: www.paulhuisman.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class acf_flickr_plugin {
	/*
	*  Construct
	*/
	function __construct() {
		// set text domain
		$domain = 'acf-flickr';
		$mofile = trailingslashit(dirname(__File__)) . 'lang/' . $domain . '-' . get_locale() . '.mo';
		load_textdomain( $domain, $mofile );

		// version 4+
		add_action('acf/register_fields', array($this, 'register_fields'));

		// version 3-
		add_action( 'init', array( $this, 'init' ));
	}


	/*
	*  Init
	*/
	function init() {
		if(function_exists('register_field')) {
			register_field('acf_flickr', dirname(__File__) . '/acf-flickr-v3.php');
		}
	}

	/*
	*  register_fields
	*/
	function register_fields() {
		include_once('acf-flickr-v4.php');
	}

}

new acf_flickr_plugin();

?>