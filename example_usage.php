<div class="flickr_set">
	<h2>Flickr Set</h2>
	<?php 
	// Get Flickr set ID, Api Key and User ID
	$flickr_set = get_field('flickr_set');
	
	// Require and initialize phpFlickr library
	require_once(dirname(__FILE__) . '/flickr_set/phpFlickr.php');
	$f = new phpFlickr($flickr_set['api_key']);
	
	// Enable the phpFlickr cache - make sure the folder cache is properly placed
	$f->enableCache("f", dirname(__FILE__) . '/flickr_set/cache');
	
	// Get all photos that are part of the given set ID
	$photos = $f->photosets_getPhotos($flickr_set['id']);

	// Loop through photos in the set and use buildPhotoURL to create an image src
	foreach ($photos['photoset']['photo'] as $photo) {
		?>
		<a class="colorbox" href="<?php echo $f->buildPhotoURL($photo, 'medium_640'); ?>">
			<img src="<?php echo $f->buildPhotoURL($photo, 'square'); ?>" />
		</a>
		<?php 
	} ?>
</div>