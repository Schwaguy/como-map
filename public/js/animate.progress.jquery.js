// Animate map progress bars
jQuery(document).ready(function($) {
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
});