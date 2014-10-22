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
	*  ready append (ACF5)
	*
	*  These are 2 events which are fired during the page load
	*  ready = on page load similar to $(document).ready()
	*  append = on new DOM elements appended via repeater field
	*
	*  @type	event
	*  @date	20/07/13
	*
	*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
	*  @return	n/a
	*/
	
	acf.add_action('ready append', function( $el ){
		
		// search $el for fields of type 'flickr'
		acf.get_fields({ type : 'flickr'}, $el).each(function(){
			
			initialize_flickrfield( $(this) );
			
		});
		
	});
	
})(jQuery);


