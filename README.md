Flickr field for Advanced Custom Fields v4 and v5
=============

## Description

The Flickr Field will grant you the ability to include photos, sets and galleries from your Flickr account. After selecting which image formats you wish to use the plugin will generate the flickr image urls. This plugin is an add-on for the Advanced Custom Fields WordPress plugin.

## Notice

- This add-on needs [Advanced Custom Fields](http://www.advancedcustomfields.com/).
- When you update from ACF v4 to ACF v5; First disable the Flickr plugin, then disable ACF v4 and enable ACF v5. Don't forget to backup your data first.
- I recommend using [Easy Fancybox](https://wordpress.org/plugins/easy-fancybox/) if you want to use a lightbox that's very easy to setup.

## Installation

1. Download the ACF Flickr Field repo to your plugin directory by downloading https://github.com/phuisman88/flickrfield/zipball/master or clone it via git: git clone git://github.com/phuisman88/flickrfield.git  
2. Enable the plugin in your Wordpress installation.
3. Succes! You can now select a Flickr field when you create new custom fields.

## Usage Example for Photostream (PHP)

	$flickr_photostream = get_field(FIELD_NAME);

	if (isset($flickr_photostream['items'])) {
		foreach ($flickr_photostream['items'] as $id => $photo) {
			echo '<a href="' . $photo['large'] . '" title="' . $photo['title'] . '"><img src="' . $photo['thumb'] . '" /></a>';
		}
	}

## Usage Example for Photo Set (PHP)

	$flickr_set = get_field(FIELD_NAME);

	if (isset($flickr_set['items'])) {
		foreach ($flickr_set['items'] as $id => $photos) {
			foreach ($photos as $photo) {
				echo '<a href="' . $photo['large'] . '" title="' . $photo['title'] . '"><img src="' . $photo['thumb'] . '" /></a>';
			}
		}
	}
