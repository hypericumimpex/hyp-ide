jQuery(document).ready(function($) {
	
	if(window.self !== window.top) {
		$('head').find('link[rel="stylesheet"]').each(function() {
			if(this.getAttribute('href').indexOf('iide_cache_buster') == -1) {
				var that = this;
				var clone = $(that).clone().insertAfter(that);
				setTimeout(function() {
					that.href = that.getAttribute('href')+(that.getAttribute('href').indexOf('?') > -1 ? '&' : '?')+'iide_cache_buster='+Date.now();
					setTimeout(function() {
						clone.remove();	
					}, 300);
				}, 0);
			}
		});
	}
	
});