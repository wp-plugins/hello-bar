jQuery(document).ready(
	function($) {
		
		function goHelloBar() {
			var top = $('#hello-bar').css('height');
			var time = 5000;
			//console.log(top);
			
			$('#hello-bar').removeClass('no-js').delay(time).animate({top: '0'});
			$('body').delay(time).animate({paddingTop: top});
			$('#hello-bar-container .toggle a').delay(time).toggle();
			
			$('#hello-bar-container .open').click(function () {
				$('#hello-bar').slideDown();
				$('body').animate({paddingTop: top});
				$('#hello-bar-container .tab').addClass('open')
			});
			$('#hello-bar-container .close').click(function () {
				$('#hello-bar').slideUp('fast');
				$('body').animate({paddingTop: '0'});
				$('#hello-bar-container .tab').removeClass('open')
			});
			$('#hello-bar-container .toggle a').click(function () {
				$('#hello-bar-container .toggle a').toggle()
			})
			
			//Pre-caution
			$('#hello-bar .branding').delay(time).show();
			
		}
		goHelloBar();
		
		// External links
		$('#hello-bar a').filter(function() {
			return this.hostname && this.hostname !== location.hostname;
		}).attr('target','_blank');
	
	}
);