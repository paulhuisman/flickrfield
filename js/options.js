jQuery(function($){
  $('.flickr_private_mode_select').each(function() {
    var $wrap = $(this);
    $('input:radio', this).each(function() {
      if ($(this).is(':checked') && $(this).val() == '1') {
        $wrap.parent().parent().parent().find('[data-name="flickr_secret_key"]').css('display', 'table-row');
        $wrap.parent().parent().parent().find('[data-name="flickr_private_token"]').css('display', 'table-row');
      }
    });
  });

  $('.flickr_cache_select').each(function() {
    var $wrap = $(this);
    $('input:radio', this).each(function() {
      if ($(this).is(':checked') && $(this).val() == '1') {
        $wrap.parent().parent().parent().find('[data-name="flickr_cache_duration"]').css('display', 'table-row');
        $wrap.parent().parent().parent().find('[data-name="flickr_admin_cache_duration"]').css('display', 'table-row');
      }
    });
  });

  $(document).on('change', '.flickr_private_mode_select', function(e) {
    // Toggle hidden flickr private fields
    $(this).parent().parent().parent().find('[data-name="flickr_secret_key"]').toggle();
    $(this).parent().parent().parent().find('[data-name="flickr_private_token"]').toggle();
  });

  $(document).on('change', '.flickr_cache_select', function(e) {
    // Toggle hidden cache field(s)
    $(this).parent().parent().parent().find('[data-name="flickr_cache_duration"]').toggle();
    $(this).parent().parent().parent().find('[data-name="flickr_admin_cache_duration"]').toggle();
  });

});