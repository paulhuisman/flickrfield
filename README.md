Flickr field
=============

## Description

This is an add-on for the Advanced Custom Fields WordPress plugin that creates a list of all Flickr sets/galleries from a specific Flickr user.

**Contributors**:

* Paul Huisman	([paulhuisman.com](http://www.paulhuisman.com))

## Notice

- This add-on needs [ACF](http://www.advancedcustomfields.com/) 


## Installation

1. Download or clone the ACF Flickr Field repo to your plugin directory by downloading https://github.com/phuisman88/flickrfield/zipball/master or cloning: git clone git://github.com/phuisman88/flickrfield.git  
2. Enable the plugin in Wordpress.
3. Succes! You can now select a Flickr field when you create new custom fields.

## Usage Example (PHP)

	// Get Flickr Field data
	$flickr = get_field(FIELD_NAME);
	
	if (!empty($flickr['set_id']) && $flickr['set_id'] != 0) {
		// Require phpFlickr
		require_once(getCwd()  . '/wp-content/plugins/flickrfield/phpFlickr.php');

		// Initialize a new phpFlickr object based on your api key
		$f = new phpFlickr($flickr['api_key']);
		
		// Optionally: enable phpFlickr caching
		$f->enableCache('f', getCwd()  . '/wp-content/plugins/flickrfield/cache');
		
		// Get photos from Flickr based on set id
		$photos = $f->photosets_getPhotos($flickr['set_id']);

		// Loop through photos and print them
		foreach ($photos['photoset']['photo'] as $photo) {	
			echo '<a href="'. $f->buildPhotoURL($photo, 'large') .'"><img src="'. $f->buildPhotoURL($photo, 'square') .'"/></a>';	
		}		
	}
