// Tabby Plugin
!function(t){function e(t,e,r){var g=t.scrollTop;t.setSelectionRange?n(t,e,r):document.selection&&a(t,e,r),t.scrollTop=g}function n(t,e,n){var a=t.selectionStart,r=t.selectionEnd;if(a===r)e?a-n.tabString===t.value.substring(a-n.tabString.length,a)?(t.value=t.value.substring(0,a-n.tabString.length)+t.value.substring(a),t.focus(),t.setSelectionRange(a-n.tabString.length,a-n.tabString.length)):a-n.tabString===t.value.substring(a,a+n.tabString.length)&&(t.value=t.value.substring(0,a)+t.value.substring(a+n.tabString.length),t.focus(),t.setSelectionRange(a,a)):(t.value=t.value.substring(0,a)+n.tabString+t.value.substring(a),t.focus(),t.setSelectionRange(a+n.tabString.length,a+n.tabString.length));else{for(;a<t.value.length&&t.value.charAt(a).match(/[ \t]/);)a++;var g=t.value.split("\n"),l=[],i=0,s=0,b=0;for(b in g)s=i+g[b].length,l.push({start:i,end:s,selected:a>=i&&s>a||s>=r&&r>i||i>a&&r>s}),i=s+1;var o=0;for(b in l)if(l[b].selected){var u=l[b].start+o;e&&n.tabString===t.value.substring(u,u+n.tabString.length)?(t.value=t.value.substring(0,u)+t.value.substring(u+n.tabString.length),o-=n.tabString.length):e||(t.value=t.value.substring(0,u)+n.tabString+t.value.substring(u),o+=n.tabString.length)}t.focus();var c=a+(o>0?n.tabString.length:0>o?-n.tabString.length:0),h=r+o;t.setSelectionRange(c,h)}}function a(e,n,a){var r=document.selection.createRange();if(e===r.parentElement())if(""===r.text)if(n){var g=r.getBookmark();r.moveStart("character",-a.tabString.length),a.tabString===r.text?r.text="":(r.moveToBookmark(g),r.moveEnd("character",a.tabString.length),a.tabString===r.text&&(r.text="")),r.collapse(!0),r.select()}else r.text=a.tabString,r.collapse(!1),r.select();else{var l=r.text,i=l.length,s=l.split("\r\n"),b=document.body.createTextRange();b.moveToElementText(e),b.setEndPoint("EndToStart",r);var o=b.text,u=o.split("\r\n"),c=o.length,h=document.body.createTextRange();h.moveToElementText(e),h.setEndPoint("StartToEnd",r);var S=h.text,f=document.body.createTextRange();f.moveToElementText(e),f.setEndPoint("StartToEnd",b);var d=f.text,v=t(e).html();t("#r3").text(c+" + "+i+" + "+S.length+" = "+v.length),c+d.length<v.length?(u.push(""),c+=2,n&&a.tabString===s[0].substring(0,a.tabString.length)?s[0]=s[0].substring(a.tabString.length):n||(s[0]=a.tabString+s[0])):n&&a.tabString===u[u.length-1].substring(0,a.tabString.length)?u[u.length-1]=u[u.length-1].substring(a.tabString.length):n||(u[u.length-1]=a.tabString+u[u.length-1]);for(var m=1;m<s.length;m++)n&&a.tabString===s[m].substring(0,a.tabString.length)?s[m]=s[m].substring(a.tabString.length):n||(s[m]=a.tabString+s[m]);1===u.length&&0===c&&(n&&a.tabString===s[0].substring(0,a.tabString.length)?s[0]=s[0].substring(a.tabString.length):n||(s[0]=a.tabString+s[0])),c+i+S.length<v.length&&(s.push(""),i+=2),b.text=u.join("\r\n"),r.text=s.join("\r\n");var T=document.body.createTextRange();T.moveToElementText(e),c>0?T.setEndPoint("StartToEnd",b):T.setEndPoint("StartToStart",b),T.setEndPoint("EndToEnd",r),T.select()}}t.fn.tabby=function(n){var a=t.extend({},t.fn.tabby.defaults,n),r=t.fn.tabby.pressed;return this.each(function(){var n=t(this),g=t.meta?t.extend({},a,n.data()):a;n.bind("keydown",function(n){var a=t.fn.tabby.catch_kc(n);return 16===a&&(r.shft=!0),17===a&&(r.ctrl=!0,setTimeout(function(){t.fn.tabby.pressed.ctrl=!1},1e3)),18===a&&(r.alt=!0,setTimeout(function(){t.fn.tabby.pressed.alt=!1},1e3)),9!==a||r.ctrl||r.alt?void 0:(n.preventDefault(),r.last=a,setTimeout(function(){t.fn.tabby.pressed.last=null},0),e(t(n.target).get(0),r.shft,g),!1)}).bind("keyup",function(e){16===t.fn.tabby.catch_kc(e)&&(r.shft=!1)}).bind("blur",function(e){9===r.last&&t(e.target).one("focus",function(){r.last=null}).get(0).focus()})})},t.fn.tabby.catch_kc=function(t){return t.keyCode?t.keyCode:t.charCode?t.charCode:t.which},t.fn.tabby.pressed={shft:!1,ctrl:!1,alt:!1,last:null},t.fn.tabby.defaults={tabString:String.fromCharCode(9)}}(jQuery);

jQuery(document).ready(function($) {
	
	$('#instant-ide-manager-htaccess-open-button').click(function() {
		$('body').addClass('instant-ide-htaccess-editor-active');
		$('#instant-ide-manager-htaccess-open-button .instant-ide-manager-ajax-save-spinner').show();
		jQuery.ajax({
			type : 'POST',
			url : ajaxurl,
			data : {
				action : 'instant_ide_manager_htaccess_open',
				security : iidemAdminL10n.iideAjaxNonce
			},
			success : function(response) {
				if(response) {
					$('.instant-ide-manager-ajax-save-spinner').hide();
					$('#instant-ide-manager-htaccess').val(response);
					$('#instant-ide-manager-htaccess-overlay').fadeIn('fast');
				}
			}
		});
	});

	$('#instant-ide-manager-htaccess-close').click(function() {
		$('body').removeClass('instant-ide-htaccess-editor-active');
		$('#instant-ide-manager-htaccess-overlay').fadeOut('fast');
	});
	
	$('#instant-ide-manager-htaccess-use-last-save').click(function() {
		$('#instant-ide-manager-htaccess-title .instant-ide-manager-ajax-save-spinner').show();
		jQuery.ajax({
			type : 'POST',
			url : ajaxurl,
			data : {
				action : 'instant_ide_manager_htaccess_restore',
				security : iidemAdminL10n.iideAjaxNonce
			},
			success : function(response) {
				if(response) {
					$('.instant-ide-manager-ajax-save-spinner').hide();
					$('#instant-ide-manager-htaccess').val(response);
				}
			}
		});
	});
	
	$('#instant-ide-manager-htaccess-form').submit(function() {
		$('#instant-ide-manager-htaccess-button-container .instant-ide-manager-ajax-save-spinner').show();
		var data = $(this).serialize();
		jQuery.post(ajaxurl, data, function(response) {
			if(response) {
				$('.instant-ide-manager-ajax-save-spinner').hide();
				$('.instant-ide-manager-saved').html(response).fadeIn('slow');
				window.setTimeout(function() {
					$('.instant-ide-manager-saved').fadeOut('slow'); 
				}, 2000);
			}
		});
		return false;
	});
	
	$('#instant-ide-manager-admin-access-pin-form').submit(function() {
		$('#instant-ide-manager-admin-access-pin-form-input-container .instant-ide-manager-ajax-save-spinner').show();
		var data = $(this).serialize();
		jQuery.post(ajaxurl, data, function(response) {
			if(response) {
				$('.instant-ide-manager-ajax-save-spinner').hide();
				$('.instant-ide-manager-saved').html(response).fadeIn('slow');
				window.setTimeout(function() {
					location.reload();
				}, 2000);
			}
		});
		return false;
	});
	
	$('#instant-ide-manager-admin-access-check-form').submit(function() {
		$('#instant-ide-manager-admin-access-check-form-input-container .instant-ide-manager-ajax-save-spinner').show();
		var data = $(this).serialize();
		jQuery.post(ajaxurl, data, function(response) {
			if(response) {
				$('.instant-ide-manager-ajax-save-spinner').hide();
				$('.instant-ide-manager-saved').html(response).fadeIn('slow');
				window.setTimeout(function() {
					$('.instant-ide-manager-saved').fadeOut('slow');
					if(response == 'Pin Verified!') {
						location.reload();
					}
				}, 2000);
			}
		});
		return false;
	});
	
	$(window).keydown(function(e) {
        if($('body').hasClass('instant-ide-htaccess-editor-active')) {
	        if(e.which == 27) {
				$('#instant-ide-manager-htaccess-close').click();
	        }
        }
	});
	
	$('#instant-ide-manager-htaccess').tabby();
	
	$('#instant-ide-manager-install-button').click(function() {
		$('#instant-ide-manager-ajax-install-spinner-container .instant-ide-manager-ajax-save-spinner').show();
		jQuery.ajax({
			type : 'POST',
			url : ajaxurl,
			data : {
				action : 'instant_ide_manager_iide_install',
				dir : $('#instant-ide-manager-install-directory-name').text(),
				security : iidemAdminL10n.iideAjaxNonce
			},
			success : function(response) {
				if(response) {
					location.reload();
				}
			}
		});
	});
	
	$('#instant-ide-manager-uninstall-button').click(function() {
		if(confirm('Are you sure your want to uninstall Instant IDE?')) {
			if(confirm('Are you REALLY sure?')) {
				$('#instant-ide-manager-ajax-uninstall-spinner-container .instant-ide-manager-ajax-save-spinner').show();
				$('#instant-ide-manager-iide-viewer-iframe').attr('src', iidemAdminL10n.homeUrl+'/'+iidemAdminL10n.iideDirName+'/logout.php');
				setTimeout(function() {
					jQuery.ajax({
						type : 'POST',
						url : ajaxurl,
						data : {
							action : 'instant_ide_manager_iide_uninstall',
							security : iidemAdminL10n.iideAjaxNonce
						},
						success : function(response) {
							if(response) {
								location.reload();
							}
						}
					});
				}, 1000);
			}
		}
	});
	
});