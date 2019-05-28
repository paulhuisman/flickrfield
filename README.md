Flickr field for Advanced Custom Fields v4 and v5
=============

## Maintenance info
*Please note: this plugin is NOT being actively maintained at the moment. The plugin might not work with new versions of WordPress and ACF.*

## Description

The Flickr Field will grant you the ability to include photos, sets and galleries from your Flickr account. After selecting which image formats you wish to use the plugin will generate the flickr image urls. This plugin is an add-on for the Advanced Custom Fields WordPress plugin.

This plugins also grants you the ability to enable "Private Mode". Here you have to authenticate your account and generate a token which you can use to access your own private pictures. These private pictures are not available for the public on your Flickr.com account.

For more info, check the [plugin page on WordPress.org](https://wordpress.org/plugins/flickr-field-for-advanced-custom-fields/).

## Notice

- This add-on needs [Advanced Custom Fields](http://www.advancedcustomfields.com/).
- When you update from ACF v4 to ACF v5; First disable the Flickr plugin, then disable ACF v4 and enable ACF v5. Don't forget to backup your data first.
- I recommend using [Easy Fancybox](https://wordpress.org/plugins/easy-fancybox/) if you want to use a lightbox that's very easy to setup.

## Installation

1. Download the ACF Flickr Field repo to your plugin directory by downloading https://github.com/paulhuisman/flickrfield/zipball/master or clone it via git: git clone git://github.com/paulhuisman/flickrfield.git
2. Enable the plugin in your Wordpress installation.
3. Succes! You can now select a Flickr field when you create new custom fields.

## Usage Example (in PHP)

**Getting the contents of a photostream and looping through the results**

	$flickr_photostream = get_field(FIELD_NAME);

	if (isset($flickr_photostream['items'])) {
		foreach ($flickr_photostream['items'] as $id => $photo) {
			echo '<a href="' . $photo['large'] . '" title="' . $photo['title'] . '"><img src="' . $photo['thumb'] . '" /></a>';
		}
	}

**Getting the contents of a set and looping through the results**

	$flickr_set = get_field(FIELD_NAME);

	if (isset($flickr_set['items'])) {
		foreach ($flickr_set['items'] as $id => $photos) {
			foreach ($photos as $photo) {
				echo '<a href="' . $photo['large'] . '" title="' . $photo['title'] . '"><img src="' . $photo['thumb'] . '" /></a>';
			}
		}
	}

