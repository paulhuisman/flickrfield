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
		$this->category = __("Flickr",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			// add default here to merge into your field. 
			'flickr_api_key'     => '',
			'flickr_user_id'     => '',
			'flickr_content'     => 'sets',
			'flickr_sets_amount' => '30',
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
				<label><?php _e('Flickr User ID','acf'); ?></label>
				<p class="description"><a href="http://idgettr.com/">http://idgettr.com/</a></p>
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
				<label><?php _e('Flickr API Key','acf'); ?></label>
				<p class="description"><?php _e('Find or register your API key at http://www.flickr.com/services/apps/by/[USERNAME]', 'acf');?></p>
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
				<label><?php _e( 'Flickr set display amount', 'acf' );?></label>
				<p class="description"><?php _e('Number of sets to display. The most recent made sets will be shown first.', 'acf');?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][flickr_sets_amount]',
					'value'	=>	$field['flickr_sets_amount'],
					'choices' => array(
						'10' =>'10',
						'20' =>'20',
						'30' =>'30',
						'40' =>'40',
						'50' =>'50',
						'100' =>'100',
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
		
		// Optionally enable the phpFlickr cache - make sure the folder cache is properly placed
		$f->enableCache("fs", dirname(__FILE__) . '/cache');		
				
		// Include fields.css from the ACF plugin for some more styling
		wp_register_style('fields-css',get_bloginfo('wpurl'). '/wp-content/plugins/advanced-custom-fields/css/fields.css');
		wp_enqueue_style('fields-css');
		
		$field['choices'] = array();
		$field['choices'][''] = '';
		?>
		
		<div class="field_form flickr_field">
			<table class="acf_input widefat acf_field_form_table">
				<tbody>
					<?php		
					$flickr_data = $f->photosets_getList($field['flickr_user_id'], $field['flickr_sets_amount'], 1);

					if (is_array($flickr_data) && !empty($flickr_data)) {
						foreach($flickr_data['photoset'] as $key => $flickr) {
							?>
							<tr class="field_label flickr_row <?php if ($field['value'] == $flickr['id']) echo 'active-row'; ?>" data-flickr-id="<?php echo $flickr['id']; ?>">
								<td class="label set_image">
									<img title="<?php echo $flickr['title'];?>" src="http://farm<?php echo $flickr['farm'];?>.static.flickr.com/<?php echo $flickr['server'];?>/<?php echo $flickr['primary'];?>_<?php echo $flickr['secret'];?>_s.jpg">
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
			echo '<p>' . __("No choices to choose from",'acf') . '</p>';
			return false;
		}
		
		// html
		if (!isset($multiple)) { $multiple = ''; }
		echo '<select id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' >';	
		
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
		wp_register_script( 'acf-input-flickr', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version'] );
		wp_register_style( 'acf-input-flickr', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] ); 
		
		
		// scripts
		wp_enqueue_script(array(
			'acf-input-flickr',	
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-flickr',	
		));
		
		
	}
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your create_field() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_head()
	{
		// Note: This function can be removed if not used
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

	function field_group_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
	}

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your create_field_options() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_head()
	{
		// Note: This function can be removed if not used
	}


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
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/
	
	function update_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
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
	
	function format_value( $value, $post_id, $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the $value?
		
		
		// Note: This function can be removed if not used
		return $value;
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
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the $value?
		
		
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/
	
	function load_field( $field )
	{
		// Note: This function can be removed if not used
		return $field;
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field, $post_id )
	{
		// Note: This function can be removed if not used
		return $field;
	}

	
}


// create field
new acf_field_flickr();

?>
