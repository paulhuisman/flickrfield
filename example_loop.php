<?php 
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
?>