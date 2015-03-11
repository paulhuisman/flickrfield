<?php

class acf_field_flickr extends acf_field {
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'flickr';
		$this->label = __('Flickr Field');
		$this->category = __("Content", 'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			// add default here to merge into your field. 
			'flickr_api_key'        => '',
			'flickr_user_id'        => '',
			'flickr_content_type'   => 'sets',
			'flickr_sets_amount'    => '9999',
			'flickr_max_selected'   => '0',
			'flickr_thumb_size'     => 'square',
			'flickr_large_size'     => 'large_1024',
			'flickr_cache_enabled'  => '1',
			'flickr_cache_duration' => '168',
		);
		
		
		// do not delete!
    parent::__construct();
    	
    	
    // settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);

	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field ) {
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// key is needed in the field names to correctly save the data
		$key = $field['name'];		
		
		// Create Fields
		?>	
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Flickr User ID','acf-flickr'); ?><span class="required">*</span></label>
				<p class="description">Find your User ID at <a href="http://idgettr.com/">http://idgettr.com/</a></p>
			</td>
			<td>
				<?php		
				do_action('acf/create_field', array(
					'type'		=>	'text',
					'name'		=>	'fields['.$key.'][flickr_user_id]',
					'value'		=>	$field['flickr_user_id'],
				));		
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Flickr API Key','acf-flickr'); ?><span class="required">*</span></label>
				<p class="description"><?php _e('Find or register your API key at <a href="http://www.flickr.com/services/apps/">http://www.flickr.com/services/apps</a>', 'acf-flickr');?></p>
			</td>
			<td>
				<?php		
				do_action('acf/create_field', array(
					'type'		=>	'text',
					'name'		=>	'fields['.$key.'][flickr_api_key]',
					'value'		=>	$field['flickr_api_key'],
				));		
				?>
			</td>
		</tr>	
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e( 'Type of content', 'acf-flickr' );?></label>
				<p class="description"><?php _e('Do you want to be able to select photos from the photostream or use sets/galleries that have already been created on Flickr?', 'acf-flickr');?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][flickr_content_type]',
					'value'	=>	$field['flickr_content_type'],
					'choices' => array(
						'sets'        => 'Sets',
						'galleries'   => 'Galleries',
						'photostream' => 'Photostream',
					),
				));
				?>
		   </td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e( 'Display amount', 'acf-flickr' );?></label>
				<p class="description"><?php _e('How many sets/photos do you want to select from? The most recent items will be shown first.', 'acf-flickr');?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][flickr_sets_amount]',
					'value'	=>	$field['flickr_sets_amount'],
					'choices' => array(
						'10'   =>'10',
						'20'   =>'20',
						'30'   =>'30',
						'40'   =>'40',
						'50'   =>'50',
						'100'  =>'100',
						'9999' =>'Unlimited',
					)
				));
				?>
		   </td>
		</tr>		
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e( 'Max selectable amount', 'acf-flickr' );?></label>
				<p class="description"><?php _e('What\'s the maximum amount to be attached to a post? Using 0 is default (and unlimited).', 'acf-flickr');?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'text',
					'name'	=>	'fields['.$key.'][flickr_max_selected]',
					'value'	=>	$field['flickr_max_selected'],
				));
				?>
		   </td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e( 'Enable cache', 'acf-flickr' );?></label>
				<p class="description">
					<?php 
					$cache_dir = dirname(__FILE__) . '/cache';
					if (!	is_writeable($cache_dir)) {			
						echo __('The cache folder <em>'. $cache_dir . '</em> is <span style="color:#CC0000; font-weight:bold">not writable</span>. Make sure cache can be used by executing <i>sudo chmod 777</i> on the cache folder.', 'acf-flickr');
					}
					else {
						echo '<span style="color:#336600">' . __('The cache folder is writable!', 'acf-flickr') . '</span>';
					}
					?>
				</p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][flickr_cache_enabled]',
					'value'	=>	$field['flickr_cache_enabled'],
					'choices' => array(
						'1' => 'Yes',
						'0' => 'No',
					),
				));
				?>
		   </td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Cache duration','acf-flickr'); ?></label>
				<p class="description"><?php _e('The time your cache may last in minutes (this setting will be ignored when your cache is disabled).', 'acf-flickr');?></p>
			</td>
			<td>
				<?php		
				do_action('acf/create_field', array(
					'type'		=>	'text',
					'name'		=>	'fields['.$key.'][flickr_cache_duration]',
					'value'		=>	$field['flickr_cache_duration'],
				));		
				?>
			</td>
		</tr>


		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e( 'Thumbnail size', 'acf-flickr' );?></label>
				<p class="description"><?php _e('The size of the photo thumbnail.', 'acf-flickr');?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][flickr_thumb_size]',
					'value'	=>	$field['flickr_thumb_size'],
					'choices' => array(
						'square'     => '75x75 (square)',
						'thumbnail'  => '100px (rectangle)',
						'square_150' => '150x150 (square)',
						'small_240'  => '240px (rectangle)',
						'small_320'  => '320px (rectangle)',
					)
				));
				?>
		   </td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e( 'Large size', 'acf-flickr' );?></label>
				<p class="description"><?php _e('The preferred width of the large photo.', 'acf-flickr');?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][flickr_large_size]',
					'value'	=>	$field['flickr_large_size'],
					'choices' => array(
						'medium_640'   => '640px',
						'medium_800'   => '800px',
						'large_1024'   => '1024px',
						//'large_1600'   => '1600px',
						'original'     => 'Original',
					)
				));
				?>
		   </td>
		</tr>
		<?php
		
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field ) {
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
			$items = json_decode($field['value']['items']);

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
		echo '<select id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' data-max-selectable="'. $field['flickr_max_selected'] .'" data-flickr-type="'. $field['flickr_content_type'] .'">';	
		
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
	*  Use this action to add CSS + JavaScript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// register ACF scripts
		wp_register_script( 'acf-flickr-init', $this->settings['dir'] . 'js/flickr-acf4.js', array('acf-input'), $this->settings['version'] );
		wp_register_style( 'acf-input-flickr', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] ); 
		
		// scripts
		wp_enqueue_script(array(
			'acf-flickr-init',	
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-flickr',	
		));
		
		
	}
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
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
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in the database
	*/
	
	function load_value( $value, $post_id, $field )
	{
		$data = array();

		$data['items']      = $value;
		$data['type']       = $field['flickr_content_type'];
		$data['thumb_size'] = $field['flickr_thumb_size'];
		$data['large_size'] = $field['flickr_large_size'];
		$data['user_id']    = $field['flickr_user_id'];
		$data['api_key']    = $field['flickr_api_key'];

		return $data;
	}
	
	/*
	*  format_value_for_api()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field )
	{
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
				// Get photos from Flickr based on set/gallery id
				$sets = array();
				foreach($value['items'] as $id) {
					if ($value['type'] == 'sets') {
						$photos = $f->photosets_getPhotos($id, 'url_o');
						$name = 'photoset';
					} 
					elseif ($value['type'] == 'galleries') {
						$photos = $f->galleries_getPhotos($id, 'url_o');
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
