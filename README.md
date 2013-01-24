Flickr field
=============

## Description

This is an add-on for the Advanced Custom Fields WordPress plugin that creates a list of all Flickr sets/galleries from a specific Flickr user.

**Contributors**:

* Paul Huisman	([paulhuisman-online.nl](http://www.paulhuisman-online.nl))

## Notice

- This add-on needs [ACF](http://www.advancedcustomfields.com/) 


## Installation

1.Download or clone the ACF Flickr set repo to your plugin or theme:  
* https://github.com/phuisman88/ACF/zipball/master or  
* git clone git://github.com/phuisman88/ACF.git acf-flickr-field  

2.Register the field 

register_field($name, $path);

	if(function_exists('register_field')) {    
		register_field('flickr_field', dirname(__File__) . '/fields/flickr/flickr.php');  
	}  


## Usage Example (PHP)

	// Get the Flickr set data by using get_field
	$flickr_set = get_field('flickr_set');
	
	// Check if an set or gallery ID exists
	if (!empty($flickr_set['id'])) {
		
		// Require phpFlickr
		require_once(dirname(__FILE__) . '/fields/flickr/phpFlickr.php');
		$f = new phpFlickr($flickr_set['api_key']);
		
		// Enable phpFlickr caching
		$f->enableCache("f", dirname(__FILE__) . '/fields/flickr/cache');
	
		// Get all data based on Flickr ID (set or gallery)
		switch ($flickr_set['flickr_content']) {
			
			case 'sets':
				$photos = $f->photosets_getPhotos($flickr_set['id']);
				foreach ($photos['photoset']['photo'] as $photo) {	
					echo '<a href="'. $f->buildPhotoURL($photo, 'large') .'"><img src="'. $f->buildPhotoURL($photo, 'square') .'"/></a>';
				}
			break;
			
			case 'galleries':
				$photos = $f->galleries_getPhotos($flickr_set['id']);
				
				foreach ($photos['photos']['photo'] as $photo) {
					echo '<a href="'. $f->buildPhotoURL($photo, 'large') .'"><img src="'. $f->buildPhotoURL($photo, 'square') .'"/></a>';
				}
			break;
			
		}
		
	}
	

Make sure the folder flickr is located in wp-content/themes/[YOUR THEME]/fields

## More documentation

* Check out [my personal website](http://www.paulhuisman-online.nl/fresh-look/flickr-field) for more documentation on how to use this custom field for ACF.
