(function( $ ) {
	'use strict';
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */
	
	"use strict"; 
	// Add Animations to map on scroll
	var $map_elements = $('.exp-on-scroll');
	var $win = $(window);
	$win.on('scroll resize', check_if_in_view);
	$win.trigger('scroll');
	function check_if_in_view() {
		var win_height = $win.height();
		var win_top_position = $win.scrollTop();
		var win_bottom_position = (win_top_position + win_height);
		$.each($map_elements, function() {
			var $elmnt = $(this);
			var elmnt_height = $elmnt.outerHeight();
			var elmnt_top_position = $elmnt.offset().top;
			var elmnt_bottom_position = (elmnt_top_position + elmnt_height);
			if ((elmnt_bottom_position >= win_top_position) && (elmnt_top_position <= win_bottom_position)) {
				//showProgress();
				var bar = jQuery(this).find('.progress');
				var prog = jQuery(bar).children('.progress-bar');
				setTimeout( function(){
					$(prog).addClass('expanded');
					if ($(prog).hasClass('no-repeat')) {
						$(prog).removeClass('expanded');
					}
				}, 1000);	
			} 
		});
	}
})( jQuery );
