(function($){

	function initialize_flickrfield( $el ) {

		function fill_input_value() {
			var active_items = new Array();

			$('.flickr_row.active-row', $el).each(function() {
				if ($(this).hasClass('photo_image')) {
					active_items.push({
						id: $(this).attr('data-flickr-id'), 
						server: $(this).attr('data-flickr-server'), 
						secret: $(this).attr('data-flickr-secret'), 
						farm: $(this).attr('data-flickr-farm'), 
						title: $(this).attr('data-flickr-title')
					});	
				} 
				else {
					active_items.push($(this).attr('data-flickr-id'));	
				}
				
			});

			if (active_items.length > 0) {
				input.val(JSON.stringify(active_items));
			}
		} 

		var input;

		$('select option:selected', $el).each(function() {
			value = $(this).val();
		});

		$('.flickr_row', $el).click(function(e) {
			e.preventDefault();
			// Deselect if active
			if ($(this).hasClass('active-row')) {
				$(this).removeClass('active-row');
			}
			else {
				$(this).addClass('active-row');
			}

			fill_input_value();
		});

		// Make hidden input with flickr data
		input = $('<input />').attr('type', 'hidden').val(value).attr('name',$('select', $el).attr('name')).addClass('flickr-id');	

		// Remove default select
		$('select', $el).after(input).remove();	

		fill_input_value();
	
	}


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
		
		$(postbox).find('.field[data-field_type="flickr"]').each(function(){
			
			initialize_flickrfield( $(this) );
			
		});
	
	});


})(jQuery);


