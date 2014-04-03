(function($){

	
	/*
	*  acf/setup_fields (ACF4)
	*
	*  This event is triggered when ACF adds any new elements to the DOM. 
	*
	*  @type	function
	*  @since	1.0.0
	*  @date	01/01/12
	*
	*  @param	event		e: an event object. This can be ignored
	*  @param	Element		postbox: An element which contains the new HTML
	*
	*  @return	n/a
	*/
	
	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.field[data-field_type="FIELD_NAME"]').each(function(){
			
			initialize_field( $(this) );
			
		});

		$('.field_form.flickr_field').each(function() {
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
					
					// Adjust value of the input hidden field
					input.val($(this).attr('data-flickr-id'));
				}
			});
			
			var value = '';
			
			if ($('.flickr_row', self).hasClass('active-row')) {
				value = $('.active-row', self).attr('data-flickr-id');
			}
			
			// Make hidden input with set/gallery id
			input = $('<input />').attr('type', 'hidden').val(value).attr('name',$('select', self).attr('name')).addClass('flickr-id');
			
			// Remove default select
			$('select', self).after(input).remove();	
		});

		
	
	});


})(jQuery);
