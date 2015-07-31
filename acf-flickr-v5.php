<?php

class acf_field_flickr extends acf_field {

	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct() {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

		$this->name = 'flickr';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('Flickr Field', 'flickr');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'content';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'flickr_api_key'        => '',
			'flickr_user_id'        => '',
			'flickr_content_type'   => 'sets',
			'flickr_sets_amount'    => '9999',
			'flickr_max_selected'   => '0',
			'flickr_show_limit'     => '500',
			'flickr_thumb_size'     => 'square',
			'flickr_large_size'     => 'large_1024',
			'flickr_cache_enabled'  => '1',
			'flickr_cache_duration' => '168',
		);


		// do not delete!
    parent::__construct();

	}


	/*
	*  render_field_options()
	*
	*  Create extra options for your field. These are visible when editing a field.
	*  All parameters of `acf_render_field_option` can be changed except 'prefix'
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {
		acf_render_field_setting( $field, array(
			'required'  => true,
			'label'			=> __('Flickr User ID','acf-flickr'),
			'instructions'	=> __('Find your User ID at','acf-flickr') . ' <a href="http://idgettr.com/">http://idgettr.com/</a>',
			'type'			=> 'text',
			'name'			=> 'flickr_user_id',
		));

		acf_render_field_setting( $field, array(
			'required'  => true,
			'label'			=> __('Flickr API Key','acf-flickr'),
			'instructions'	=> __('Find or register your API key at','acf-flickr') . ' <a href="http://www.flickr.com/services/apps/">http://www.flickr.com/services/apps</a>',
			'type'			=> 'text',
			'name'			=> 'flickr_api_key',
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Type of content','acf-flickr'),
			'instructions'	=> __('Do you want to be able to select photos from the photostream or use sets/galleries that have already been created on Flickr?','acf-flickr'),
			'type'			=> 'select',
			'name'			=> 'flickr_content_type',
			'choices' 	=> array(
				'sets'        => 'Sets',
				'galleries'   => 'Galleries',
				'photostream' => 'Photostream',
			),
		));

		acf_render_field_setting( $field, array(
			'label'        => __('Display amount','acf-flickr'),
			'instructions' => __('How many sets/photos do you want the admin to select from? The most recent items will be shown first.','acf-flickr'),
			'type'         => 'select',
			'name'         => 'flickr_sets_amount',
			'choices'      => array(
				'10'   =>'10',
				'20'   =>'20',
				'30'   =>'30',
				'40'   =>'40',
				'50'   =>'50',
				'100'  =>'100',
				'9999' =>'Unlimited',
			),
		));

		acf_render_field_setting( $field, array(
			'label'        => __('Max selectable amount','acf-flickr'),
			'instructions' => __('What\'s the maximum amount to be attached to a post by the user? Using 0 is default (and unlimited).','acf-flickr'),
			'type'         => 'text',
			'name'         => 'flickr_max_selected',
		));

		$cache_dir = dirname(__FILE__) . '/cache';
		if (!	is_writeable($cache_dir)) {
			$instructions = __('The cache folder <em>'. $cache_dir . '</em> is <span style="color:#CC0000; font-weight:bold">not writable</span>. Make sure cache can be used by executing <i>sudo chmod 777</i> on the cache folder.', 'acf-filckr');
		}
		else {
			$instructions = '<span style="color:#336600">' . __('The cache folder is writable!', 'acf-flickr') . '</span>';
		}

		acf_render_field_setting( $field, array(
			'label'        => __('Enable cache','acf-flickr'),
			'instructions' => $instructions,
			'type'         => 'select',
			'name'         => 'flickr_cache_enabled',
			'choices'      => array(
				'1' => 'Yes',
				'0' => 'No',
			),
		));

		acf_render_field_setting( $field, array(
			'label'        => __('Cache duration','acf-flickr'),
			'instructions' => __('The time your cache may last in minutes (this setting will be ignored when your cache is disabled).','acf-flickr') . ' <a href="http://www.flickr.com/services/apps/">http://www.flickr.com/services/apps</a>',
			'type'         => 'text',
			'name'         => 'flickr_cache_duration',
			'append'       => 'minutes',
		));

		acf_render_field_setting( $field, array(
			'label'        => __('Maximum amount of photos in the set to load', 'acf-flickr'),
			'instructions' => __('For sets only: How many photos should be loaded when viewing the set? Flickr allows a maximum of 500.', 'acf-flickr'),
			'type'         => 'text',
			'name'         => 'flickr_show_limit',
		));

		acf_render_field_setting( $field, array(
			'label'        => __('Thumbnail size','acf-flickr'),
			'instructions' => __('The preferred size of the photo thumbnail.','acf-flickr'),
			'type'         => 'select',
			'name'         => 'flickr_thumb_size',
			'choices' 		 => array(
				'square'     => '75x75 (square)',
				'thumbnail'  => '100px (rectangle)',
				'square_150' => '150x150 (square)',
				'small_240'  => '240px (rectangle)',
				'small_320'  => '320px (rectangle)',
			),
		));

		acf_render_field_setting( $field, array(
			'label'        => __('Large size','acf-flickr'),
			'instructions' => __('The preferred size of the enlargment of the photo.','acf-flickr'),
			'type'         => 'select',
			'name'         => 'flickr_large_size',
			'choices' 		 => array(
				'medium_640'   => '640px',
				'medium_800'   => '800px',
				'large_1024'   => '1024px',
				//'large_1600'   => '1600px',
				'original'     => 'Original',
			),
		));

	}


	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {
		// Defaults
		$field['value'] = isset($field['value']) ? $field['value'] : array();
		$field['optgroup'] = isset($field['optgroup']) ? $field['optgroup'] : false;

		// Get all Flickr sets by the given user ID and api key (both required)
		require_once(dirname(__FILE__) . '/phpFlickr.php');
		$f = new phpFlickr($field['flickr_api_key']);

		// Caching
		$cache_dir = dirname(__FILE__) . '/cache';
		if (is_writeable($cache_dir) && $field['flickr_cache_enabled'] == 1) {
			$duration = $field['flickr_cache_duration'] * 60 * 60;
			$f->enableCache('fs', $cache_dir, $duration);
		}

		$field['choices'] = array();
		$field['choices'][''] = '';
		?>

		<div class="field_form flickr_field type_<?php echo $field['flickr_content_type']; ?>">
			<?php
			$items = array();

			if (isset($field['value']['items'])) {
				$items = json_decode($field['value']['items']);
			}

			// Check for three types of Flickr content; Sets, Galleries and Photostream
			if ($field['flickr_content_type'] == 'sets' || $field['flickr_content_type'] == 'galleries') {
				if ($field['flickr_content_type'] == 'sets') {
					$flickr_data = $f->photosets_getList($field['flickr_user_id'], $field['flickr_sets_amount'], 1);
				}
				elseif ($field['flickr_content_type'] == 'galleries') {
					$flickr_data = $f->galleries_getList($field['flickr_user_id'], $field['flickr_sets_amount'], 1);
				}
				?>

				<table class="acf_input widefat acf_field_form_table">
					<tbody>
						<?php
						if (is_array($flickr_data) && !empty($flickr_data)) {
							if ($field['flickr_content_type'] == 'sets') {
								$data = $flickr_data['photoset'];
							}
							elseif ($field['flickr_content_type'] == 'galleries') {
								$data = $flickr_data['galleries']['gallery'];
							}
							foreach($data as $key => $flickr) {
								?>
								<tr class="field_label flickr_row <?php if (isset($flickr['id']) && in_array($flickr['id'], $items)) echo 'active-row'; ?>" data-flickr-id="<?php echo $flickr['id']; ?>">
									<td class="label set_image">
										<?php if ($field['flickr_content_type'] == 'sets'): ?>
											<img title="<?php echo $flickr['title'];?>" src="http://farm<?php echo $flickr['farm'];?>.static.flickr.com/<?php echo $flickr['server'];?>/<?php echo $flickr['primary'];?>_<?php echo $flickr['secret'];?>_s.jpg">
										<?php else: ?>
											<img title="<?php echo $flickr['title'];?>" src="http://farm<?php echo $flickr['primary_photo_farm'];?>.static.flickr.com/<?php echo $flickr['primary_photo_server'];?>/<?php echo $flickr['primary_photo_id'];?>_<?php echo $flickr['primary_photo_secret'];?>_s.jpg">
										<?php endif; ?>
									</td>
									<td class="set_info">
										<p class="set_title"><?php echo $flickr['title'];?></p>
										<p class="description"><?php echo $flickr['description'];?></p>
										<p class="meta_data">
											<?php _e('Added on');?> <?php echo date_i18n(get_option('date_format') ,$flickr['date_create']); echo ' &nbsp;|&nbsp; ';
											echo $flickr['count_views'];?> <?php _e('views on Flickr');
											echo ' &nbsp;|&nbsp; ';
											echo $flickr['photos'];?> <?php _e('Photos');
											if ($flickr['videos'] != 0) {
												echo ' &nbsp;|&nbsp; '. $flickr['videos'] .' ';
												_e('Videos');
											} ?>
										</p>
									</td>
								 </tr>
								<?php
							}
						}
						else {
							?><tr class="field_label">
								<td colspan="2"><?php _e('There are no Flickr sets available for user ID'); ?> <?php echo $field['flickr_user_id']; ?> <?php _e('or there is a problem with API KEY'); ?> <?php echo $field['api_key']; ?></td>
							</tr><?php
						}
					?>
				</tbody>
			</table>
			<?php
			}
			elseif($field['flickr_content_type'] == 'photostream') {
				$flickr_data = $f->people_getPublicPhotos ($field['flickr_user_id'], NULL, 'url_o', $field['flickr_sets_amount'], '');
				if (is_array($flickr_data['photos']) && isset($flickr_data['photos']['photo'][0])):  ?>
					<ul class="field_label photostream">
						<?php foreach($flickr_data['photos']['photo'] as $key => $photo): ?>
							<?php
							$active = '';
							if (is_array($items)) {
								foreach ($items as $k => $item) {
									$item = get_object_vars($item);
									if(is_array($item) && in_array($photo['id'], $item)) {
										$active = ' active-row';
									}
								}
							}
							?>
							<li class="label flickr_row photo_image<?php echo $active; ?>"
								data-flickr-id="<?php echo $photo['id']; ?>"
								data-flickr-server="<?php echo $photo['server']; ?>"
								data-flickr-secret="<?php echo $photo['secret']; ?>"
								data-flickr-farm="<?php echo $photo['farm']; ?>"
								data-flickr-title="<?php echo $photo['title']; ?>"
								data-flickr-original-url="<?php echo $photo['url_o']; ?>"
								>
								<img title="<?php echo $photo['title'];?>" src="<?php echo $f->buildPhotoURL($photo, 'square'); ?>">
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<p><?php _e('There are no Flickr sets available for user ID'); ?> <?php echo $field['flickr_user_id']; ?> <?php _e('or there is a problem with API KEY'); ?> <?php echo $field['api_key']; ?></p>
				<?php
				endif;
			}
			?>
		</div>

		<?php
		if (!empty($sets['photoset'])) {
			foreach($sets['photoset'] as $set_key => $set) {
				// Add to the choices array for the selectbox
				$field['choices'][$set['id']] = $set['title'] .' (' .$set['photos']. ' photos)';
			}
		}

		// no choices
		if(empty($field['choices']))
		{
			echo '<p>' . __("No choices to choose from",'acf-flickr') . '</p>';
			return false;
		}

		// html
		if (!isset($multiple)) { $multiple = ''; }
		echo '<select id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . '  data-max-selectable="'. $field['flickr_max_selected'] .'" data-flickr-type="'. $field['flickr_content_type'] .'" >';

		// null
		if($field['required'] == '1')
		{
			echo '<option value="null"> - Select - </option>';
		}

		// loop through values and add them as options
		foreach($field['choices'] as $key => $value)
		{
			if($field['optgroup'])
			{
				// this select is grouped with optgroup
				if($key != '') echo '<optgroup label="'.$key.'">';

				if($value)
				{
					foreach($value as $id => $label)
					{
						$selected = '';
						if(is_array($field['value']) && in_array($id, $field['value']))
						{
							// 2. If the value is an array (multiple select), loop through values and check if it is selected
							$selected = 'selected="selected"';
						}
						else
						{
							// 3. this is not a multiple select, just check normaly
							if($id == $field['value'])
							{
								$selected = 'selected="selected"';
							}
						}
						echo '<option value="'.$id.'" '.$selected.'>'.$label.'</option>';
					}
				}

				if($key != '') echo '</optgroup>';
			}
			else
			{
				$selected = '';
				if(is_array($field['value']) && in_array($key, $field['value']))
				{
					// 2. If the value is an array (multiple select), loop through values and check if it is selected
					$selected = 'selected="selected"';
				}
				else
				{
					// 3. this is not a multiple select, just check normaly
					if($key == $field['value'])
					{
						$selected = 'selected="selected"';
					}
				}
				echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
			}

		}

		echo '</select>';

	}

	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function input_admin_enqueue_scripts() {

		$dir = plugin_dir_url( __FILE__ );

		// register & include JS
		wp_register_script( 'acf-flickr-init', "{$dir}js/flickr-acf5.js" );
		wp_enqueue_script('acf-flickr-init');

		// register & include CSS
		wp_register_style( 'acf-input-flickr', "{$dir}css/input.css" );
		wp_enqueue_style('acf-input-flickr');

	}


	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_footer() {



	}

	*/


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_enqueue_scripts() {

	}

	*/


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_head() {

	}

	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	function load_value( $value, $post_id, $field ) {
		$data = array();

		$data['items']             = $value;
		$data['type']              = $field['flickr_content_type'];
		$data['flickr_show_limit'] = $field['flickr_show_limit'];
		$data['thumb_size']        = $field['flickr_thumb_size'];
		$data['large_size']        = $field['flickr_large_size'];
		$data['user_id']           = $field['flickr_user_id'];
		$data['api_key']           = $field['flickr_api_key'];

		return $data;

	}



	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function update_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed to the render_field() function
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @param	$template (boolean) true if value requires formatting for front end template function
	*  @return	$value
	*/


	function format_value( $value, $post_id, $field ) {
		// bail early if no value
		if(empty($value)) {
			return $value;
		}
		if (!empty($value['items'])) {
			// Decode JSON format that is used in the database
			$value['items'] = json_decode($value['items']);

			// Initialize a new phpFlickr object based on your api key
			require_once(dirname(__FILE__) . '/phpFlickr.php');
			$f = new phpFlickr($value['api_key']);

			// enable phpFlickr caching if possible
			$cache_dir = dirname(__FILE__) . '/cache';
			if (is_writeable($cache_dir) && $field['flickr_cache_enabled'] == 1) {
				$duration = $field['flickr_cache_duration'] * 60;
				$f->enableCache('fs', $cache_dir, $duration);
			}

			if ($value['type'] == 'sets' || $value['type'] == 'galleries') {
				// Set default to get if flickr_show_limit does not exist
				if(!isset($value['flickr_show_limit'])) {
					$value['flickr_show_limit'] = '500';
				}

				// Get photos from Flickr based on set/gallery id
				$sets = array();
				foreach($value['items'] as $id) {
					if ($value['type'] == 'sets') {
						$photos = $f->photosets_getPhotos($id, 'url_o', null, $value['flickr_show_limit']);
						$name = 'photoset';
					}
					elseif ($value['type'] == 'galleries') {
						$photos = $f->galleries_getPhotos($id, 'url_o', $value['flickr_show_limit']);
						$name = 'photos';
					}
					// Loop through all photos and create a thumb and large url
					foreach ($photos[$name]['photo'] as $photo) {
						$sets[$id][$photo['id']]['title']    = $photo['title'];
						$sets[$id][$photo['id']]['thumb']    = $f->buildPhotoURL($photo, $value['thumb_size']);
						$sets[$id][$photo['id']]['large']    = ($value['large_size'] == 'original') ? $photo['url_o'] : $f->buildPhotoURL($photo, $value['large_size']);
						$sets[$id][$photo['id']]['photo_id'] = $photo['id'];
					}
				}
				$value['items'] = $sets;
			}
			elseif($value['type'] == 'photostream') {
				$items = array();

				foreach($value['items'] as $photo) {
					$items[] = array(
						'title'    => $photo->title,
						'thumb'    => $f->buildPhotoURL($photo, $value['thumb_size']),
						'large'    => ($value['large_size'] == 'original') ? $photo->original_url : $f->buildPhotoURL($photo, $value['large_size']),
						'photo_id' => $photo->id,
					);
				}
				$value['items'] = $items;
			}
		}

		return $value;
	}

}

// create field
new acf_field_flickr();

?>
