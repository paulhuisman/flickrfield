<?php

/*
 *	Advanced Custom Fields - Flickr field
 *
 *	Author: Paul Huisman
 *  Author website: www.paulhuisman.com
 *
 */
 
 
class Flickr_field extends acf_Field
{

	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*	- This function is called when the field class is initalized on each page.
	*	- Here you can add filters / actions and setup any other functionality for your field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function __construct($parent)
	{
    	parent::__construct($parent);
    	
    	$this->name = 'Flickr_field';
			$this->title = __("Flickr Field",'acf'); 
		
   	}

	
	/*--------------------------------------------------------------------------------------
	*
	*	create_options
	*	- this function is called from core/field_meta_box.php to create extra options
	*	for your field
	*
	*	@params
	*	- $key (int) - the $_POST obejct key required to save the options to the field
	*	- $field (array) - the field object
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_options($key, $field)
	{
		// defaults
		$field['user_id'] 		 = isset($field['user_id']) ? $field['user_id'] : '';
		$field['api_key']		 = isset($field['api_key']) ? $field['api_key'] : '';
		$field['flickr_content'] = isset($field['flickr_content']) ? $field['flickr_content'] : 'sets';
		
		
		// Check if there's an flickr_api_key set in options
		$flick_api_key = get_option('flickr_api_key');
		if (!empty($flick_api_key) && empty($field['api_key'])) {
			$field['api_key'] = $flick_api_key;
		}
		?>
		
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><span class="required">*</span><?php _e("Type of Flickr content",'acf'); ?></label>
				<p class="description">What do you want to display?</p>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
						'type'	=>	'radio',
						'default' => 'sets',
						'name'	=>	'fields['.$key.'][flickr_content]',
						'value'	=>	$field['flickr_content'],
						'choices' => array(
							'sets'		=>	'Sets',
							'galleries'	=>	'Galleries',
						),
						'layout'	=>	'horizontal',
					)
				);
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><span class="required">*</span><?php _e("Flickr user ID",'acf'); ?></label>
				<p class="description">Get your user ID at <a href="http://idgettr.com/" target="_blank">http://idgettr.com/</a></p>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'text',
					'name'	=>	'fields['.$key.'][user_id]',
					'value'	=>	$field['user_id'],
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php if (empty($flick_api_key)): ?><span class="required">*</span><?php endif; ?><?php _e("Flickr API Key",'acf'); ?></label>
				<p class="description">
					<?php if (!empty($flick_api_key)):
						_e('Flickr API key found in options. You can leave this field blank.');
					else: ?>
						<a href="http://www.flickr.com/services/api/misc.api_keys.html" target="_blank">Get a Flickr API key</a>
					<?php endif; ?>
				</p>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'text',
					'name'	=>	'fields['.$key.'][api_key]',
					'value'	=>	$field['api_key'],
				));
				?>
			</td>
		</tr>
		<?php
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	pre_save_field
	*	- this function is called when saving your acf object. Here you can manipulate the
	*	field object and it's options before it gets saved to the database.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function pre_save_field($field)
	{
		// do stuff with field (mostly format options data)
		
		return parent::pre_save_field($field);
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*	- this function is called on edit screens to produce the html for this field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_field($field)
	{
		// Defaults
		$field['value'] = isset($field['value']) ? $field['value'] : array();
		$field['optgroup'] = isset($field['optgroup']) ? $field['optgroup'] : false;
		
		// Get all Flickr sets by the given user ID and api key (both required)
		require_once(dirname(__FILE__) . '/phpFlickr.php');
		$f = new phpFlickr($field['api_key']);
		
		// Optionally enable the phpFlickr cache - make sure the folder cache is properly placed
		// $f->enableCache("fs", dirname(__FILE__) . '/cache');		
		
		// Get all sets by user ID ordered by post_date desc and limit to max 50 sets
		$user_id = $field['user_id'];
		
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
					// Check what kind of Flickr content should be displayed
					switch($field['flickr_content']) {
						case 'galleries':
						
							$flickr_data = $f->galleries_getList($user_id, 50, 1);
							if (is_array($flickr_data) && !empty($flickr_data)) {
								foreach($flickr_data['galleries']['gallery'] as $key => $flickr) {
									?>
									<tr class="field_label flickr_row <?php if ($field['value'] == $flickr['id']) echo 'active-row'; ?>" data-flickr-id="<?php echo $flickr['id']; ?>">
										<td class="label set_image">
											<img title="<?php echo $flickr['title'];?>" src="http://farm<?php echo $flickr['primary_photo_farm'];?>.static.flickr.com/<?php echo $flickr['primary_photo_server'];?>/<?php echo $flickr['primary_photo_id'];?>_<?php echo $flickr['primary_photo_secret'];?>_s.jpg">
										</td>
										<td class="set_info">
											<p class="set_title"><?php echo $flickr['title'];?></p>
											<p class="description"><?php echo $flickr['description'];?></p>
											<p class="meta_data">
												<?php _e('Added on');?> <?php echo date_i18n(get_option('date_format') ,$flickr['date_create']); echo '&nbsp;|&nbsp;';
												echo $flickr['count_views'];?> <?php _e('views on Flickr'); 
												echo '&nbsp;|&nbsp;';
												echo $flickr['count_photos'];?> <?php _e('Photos');
												if ($flickr['count_videos'] != 0) {
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
									<td colspan="2"><?php _e('There are no Flickr galleries available for user ID'); ?> <?php echo $user_id; ?> <?php _e('or there is a problem with API KEY'); ?> <?php echo $field['api_key']; ?></td>
								</tr><?php
							}
						break;
							
						default:
							$flickr_data = $f->photosets_getList($user_id, 50, 1);
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
									<td colspan="2"><?php _e('There are no Flickr sets available for user ID'); ?> <?php echo $user_id; ?> <?php _e('or there is a problem with API KEY'); ?> <?php echo $field['api_key']; ?></td>
								</tr><?php
							}							
						break;
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
		echo '<select id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' >';	
		
		// null
		if($field['allow_null'] == '1')
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
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	admin_head
	*	- this function is called in the admin_head of the edit screen where your field
	*	is created. Use this function to create css and javascript to assist your 
	*	create_field() function.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_head()
	{
		?>
		<style type="text/css">
			.field_form.flickr_field table.acf_input tbody
											  { -webkit-border-radius: 5px; border-radius: 5px; }
			.flickr_row  			     	  { cursor:pointer; }
			.flickr_row .set_info  	  { border-top:1px solid #dfdfdf; }
			.flickr_row:hover				  { background-color:#f0f0f0;}
			.field_label .label.set_image	  { width:75px; position:relative; background:#fff; border-top:1px solid #dfdfdf; }
			.flickr_row:hover .set_image,
			.active-row .label.set_image  	  { width:79px; padding:8px; }
			.flickr_row:hover .set_image img  { padding:1px; border:1px solid #aaa; }
			.active-row:hover .set_image img,
			.active-row .set_image img    	  { padding:1px; border:1px solid #888; }
			.label.set_image img		  	  { display:block; }
			.flickr_row .set_title		  	  { margin:0 0 8px 0; font-weight:bold; padding:0; font-size:13px; color:#333; }
			.flickr_row .meta_data,
			.flickr_row .meta_data a		  { color:#777; font-size:11px;}
			.field_form.flickr_field {
				border:1px solid #c8c8c8; 
				background:#e8e8e8; 
			    background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#e8e8e8), to(#e1e1e1));
			    background-image: -webkit-linear-gradient(top, #e8e8e8, #e1e1e1); 
			    background-image: -moz-linear-gradient(top, #e8e8e8, #e1e1e1);
			    background-image: -ms-linear-gradient(top, #e8e8e8, #e1e1e1);
			    background-image: -o-linear-gradient(top, #e8e8e8, #e1e1e1);
			}
			.flickr_row.active-row,
			.flickr_row.active-row .label.set_image {
				background-color: #2483b9; 
				background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#119bc9), to(#2483b9));
				background-image: -webkit-linear-gradient(top, #119bc9, #2483b9); 
				background-image: -moz-linear-gradient(top, #119bc9, #2483b9);
				background-image: -ms-linear-gradient(top, #119bc9, #2483b9);
				background-image: -o-linear-gradient(top, #119bc9, #2483b9);
			}
			.flickr_row.active-row 	.set_title,	  	  
			.flickr_row.active-row 	.description,	  	  
			.flickr_row.active-row 	.meta_data	      { color:#fff}
			.active-row:hover .set_image img,
			.active-row .set_image img    	          { padding:1px; border:1px solid #fff; }
			.flickr_row.active-row .meta_data   	  { color: #a8d9f0; }
			.flickr_row:hover td.label.set_image 	  { border-right:1px solid #c8c8c8; background-color:#f0f0f0;  }
			.flickr_row.active-row td.label.set_image { border-right:1px solid #3d9ed1; }
			.flickr_row p.description 				  { color:#444; font-style:normal; }
		</style>
		<script type="text/javascript">
			jQuery(function($) {
				
				$('.flickr_field').each(function() {
					var self = $(this).parent(),
						input;

					
					$('select option:selected', self).each(function() {
						value = $(this).val();
					});

					$('.flickr_row', self).click(function() {
						// Deselect if active
						if ($(this).hasClass('active-row')) {
							$(this).removeClass('active-row');
							input.val(0);
						}
						else {
							$('.flickr_row', self).removeClass('active-row');
							$(this).addClass('active-row');
							
							// adjust value of the input hidden field
							input.val($(this).attr('data-flickr-id'));
						}
					});
					
					var value = '';
					
					if ($('.flickr_row',self).hasClass('active-row')) {
						value = $('.active-row', self).attr('data-flickr-id');
					}
					
					// Make hidden input with set/gallery id
					input = $('<input />').attr('type', 'hidden').val(value).attr('name',$('select', self).attr('name')).addClass('flickr-id');
					
					// Remove default select
					$('select', self).after(input).remove();	
					
				});
		
			});
		</script>
		<?php 
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	admin_print_scripts / admin_print_styles
	*	- this function is called in the admin_print_scripts / admin_print_styles where 
	*	your field is created. Use this function to register css and javascript to assist 
	*	your create_field() function.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_print_scripts()
	{
	
	}
	
	function admin_print_styles()
	{
		
	}

	
	/*--------------------------------------------------------------------------------------
	*
	*	update_value
	*	- this function is called when saving a post object that your field is assigned to.
	*	the function will pass through the 3 parameters for you to use.
	*
	*	@params
	*	- $post_id (int) - usefull if you need to save extra data or manipulate the current
	*	post object
	*	- $field (array) - usefull if you need to manipulate the $value based on a field option
	*	- $value (mixed) - the new value of your field.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function update_value($post_id, $field, $value)
	{
		// do stuff with value
		
		// save value
		parent::update_value($post_id, $field, $value);
	}
	
	
	
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value
	*	- called from the edit page to get the value of your field. This function is useful
	*	if your field needs to collect extra data for your create_field() function.
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value($post_id, $field)
	{
		$value = parent::get_value($post_id, $field);
		
		$value = htmlspecialchars($value, ENT_QUOTES);
		
		return $value;
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value_for_api
	*	- called from your template file when using the API functions (get_field, etc). 
	*	This function is useful if your field needs to format the returned value
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value_for_api($post_id, $field)
	{
		// vars
		$value = array();
		$value['id'] = parent::get_value($post_id, $field);
		$value['user_id'] = $field['user_id'];
		$value['api_key'] = $field['api_key'];
		$value['flickr_content'] = $field['flickr_content'];

		
		return $value;
	}
	
}

?>