jQuery(document).ready(function($) {
	
	if ($('.parallax-banner').length ) {

		$('#pusher').on('scroll', function() {

			var $scrollTop = $('#pusher').scrollTop();
			var $bannerHeight = $('.parallax-banner').height();
			if ($scrollTop < $bannerHeight ) {

				$bannerFromTop = Math.pow(( ( $bannerHeight - $scrollTop ) / $bannerHeight ), 1.2);
				
				// $('.site-logo').text($bannerFromTop);
		        $('.page-banner-bg').velocity({
		        	translateZ: 0,
		            translateY: $scrollTop * 0.8+"px"
		        },{ duration: 0 });

		        $('.page-banner-content').velocity({
		        	translateZ: 0,
		            translateY: $scrollTop * 0.7+"px",
		            opacity: $bannerFromTop
		        },{ duration: 0 });
			       
			}
	    
		});

	}
	
});


