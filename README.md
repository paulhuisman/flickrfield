Flickr field
=============

## Description

This is an add-on for the Advanced Custom Fields WordPress plugin that creates a list of all Flickr sets/galleries from a specific Flickr user.

## Notice

- This add-on needs [ACF](http://www.advancedcustomfields.com/) 
- I recommend using [Easy Fancybox](https://wordpress.org/plugins/easy-fancybox/) if you want to use a lightbox that's very easy to setup.

## Installation

1. Download or clone the ACF Flickr Field repo to your plugin directory by downloading https://github.com/phuisman88/flickrfield/zipball/master or cloning: git clone git://github.com/phuisman88/flickrfield.git  
2. Enable the plugin in Wordpress.
3. Succes! You can now select a Flickr field when you create new custom fields.

## Usage Example (PHP)

	$flickr = get_field(FIELD_NAME);
	
	if (isset($flickr['items'])) {
		foreach ($flickr['items'] as $id => $photo) {
			echo '<a href="' . $photo['large'] . '"><img src="' . $photo['thumb'] . '" /></a>';
		}
	}
