(function($) {
	$(document).ready(function() {
		
		$('body').removeClass('instant-ide-loading');
		$('#instant-ide-file-editor-loading').hide();
		$('#instant-ide-file-editor-loaded').show();
		
		if(window.self !== window.top) {
			$('body').append('<div id="instant-ide-inside-iframe-overlay"><a href="'+iide_url+'" target="_blank">Click Here To Access Instant IDE In A New Tab</a><div class="instant-ide-login-logo-container"><img width="200" height="200" style="margin:0 auto;display:block;" src="'+iide_url+'assets/css/images/instant-ide-login-logo.png"></div></div>');
		}
		
		$.ajaxSetup({
		    headers : {
		        'iide-ajax-token': $('meta[name="iide-ajax-token"]').attr('content')
		    }
		});
		
		if(localStorage.getItem('iide_copy_file_folder_path') === null) {
			localStorage.setItem('iide_copy_file_folder_path', '');
		}
		if(localStorage.getItem('iide_copy_file_folder_action') === null) {
			localStorage.setItem('iide_copy_file_folder_action', '');
		}
		if(localStorage.getItem('iide_copy_file_folder_source') === null) {
			localStorage.setItem('iide_copy_file_folder_source', '');
		}
		
		localStorage.setItem('iide_active_editor', iide_active_editor);
		localStorage.setItem('iide_dev_path', iide_dev_path);
	
		if(localStorage.getItem('iide_file_tree_width') === null) {
			localStorage.setItem('iide_file_tree_width', 'md');
		}
		if(localStorage.getItem('iide_console_height') === null) {
			localStorage.setItem('iide_console_height', 'md');
		}
		if(localStorage.getItem('iide_site_preview_width') === null) {
			localStorage.setItem('iide_site_preview_width', 'md');
		}
		if(localStorage.getItem('iide_theme') === null) {
			localStorage.setItem('iide_theme', 'dark');
		}
		if(localStorage.getItem('iide_monaco_editor_theme') === null) {
			localStorage.setItem('iide_monaco_editor_theme', 'tomorrow-night-iide');
		}
		if(localStorage.getItem('iide_ace_editor_theme') === null) {
			localStorage.setItem('iide_ace_editor_theme', 'tomorrow_night_eighties');
		}
		
		if(localStorage.getItem('iide_word_wrap') === null) {
			localStorage.setItem('iide_word_wrap', 'off');
		}
		
		if(localStorage.getItem('iide_show_hidden_files') === null) {
			localStorage.setItem('iide_show_hidden_files', 'false');
		}
		
	    if(localStorage.getItem('iide_show_hidden_files') == 'true') {
	    	$('body').addClass('instant-ide-file-tree-show-hidden');
	    }
	    
	    var image_ext_array = ['gif', 'jpg', 'jpeg', 'png', 'svg', 'swf', 'psd', 'bmp', 'tiff', 'ico'];
	    
	    if($('body').hasClass('instant-ide-monaco-editor-active')) {
	    	
			require.config({ paths: { 'vs': 'assets/js/vs' }});
			
			function instant_ide_monaco_editor_build(textarea_id) {
				var textarea_id = textarea_id;
				require(['vs/editor/editor.main'], function() {
				    $('#'+textarea_id).each(function () {
						var textarea = $(this);
						var word_wrap = localStorage.getItem('iide_word_wrap');
						var mode = textarea.data('editor');
						textarea.css('display', 'none');
						var editor = monaco.editor.create(document.getElementById(textarea_id+'-container'), {
							value: [
								textarea.val()
							].join('\n'),
							wordWrap: word_wrap,
							language: mode
						});
						editor.onDidChangeModelContent(function(e) {
							textarea.val(editor.getValue());
							$('.instant-ide-file-editor-tab-active .instant-ide-file-editor-tab-quit').addClass('file-changed');
						});
						if(localStorage.getItem('iide_monaco_editor_theme') == 'tomorrow-night-iide') {
							monaco.editor.defineTheme('tomorrow-night-iide', {
								base: 'vs-dark', // can also be vs-dark or hc-black
								inherit: true, // can also be false to completely replace the builtin rules
								rules: [
									{ token: 'comment', foreground: '888888' },
									{ token: 'variable', foreground: '6392c3' },
									{ token: 'variable.predefined.php', foreground: 'df7072' },
									{ token: 'string', foreground: '98ca98' },
									{ token: 'metatag', foreground: 'cccccc' },
									{ token: 'tag', foreground: 'ea7477' },
									{ token: 'keyword', foreground: 'c091c0' },
									{ token: 'attribute.name', foreground: 'ee7578' },
									{ token: 'attribute.name.css', foreground: 'fcca65' },
									{ token: 'attribute.value', foreground: '98ca98' },
									{ token: 'keyword.css', foreground: 'cccccc' },
									{ token: 'attribute.value.css', foreground: 'f69056' },
									{ token: 'attribute.value.number.css', foreground: 'f69056' },
									{ token: 'attribute.value.hex.css', foreground: 'f69056' },
									{ token: 'attribute.value.unit.css', foreground: 'bb8dbb' }
								]
							});
						}
						monaco.editor.setTheme(localStorage.getItem('iide_monaco_editor_theme'));
						editor.layout({ width: $('#instant-ide-file-editor-container').width(), height: $('#instant-ide-file-editor-container').height() - 64});
						$(window).resize(function() {
							editor.layout({ width: $('#instant-ide-file-editor-container').width(), height: $('#instant-ide-file-editor-container').height() - 64});
						});
				    });
				});
			}
			
	    } else {
	    	
			function instant_ide_ace_editor_build(textarea_id) {
			    $(textarea_id).each(function () {
			        var textarea = $(this);
			        var word_wrap = localStorage.getItem('iide_word_wrap') == 'on' ? true : false;
			        var mode = textarea.data('editor');
			        var editDiv = $('<div>', {
			            position: 'absolute',
			            width: $('#instant-ide-file-editor-container').width(),
			            height: $('#instant-ide-file-editor-container').height() - 64,
			            'class': textarea.attr('class')
			        }).insertBefore(textarea);
			        textarea.css('display', 'none');
			        var editor = ace.edit(editDiv[0]);
			        editor.renderer.setShowGutter(true);
			        editor.setShowPrintMargin(false);
			        editor.getSession().setValue(textarea.val());
			        editor.getSession().setUseWrapMode(word_wrap);
			        editor.getSession().setMode('ace/mode/'+mode);
			        editor.setTheme('ace/theme/'+localStorage.getItem('iide_ace_editor_theme'));
					editor.setOptions({
						useWorker: true,
					    enableBasicAutocompletion: true,
				        enableLiveAutocompletion: true,
				        enableSnippets: true,
				        useSoftTabs: true
					});
					
					editor.getSession().on('change', function() {
						textarea.val(editor.getSession().getValue());
						$('.instant-ide-file-editor-tab-active .instant-ide-file-editor-tab-quit').addClass('file-changed');
					});
					$(window).keydown(function(e) {
						if((e.ctrlKey || e.metaKey) && e.which == 70) {
							editor.focus();
							e.preventDefault();
							return false;
						}
					});
			    });		
			}
			function resizeAce() {
				$('.ace_editor').width($('#instant-ide-file-editor-container').width());
				$('.ace_content').width($('#instant-ide-file-editor-container').width());
				$('.ace_editor').height($('#instant-ide-file-editor-container').height() - 64);
				$('.ace_content').height($('#instant-ide-file-editor-container').height() - 64);
			};
			// listen for changes
			$(window).resize(resizeAce);
			// set initially
			resizeAce();
			
	    }
		
		function instant_ide_error_message_popup(message) {
			swal({
				text: message,
				type: 'error'
			})
		}
		
		function instant_ide_conditional_delete_message_popup(message_title, message, key, element, e) {
			swal({
				title: message_title,
				text: message,
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				focusCancel: true
			}).then((result) => {
				if (result.value) {
					if(key == 'delete_file') {
						instant_ide_file_tree_right_click_delete_file_ajax(key, element, e);
					} else if(key == 'delete_folder') {
						instant_ide_file_tree_right_click_delete_folder_ajax(key, element, e);
					}
				}
			})
		}
		
		function instant_ide_conditional_unzip_delete_message_popup(message_title, message, key, element, e) {
			swal({
				title: message_title,
				text: message,
				type: 'success',
				showCancelButton: true,
				confirmButtonText: 'Yes',
				cancelButtonText: 'No'
			}).then((result) => {
				if (result.value) {
					instant_ide_file_tree_right_click_delete_file_ajax(key, element, e);
				}
			})
		}
		
		function instant_ide_conditional_install_message_popup(message_title, message, key, element, e) {
			swal({
				title: message_title,
				text: message,
				type: 'info',
				showCancelButton: true,
				confirmButtonText: 'Yes',
				cancelButtonText: 'No'
			}).then((result) => {
				if (result.value) {
					instant_ide_file_tree_right_click_install_ajax(key, element, e);
				}
			})
		}
		
		/* File Tree/Site Preview Effects */
		
		var preview_tree_delay = function(elem, callback) {
		    var timeout = null;
		    elem.onmouseover = function() {
		        // Set timeout to be a timer which will invoke callback after 1s
		        timeout = setTimeout(callback, 300);
		    };
		
		    elem.onmouseout = function() {
		        // Clear any timers set to timeout
		        clearTimeout(timeout);
		    }
		};
		
		preview_tree_delay(document.getElementById('instant-ide-file-tree-container'), function() {
			if($('body').hasClass('instant-ide-site-preview-active')) {
				$('body').addClass('instant-ide-site-preview-file-tree-active');
				$('.instant-ide-file-tree-cog').show();
				$(window).resize();
				if($('body').hasClass('instant-ide-ace-editor-active')) {
					resizeAce();
				}
			}
		});
		
		$('#instant-ide-file-editor-container').mouseenter(function() {
			if($('body').hasClass('instant-ide-site-preview-active')) {
				$('body').removeClass('instant-ide-site-preview-file-tree-active');
				$('.instant-ide-file-tree-cog').hide();
				$(window).resize();
				if($('body').hasClass('instant-ide-ace-editor-active')) {
					resizeAce();
				}
			}
		});
		
		/* Right-Click Actions */
		
	    $('#instant-ide-file-tree-container').on('mouseenter', 'li.iideft-file', function() {
	        $(this).addClass('instant-ide-file-tree-file-right-clickable');
	    }).on('mouseleave', 'li.iideft-file', function() {
	        $(this).removeClass('instant-ide-file-tree-file-right-clickable');
	    });
	    
	    $('#instant-ide-file-tree-container').on('mouseenter', 'li.iideft-directory', function() {
	    	$(this).addClass('instant-ide-file-tree-folder-right-clickable');
	    }).on('mouseleave', 'li.iideft-directory', function() {
	    	$(this).removeClass('instant-ide-file-tree-folder-right-clickable');
	    });
	    
		$.contextMenu({
			selector: '.instant-ide-file-editor-tab', 
			callback: function(key, options) { /* */ },
			items: {
				close_tab: {
					name: 'Close Tab',
					callback: function(key, opt, e) {
						$(this).find('.instant-ide-file-editor-tab-quit').click();
					}
				},
				close_all_tabs: {
					name: 'Close All Tabs',
					callback: function(key, opt, e) {
						$('.instant-ide-file-editor-tab-quit').click();
					}
				},
				close_other_tabs: {
					name: 'Close Other Tabs',
					callback: function(key, opt, e) {
						$('.instant-ide-file-editor-tab-quit').not($(this).find('.instant-ide-file-editor-tab-quit')).click();
					}
				},
			}
		});
		
        if(localStorage.getItem('iide_word_wrap') == 'off') {
        	var cm_word_wrap_name = 'Enable Word Wrap';
        } else {
        	var cm_word_wrap_name = 'Disable Word Wrap';
        }
		
		$.contextMenu({
			selector: '.instant-ide-file-editor-cog', 
			trigger: 'left',
			callback: function(key, options) { /* */ },
			items: {
				refresh_file_tree: {
					name: cm_word_wrap_name,
					callback: function() {
	                    if(localStorage.getItem('iide_word_wrap') == 'off') {
	                    	localStorage.setItem('iide_word_wrap', 'on');
	                    	$('.context-menu-item > span:contains("able Word Wrap")').text('Disable Word Wrap');
	                    } else {
	                    	localStorage.setItem('iide_word_wrap', 'off');
	                    	$('.context-menu-item > span:contains("able Word Wrap")').text('Enable Word Wrap');
	                    }
					}
				},
			}
		});
		
        if(localStorage.getItem('iide_show_hidden_files') == 'false') {
        	var cm_hidden_files_name = 'Show Hidden Files';
        } else {
        	var cm_hidden_files_name = 'Hide Hidden Files';
        }
	    
		$.contextMenu({
			selector: '.instant-ide-file-tree-cog', 
			trigger: 'left',
			callback: function(key, options) { /* */ },
			items: {
				refresh_file_tree: {
					name: 'Refresh File Tree',
					callback: function() {
						$('#iideft-root-directory').load(window.location.href+' #iideft-root-directory > *', function() {
							$('.instant-ide-file-tree').find('ul').slice(1).hide();
						});
					}
				},
				collapse_folders: {
					name: 'Collapse Folders',
					callback: function() {
						$('.iideft-directory-open').dblclick();
						$('#iideft-root-directory > a').dblclick();
					}
				},
				'sep1': '---------',
				show_hidden_files: {
					name: cm_hidden_files_name,
	                callback: function() {
	                    if(localStorage.getItem('iide_show_hidden_files') == 'false') {
	                    	$('body').addClass('instant-ide-file-tree-show-hidden');
	                    	localStorage.setItem('iide_show_hidden_files', 'true');
	                    	$('.context-menu-item > span:contains(" Hidden Files")').text('Hide Hidden Files');
	                    } else {
	                    	$('body').removeClass('instant-ide-file-tree-show-hidden');
	                    	localStorage.setItem('iide_show_hidden_files', 'false');
	                    	$('.context-menu-item > span:contains(" Hidden Files")').text('Show Hidden Files');
	                    }
	                }
				}
			}
		});
	    
		$.contextMenu({
			selector: '#iideft-root-directory', 
			callback: function(key, options) { /* */ },
			items: {
				open_file: {
					name: 'Open',
					callback: function(key, opt, e) {
						$(this).find('a:first').dblclick();
					}
				},
				root_upload_file: {
					name: 'Upload Files',
					callback: function(key, opt, e) {
						$('#instant-ide-file-editor-upload-form').attr('title', instant_ide_get_rel_path($(this), e)).attr('alt', key);
						$('#instant-ide-file-editor-upload-form-container').fadeToggle('medium');
						$('#instant-ide-file-editor-upload-form-overlay').fadeToggle('medium');
						instant_ide_file_tree_right_click_upload_file_ajax(key, $(this));
					}
				},
				'sep1': '---------',
				paste_file: {
					name: 'Paste',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_paste_file_ajax(key, $(this), e);
						$('.instant-ide-file-tree li').css('opacity', '1');
					}
				},
				'sep2': '---------',
				folder_create_file: {
					name: 'New File',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_create_file_ajax(key, $(this), e);
					}
				},
				folder_create_folder: {
					name: 'New Folder',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_create_folder_ajax(key, $(this), e);
					}
				},
				'sep3': '---------',
				preview_folder: {
					name: 'Preview Folder',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_preview_folder_callback($(this), root = true, e);
					}
				},
				reset_dev_path_folder: {
					name: 'Reset Dev Path',
					callback: function(key, opt, e) {
						$('#instant-ide-dev-path').val('');
						$('#instant-ide-dev-path').blur();
					}
				},
				'sep4': '---------',
				install_wordpress: {
					name: 'Install WordPress',
					callback: function(key, opt, e) {
						instant_ide_conditional_install_message_popup('You are about to install WordPress inside this directory', 'Do you want to continue?', key, $(this), e);
					}
				},
				install_october: {
					name: 'Install October CMS',
					callback: function(key, opt, e) {
						instant_ide_conditional_install_message_popup('You are about to install October CMS inside this directory', 'Do you want to continue?', key, $(this), e);
					}
				}
			}
		});
		
		$.contextMenu({
			selector: '.instant-ide-file-tree-file-right-clickable', 
			callback: function(key, options) { /* */ },
			items: {
				open_file: {
					name: 'Open',
					callback: function(key, opt, e) {
						if($(this).hasClass('iideft-file-zip')) {
							instant_ide_file_tree_right_click_unzip_file_ajax(key, $(this), e);
						} else {
							$(this).dblclick();
						}
					}
				},
				download_file: {
					name: 'Download',
					callback: function(key, opt, e) {
						var file_name = $(this).attr('title');
						instant_ide_file_tree_right_click_download_file_ajax(key, $(this), e);
					}
				},
				'sep1': '---------',
				rename_file: {
					name: 'Rename',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_rename_file_ajax($('.context-menu-active a'), e);
						function instant_ide_file_tree_right_click_rename_file_ajax(element, e) {
							var pre_file_name = element.parent().attr('title');
							swal({
								title: 'Give your file a new name',
								input: 'text',
								inputValue: pre_file_name,
								showCancelButton: true,
								confirmButtonText: 'Submit',
								preConfirm: (name) => {
									return new Promise((resolve) => {
										setTimeout(() => {
											if (name == '') {
												swal.showValidationError(
													'The file name field cannot be empty.'
												)
											}
											resolve()
										}, 0)
									})
								},
								allowOutsideClick: false
							}).then((result) => {
								if (result.value) {
									var file_rel_path = instant_ide_get_rel_path(element.parent(), e);
									var file_rel_path_slash = file_rel_path.indexOf('/') > -1 ? true: false;
									var pre_file_rel_path = file_rel_path_slash ? file_rel_path.substr(0, file_rel_path.lastIndexOf('/') + 1)+pre_file_name : pre_file_name;
									var file_open = false;
									if($('#instant-ide-file-editor-tab-container').find('[title="'+pre_file_rel_path+'"]').length) {
										file_open = true;
									}
									var new_file_name = result.value;
									var new_file_ext = new_file_name.split('.').pop();
									$.ajax({
										type : 'POST',
										url : iide_url+'editor/file-editor-ajax.php',
										data : {
											action : 'instant_ide_file_tree_right_click_menu_action',
											context_menu_key : key,
											rel_path : pre_file_rel_path,
											old_name : pre_file_name,
											new_name : new_file_name,
											file_open : file_open
										},
										success : function(response) {
											if(response) {
												var response_error = response.substring(0, 12) == 'Rename Error' ? true : false;
												if(response_error) {
													var response_error = false;
													instant_ide_error_message_popup(response.split('|')[0]);
												} else {
													element.parent().removeClass(function(index, css) {
														return (css.match (/(^|\s)ext-\S+/g) || []).join(' ');
													}).addClass('ext-'+new_file_ext).attr('title', result.value);
													element.text(result.value);
												}
											}
										}
									});
								}
							})
						}
					}
				},
				delete_file: {
					name: 'Delete',
					callback: function(key, opt, e) {
						var file_name = $(this).attr('title');
						// If multiple files are selected then response appropriately.
						if($('.iideft-file-selected').length > 1) {
							instant_ide_conditional_delete_message_popup('You are about to delete multiple files!', 'Do you want to continue?', key, $(this), e);
						} else {
							instant_ide_conditional_delete_message_popup('You are about to delete the file: '+file_name, 'Do you want to continue?', key, $(this), e);
						}
					}
				},
				'sep2': '---------',
				cut_file: {
					name: 'Cut',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_cut_copy_callback($(this), 'cut', 'file', e);
						$(this).css('opacity', '0.5');
					}
				},
				copy_file: {
					name: 'Copy',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_cut_copy_callback($(this), 'copy', 'file', e);
					}
				},
				paste_file: {
					name: 'Paste',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_paste_file_ajax(key, $(this), e);
						$('.instant-ide-file-tree li').css('opacity', '1');
					}
				},
				duplicate_file: {
					name: 'Duplicate',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_duplicate_file_ajax(key, $(this), e);
					}
				},
				'sep3': '---------',
				create_file: {
					name: 'New File',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_create_file_ajax(key, $(this), e);
					}
				},
				create_folder: {
					name: 'New Folder',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_create_folder_ajax(key, $(this), e);
					}
				},
			}
		});
		
		function instant_ide_file_tree_right_click_upload_file_ajax(key, element) {
			var context_menu_key = key;
			var that = element;
			var form_clone = $('#instant-ide-file-editor-upload-form').clone(true);
			$('#instant-ide-file-editor-upload-form').on('submit', function(e) {
				$('#instant-ide-upload-button').hide();
				$('#instant-ide-file-editor-upload-form .instant-ide-ajax-save-spinner').show();
				var file_rel_path = instant_ide_get_rel_path(that, e);
				
				// Get the selected files from the input.
				var files = $('#instant-ide-file-upload').prop('files');
				
				// Create a new FormData object.
				var formData = new FormData();
				
				// Loop through each of the selected files.
				for(var i = 0; i < files.length; i++) {
					var file = files[i];
					
					// Add the file to the request.
					formData.append('uploads[]', file, file.name);
				}
				
				formData.append('action', 'instant_ide_file_tree_upload_action');
				formData.append('context_menu_key', context_menu_key);
				formData.append('rel_path', file_rel_path);
				
				if(formData) {
					$.ajax({
						type : 'POST',
						xhr: function() {
							var xhr = new window.XMLHttpRequest();
							
							xhr.upload.addEventListener("progress", function(evt) {
								if (evt.lengthComputable) {
									var percentComplete = evt.loaded / evt.total;
									percentComplete = parseInt(percentComplete * 100);
									//console.log(percentComplete);
									$('#instant-ide-file-upload-progress').progressbar({
										value: percentComplete
									});
									
									if (percentComplete === 100) {
									
									}
								
								}
							}, false);
							
							return xhr;
						},
						url : iide_url+'editor/file-editor-ajax.php',
						processData: false,
						contentType: false,
						cache: false,
						data: formData,
						success : function(response) {
							$('#instant-ide-upload-button').show();
							$('#instant-ide-file-editor-upload-form .instant-ide-ajax-save-spinner').hide();
							var response_error = response.substring(0, 12) == 'Upload Error' ? true : false;
							if(response_error) {
								var response_error = false;
								instant_ide_error_message_popup(response);
							} else {
								if(!that.find('a').first().hasClass('iideft-directory-open')) {
									that.find('a').first().dblclick();
								} else {
									var current_files_array = [];
									that.find('ul:first').children().each(function() {
										current_files_array.push($(this).attr('title'));
									});
									for(var value of formData.values()) {
										if(value['name'] && $.inArray(value['name'], current_files_array) == -1) {
											var file_name = value['name'];
											var file_ext = file_name.split('.').pop();
											var file_type_pre = value['type'];
											var file_type = file_type_pre.split('/')[0] == 'image' ? 'image' : file_type_pre.split('/')[0] == 'application' ? 'zip' : 'edit';
											that.find('ul:first').prepend('<li class="iideft-file iideft-file-'+file_type+' ext-'+file_ext+'" title="'+file_name+'"><a href="#">'+file_name+'</a></li>');
										}
									}
								}
								
								instant_ide_elements_draggable();
								instant_ide_elements_droppable();
								
							    $('#instant-ide-file-editor-upload-form i').click();
							    $('#instant-ide-file-editor-upload-form').replaceWith(form_clone);
							    
			                    swal({
			                        text: response,
			                        type: 'success'
			                    })
							}
						}
					});
				}
				e.preventDefault();
			});
		}
		
		function instant_ide_file_tree_right_click_unzip_file_ajax(key, element, e) {
			var element = element;
			var zip_file_name = element.attr('title');
			var file_rel_path = instant_ide_get_rel_path(element, e);
			var file_rel_path_slash = file_rel_path.indexOf('/') > -1 ? true: false;
			var pre_file_rel_path = file_rel_path_slash ? file_rel_path.substr(0, file_rel_path.lastIndexOf('/') + 1) : '';
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_file_tree_right_click_menu_action',
					context_menu_key : key,
					rel_path : pre_file_rel_path,
					zip_file_name : zip_file_name
				},
				success : function(response) {
					if(response) {
						var response_error = response == 'Unzip Error!' ? true : false;
						if(response_error) {
							var response_error = false;
							instant_ide_error_message_popup(response);
						} else {
							var file_type = response.split('|')[0];
							var file_name = response.split('|')[1];
							if(file_type == 'folder') {
								var file_name_id = file_rel_path.replace(/[_\W]+/g, '-').toLowerCase();
								element.after('<li id="iideft-directory-'+file_name_id+'-'+file_name+'" class="iideft-directory iideft-temp-directory" title="'+file_name+'"><i class="fa fa-caret-right iideft-directory-icon" aria-hidden="true"></i><a href="#">'+file_name+'</a><ul style="display: none;"></ul></li>');
							} else {
								var ext = file_name.split('.').pop();
								var type = $.inArray(ext, image_ext_array) > -1 ? 'image' : ext == 'zip' ? 'zip' : 'edit';
								element.after('<li class="iideft-file iideft-file-'+type+' ext-'+ext+'" title="'+file_name+'"><a href="#">'+file_name+'</a></li>');
							}
							instant_ide_elements_draggable();
							instant_ide_elements_droppable();
							instant_ide_conditional_unzip_delete_message_popup('Unzip Successful!', 'Would you like to delete the zip file?', 'delete_file', element, e);
						}
					}
				}
			});
		}
		
		function instant_ide_file_tree_right_click_download_file_ajax(key, element, e) {
			var file_name = element.attr('title');
			if(file_name.slice(-4) == '.zip') {
				var zip_file_name = file_name;
			} else {
				var zip_file_name = file_name + '.zip';
			}
			var file_rel_path = instant_ide_get_rel_path(element, e);
			var file_rel_path_slash = file_rel_path.indexOf('/') > -1 ? true: false;
			var pre_file_rel_path = file_rel_path_slash ? file_rel_path.substr(0, file_rel_path.lastIndexOf('/') + 1)+file_name : file_name;
			var file_open = false;
			if($('#instant-ide-file-editor-tab-container').find('[title="'+pre_file_rel_path+'"]').length) {
				file_open = true;
			}
			var new_file_name = element.text();
			var new_file_ext = new_file_name.split('.').pop();
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_file_tree_right_click_menu_action',
					context_menu_key : key,
					rel_path : pre_file_rel_path,
					file_name : file_name
				},
				success : function(response) {
					if(response) {
						$('#instant-ide-file-editor-download-link').attr('href', iide_url+'tmp/'+zip_file_name);
						var download_link = document.getElementById('instant-ide-file-editor-download-link');
						if(download_link) {
						    download_link.click();
						}
					}
				}
			});
		}
		
		$('#instant-ide-file-editor-upload-form-container i').click(function() {
			$('#instant-ide-file-editor-upload-form-container').fadeOut('medium');
			$('#instant-ide-file-editor-upload-form-overlay').fadeOut('medium');
		});
		
		function instant_ide_file_tree_right_click_delete_file_ajax(key, element, e) {
			if($('.iideft-file-selected').length > 1) {
				var file_rel_path = [];
				$('.instant-ide-file-tree').find('.iideft-file-selected').each(function(e) {
					file_rel_path.push(instant_ide_get_rel_path($(this), e, multi = true));
				});
			} else {
				var file_rel_path = instant_ide_get_rel_path(element, e);
			}
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_file_tree_right_click_menu_action',
					context_menu_key : key,
					rel_path : file_rel_path
				},
				success : function(response) {
					var response_error = response.substring(0, 12) == 'Delete Error' ? true : false;
					if(response_error) {
						var response_error = false;
						instant_ide_error_message_popup(response);
					} else {
						if($('.iideft-file-selected').length > 1) {
							$.each(file_rel_path, function(i, val) {
								if($('#instant-ide-file-editor-tab-container').find('[title="'+val+'"]').length) {
									var file_name_id = val.replace(/[_\W]+/g, '-').toLowerCase();
									var tab_id = 'instant-ide-'+file_name_id+'-textarea-container-tab';
									$('#'+tab_id+' .instant-ide-file-editor-tab-quit').click();
								}
							});
							$('.iideft-file-selected').remove();
						} else {
							if($('#instant-ide-file-editor-tab-container').find('[title="'+file_rel_path+'"]').length) {
								var file_name_id = file_rel_path.replace(/[_\W]+/g, '-').toLowerCase();
								var tab_id = 'instant-ide-'+file_name_id+'-textarea-container-tab';
								$('#'+tab_id+' .instant-ide-file-editor-tab-quit').click();
							}
							element.remove();
						}
					}
				}
			});
		}
		
		function instant_ide_file_tree_right_click_paste_file_ajax(key, element, e, drop = false) {
			var copy_rel_path = localStorage.getItem('iide_copy_file_folder_path');
			var copy_rel_path_slash = copy_rel_path.indexOf('/') > -1 ? true: false;
			var actual_copy_rel_path = copy_rel_path_slash ? copy_rel_path.substr(0, copy_rel_path.lastIndexOf('/') + 1) : '';
			var paste_rel_path = instant_ide_get_rel_path(element, e);
			var paste_rel_path_slash = paste_rel_path.indexOf('/') > -1 ? true: false;
			var actual_paste_rel_path = paste_rel_path_slash ? paste_rel_path.substr(0, paste_rel_path.lastIndexOf('/') + 1) : '';
			var paste_action = localStorage.getItem('iide_copy_file_folder_action');
			var paste_source = localStorage.getItem('iide_copy_file_folder_source');
			var paste_file_name = copy_rel_path.split('/').pop();
			var action_file_name = element.find('a').first().text();
			var paste_ext = paste_file_name.split('.').pop();
			var file_type = $.inArray(paste_ext, image_ext_array) > -1 ? 'image' : paste_ext == 'zip' ? 'zip' : 'edit';
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_file_tree_right_click_menu_action',
					context_menu_key : key,
					copy_path : copy_rel_path,
					paste_path : paste_rel_path,
					paste_action : paste_action,
					paste_source : paste_source,
					paste_name : paste_file_name,
					action_name : action_file_name,
					paste_ext : paste_ext
				},
				success : function(response) {
					if(response) {
						var response_error = response.substring(0, 11) == 'Paste Error' ? true : false;
						if(response_error) {
							var response_error = false;
						} else {
							var created_file_name = response.substr(response.indexOf('|') + 1);
							if(paste_source == 'file') {
								if(key == 'paste_file' && !drop) {
									element.after('<li class="iideft-file iideft-file-'+file_type+' ext-'+paste_ext+'" title="'+created_file_name+'"><a href="#">'+created_file_name+'</a></li>');
								} else {
									if(!element.find('a').first().hasClass('iideft-directory-open')) {
										element.find('a').first().dblclick();
									}
									if(drop) {
										element.next().prepend('<li class="iideft-file iideft-file-'+file_type+' ext-'+paste_ext+'" title="'+created_file_name+'"><a href="#">'+created_file_name+'</a></li>');
									} else {
										element.find('ul:first').prepend('<li class="iideft-file iideft-file-'+file_type+' ext-'+paste_ext+'" title="'+created_file_name+'"><a href="#">'+created_file_name+'</a></li>');
									}
									if(paste_action == 'cut') {
										$('.instant-ide-file-tree-copied-item').remove();
										if($('#instant-ide-file-editor-tab-container').find('[title="'+copy_rel_path+'"]').length) {
											var copy_name_id = copy_rel_path.replace(/[_\W]+/g, '-').toLowerCase();
											var tab_id = 'instant-ide-'+copy_name_id+'-textarea-container-tab';
											$('#'+tab_id+' .instant-ide-file-editor-tab-quit').click();
										}
									}
								}
							} else if(paste_source == 'folder') {
								var paste_name_id = paste_rel_path.replace(/[_\W]+/g, '-').toLowerCase();
								if(key == 'paste_file') {
									element.after('<li id="iideft-directory-'+paste_name_id+'-'+created_file_name+'" class="iideft-directory iideft-temp-directory" title="'+created_file_name+'"><i class="fa fa-caret-right iideft-directory-icon" aria-hidden="true"></i><a href="#">'+created_file_name+'</a><ul style="display: none;"></ul></li>');
								} else {
									if(!element.find('a').first().hasClass('iideft-directory-open')) {
										element.find('a').first().dblclick();
									}
									element.find('ul:first').prepend('<li id="iideft-directory-'+paste_name_id+'-'+created_file_name+'" class="iideft-directory iideft-temp-directory" title="'+created_file_name+'"><i class="fa fa-caret-right iideft-directory-icon" aria-hidden="true"></i><a href="#">'+created_file_name+'</a><ul style="display: none;"></ul></li>');
								}
								$('.instant-ide-file-tree-copied-item').find('ul').first().children().clone().appendTo($('.iideft-temp-directory ul'));
								$('.iideft-temp-directory').removeClass('iideft-temp-directory');
								if(paste_action == 'cut' && actual_copy_rel_path == actual_paste_rel_path) {
									$('.instant-ide-file-tree-copied-item').remove();
								}
							}
							if(paste_action == 'cut' && actual_copy_rel_path != actual_paste_rel_path) {
								if($('#instant-ide-file-editor-tab-container').find('[title="'+copy_rel_path+'"]').length) {
									var copy_name_id = copy_rel_path.replace(/[_\W]+/g, '-').toLowerCase();
									var tab_id = 'instant-ide-'+copy_name_id+'-textarea-container-tab';
									$('#'+tab_id+' .instant-ide-file-editor-tab-quit').click();
								}
								$('.instant-ide-file-tree-copied-item').remove();
							}
							instant_ide_elements_draggable();
							instant_ide_elements_droppable();
						}
					}
				}
			});
		}
		
		function instant_ide_file_tree_right_click_duplicate_file_ajax(key, element, e) {
			var file_rel_path = instant_ide_get_rel_path(element, e);
			var name = element.find('a').first().text();
			var ext = name.split('.').pop();
			var file_type = $.inArray(ext, image_ext_array) > -1 ? 'image' : ext == 'zip' ? 'zip' : 'edit';
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_file_tree_right_click_menu_action',
					context_menu_key : key,
					rel_path : file_rel_path,
					name : name,
					ext : ext
				},
				success : function(response) {
					if(response) {
						var created_file_name = response.substr(response.indexOf('|') + 1);
						element.after('<li class="iideft-file iideft-file-'+file_type+' ext-'+ext+'" title="'+created_file_name+'"><a href="#">'+created_file_name+'</a></li>');
						instant_ide_elements_draggable();
					}
				}
			});
		}
		
		function instant_ide_file_tree_right_click_create_file_ajax(key, element, e) {
			swal({
				title: 'Give your file a name',
				input: 'text',
				showCancelButton: true,
				confirmButtonText: 'Submit',
				preConfirm: (name) => {
					return new Promise((resolve) => {
						setTimeout(() => {
							if ($.inArray(name.split('.').pop(), image_ext_array) > -1) {
								swal.showValidationError(
									'Image files cannot be created.'
								)
							}
							resolve()
						}, 0)
					})
				},
				allowOutsideClick: false
			}).then((result) => {
				if (result.value) {
					var file_rel_path = instant_ide_get_rel_path(element, e);
					var file_rel_path_slash = file_rel_path.indexOf('/') > -1 ? true: false;
					var actual_file_rel_path = file_rel_path_slash ? file_rel_path.substr(0, file_rel_path.lastIndexOf('/') + 1) : '';
					var name = element.attr('id') == 'iideft-root-directory' ? '' : element.find('a').first().text();
					var file_name = result.value;
					var ext = file_name.split('.').pop() == '' ? 'txt' : file_name.split('.').pop();
					$.ajax({
						type : 'POST',
						url : iide_url+'editor/file-editor-ajax.php',
						data : {
							action : 'instant_ide_file_tree_right_click_menu_action',
							context_menu_key : key,
							rel_path : actual_file_rel_path,
							name : name,
							file_name : file_name
						},
						success : function(response) {
							var response_error = response.substring(0, 5) == 'Error' ? true : false;
							if(response_error) {
								var response_error = false;
								instant_ide_error_message_popup(response.split('|')[0]);
							} else {
								var created_file_name = response.substr(response.indexOf('|') + 1);
								if(key == 'folder_create_file') {
									if(!element.find('a').first().hasClass('iideft-directory-open')) {
										element.find('a').first().dblclick();
									}
									element.find('ul:first').append('<li class="iideft-file iideft-file-edit ext-'+ext+'" title="'+created_file_name+'"><a href="#">'+created_file_name+'</a></li>');
								} else {
									element.after('<li class="iideft-file iideft-file-edit ext-'+ext+'" title="'+created_file_name+'"><a href="#">'+created_file_name+'</a></li>');
								}
								instant_ide_elements_draggable();
							}
						}
					});
				}
			})
		}
		
		function instant_ide_file_tree_right_click_install_ajax(key, element, e) {
			$('#instant-ide-file-editor-menu .instant-ide-ajax-save-spinner').show();
			var file_rel_path = instant_ide_get_rel_path(element, e);
			var name = element.attr('id') == 'iideft-root-directory' ? '' : element.find('a').first().text();
			var context_menu_key = key;
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_one_click_install',
					context_menu_key : context_menu_key,
					rel_path : file_rel_path,
					name : name
				},
				success : function(response) {
					var response_error = response.substring(0, 13) == 'Install Error' ? true : false;
					if(response_error) {
						instant_ide_error_message_popup(response);
					} else {
						$('#instant-ide-file-editor-menu .instant-ide-ajax-save-spinner').hide();
						$('#instant-ide-file-editor-menu .instant-ide-saved').html(response).fadeIn('slow');
						window.setTimeout(function() {
							$('#instant-ide-file-editor-menu .instant-ide-saved').fadeOut('slow'); 
						}, 2000);
						if(element.attr('id') != 'iideft-root-directory' && !element.find('a').first().hasClass('iideft-directory-opened')) {
							if(!element.find('a').first().hasClass('iideft-directory-open')) {
								element.find('a').first().dblclick();
							}
						} else {
							$('#iideft-root-directory').load(window.location.href+' #iideft-root-directory > *', function() {
								$('.instant-ide-file-tree').find('ul').slice(1).hide();
							});
						}
						if(context_menu_key == 'install_october' && element.attr('id') == 'iideft-root-directory')
							var cms_install_url = platform_url_dev_path+'/'+file_rel_path+'install.php';
						else if(context_menu_key == 'install_october' && element.attr('id') != 'iideft-root-directory')
							var cms_install_url = platform_url_dev_path+'/'+file_rel_path+'/install.php';
						else
							var cms_install_url = platform_url_dev_path+'/'+file_rel_path;
							
						var cms_name = response.replace(' Install Successful!', '');

	                    swal({
	                        text: cms_name+' Installer Ready',
	                        type: 'success'
	                    })
	                    .then((value) => {
							swal({
								title: 'Run Installer!',
								text: 'To finalize the '+cms_name+' CMS installation just click the "Install" button below.',
								type: 'info',
								showCancelButton: true,
								confirmButtonText: 'Install',
								cancelButtonText: 'Cancel'
							}).then((result) => {
								if (result.value) {
									window.open(cms_install_url, '_blank');
								}
							})
	                    })
					}
				}
			});
		}
		
		$.contextMenu({
			selector: '.instant-ide-file-tree-folder-right-clickable', 
			callback: function(key, options) { /* */ },
			items: {
				open_folder: {
					name: 'Open',
					callback: function(key, opt, e) {
						if(!$(this).find('a').first().hasClass('iideft-directory-open')) {
							$(this).find('a').first().dblclick();
						}
					}
				},
				folder_upload_file: {
					name: 'Upload Files',
					callback: function(key, opt, e) {
						$('#instant-ide-file-editor-upload-form').attr('title', instant_ide_get_rel_path($(this), e)).attr('alt', key);
						$('#instant-ide-file-editor-upload-form-container').fadeToggle('medium');
						$('#instant-ide-file-editor-upload-form-overlay').fadeToggle('medium');
						instant_ide_file_tree_right_click_upload_file_ajax(key, $(this));
					}
				},
				download_folder: {
					name: 'Download',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_download_file_ajax(key, $(this), e);
					}
				},
				'sep1': '---------',
				rename_folder: {
					name: 'Rename',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_rename_folder_ajax($('.context-menu-active > a'), e);
						function instant_ide_file_tree_right_click_rename_folder_ajax(element, e) {
							var pre_file_name = element.parent().attr('title');
							swal({
								title: 'Give your folder a new name',
								input: 'text',
								inputValue: pre_file_name,
								showCancelButton: true,
								confirmButtonText: 'Submit',
								preConfirm: (name) => {
									return new Promise((resolve) => {
										setTimeout(() => {
											if (name == '') {
												swal.showValidationError(
													'The folder name field cannot be empty.'
												)
											}
											resolve()
										}, 0)
									})
								},
								allowOutsideClick: false
							}).then((result) => {
								if (result.value) {
									var file_rel_path = instant_ide_get_rel_path(element.parent(), e);
									var file_rel_path_slash = file_rel_path.indexOf('/') > -1 ? true: false;
									var pre_file_rel_path = file_rel_path_slash ? file_rel_path.substr(0, file_rel_path.lastIndexOf('/') + 1)+pre_file_name : pre_file_name;
									var file_open = false;
									if($('#instant-ide-file-editor-tab-container').find('[title="'+pre_file_rel_path+'"]').length) {
										file_open = true;
									}
									var new_file_name = result.value;
									$.ajax({
										type : 'POST',
										url : iide_url+'editor/file-editor-ajax.php',
										data : {
											action : 'instant_ide_file_tree_right_click_menu_action',
											context_menu_key : key,
											rel_path : pre_file_rel_path,
											old_name : pre_file_name,
											new_name : new_file_name,
											file_open : file_open
										},
										success : function(response) {
											if(response) {
												var response_error = response.substring(0, 12) == 'Rename Error' ? true : false;
												if(response_error) {
													var response_error = false;
													instant_ide_error_message_popup(response.split('|')[0]);
												} else {
													element.parent().attr('title', new_file_name);
													element.text(result.value);
												}
											}
										}
									});
								}
							})
						}
					}
				},
				delete_folder: {
					name: 'Delete',
					callback: function(key, opt, e) {
						var folder_name = $(this).attr('title');
						instant_ide_conditional_delete_message_popup('You are about to delete the folder: '+folder_name, 'Do you want to continue?', key, $(this), e);
					}
				},
				'sep2': '---------',
				cut_folder: {
					name: 'Cut',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_cut_copy_callback($(this), 'cut', 'folder', e);
						$(this).css('opacity', '0.5');
					}
				},
				copy_folder: {
					name: 'Copy',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_cut_copy_callback($(this), 'copy', 'folder', e);
					}
				},
				paste_folder: {
					name: 'Paste',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_paste_file_ajax(key, $(this), e);
						$('.instant-ide-file-tree li').css('opacity', '1');
					}
				},
				duplicate_folder: {
					name: 'Duplicate',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_duplicate_folder_ajax(key, $(this), e);
					}
				},
				'sep3': '---------',
				folder_create_file: {
					name: 'New File',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_create_file_ajax(key, $(this), e);
					}
				},
				folder_create_folder: {
					name: 'New Folder',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_create_folder_ajax(key, $(this), e);
					}
				},
				'sep4': '---------',
				preview_folder: {
					name: 'Preview Folder',
					callback: function(key, opt, e) {
						instant_ide_file_tree_right_click_preview_folder_callback($(this), root = false, e);
					}
				},
				set_dev_path_folder: {
					name: 'Set As Dev Path',
					callback: function(key, opt, e) {
						var new_dev_path = instant_ide_get_rel_path($(this), e);
						$('#instant-ide-dev-path').val($('#instant-ide-dev-path').val()+'/'+new_dev_path);
						$('#instant-ide-dev-path').blur();
					}
				},
				reset_dev_path_folder: {
					name: 'Reset Dev Path',
					callback: function(key, opt, e) {
						$('#instant-ide-dev-path').val('');
						$('#instant-ide-dev-path').blur();
					}
				},
				'sep5': '---------',
				install_wordpress: {
					name: 'Install WordPress',
					callback: function(key, opt, e) {
						instant_ide_conditional_install_message_popup('You are about to install WordPress inside this directory', 'Do you want to continue?', key, $(this), e);
					}
				},
				install_october: {
					name: 'Install October CMS',
					callback: function(key, opt, e) {
						instant_ide_conditional_install_message_popup('You are about to install October CMS inside this directory', 'Do you want to continue?', key, $(this), e);
					}
				}
			}
		});
		
		function instant_ide_file_tree_right_click_delete_folder_ajax(key, element, e) {
			var file_rel_path = instant_ide_get_rel_path(element, e);
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_file_tree_right_click_menu_action',
					context_menu_key : key,
					rel_path : file_rel_path
				},
				success : function(response) {
					var response_error = response.substring(0, 12) == 'Delete Error' ? true : false;
					if(response_error) {
						var response_error = false;
						instant_ide_error_message_popup(response);
					} else {
						element.remove();
					}
				}
			});
		}
		
		function instant_ide_file_tree_right_click_duplicate_folder_ajax(key, element, e) {
			var folder_rel_path = instant_ide_get_rel_path(element, e);
			var name = element.find('a').first().text();
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_file_tree_right_click_menu_action',
					context_menu_key : key,
					rel_path : folder_rel_path,
					name : name
				},
				success : function(response) {
					if(response) {
						var created_folder_name = response.substr(response.indexOf('|') + 1);
						var folder_name_id = folder_rel_path.substr(0, folder_rel_path.lastIndexOf('/')).replace(/[_\W]+/g, '-').toLowerCase();
						element.after('<li id="iideft-directory-'+folder_name_id+'-'+created_folder_name+'" class="iideft-directory iideft-temp-directory" title="'+created_folder_name+'"><i class="fa fa-caret-right iideft-directory-icon" aria-hidden="true"></i><a href="#">'+created_folder_name+'</a><ul style="display: none;"></ul></li>');
						element.find('ul').first().children().clone().appendTo($('.iideft-temp-directory ul'));
						$('.iideft-temp-directory').removeClass('iideft-temp-directory');
						instant_ide_elements_draggable();
					}
				}
			});
		}
		
		function instant_ide_file_tree_right_click_create_folder_ajax(key, element, e) {
			swal({
				title: 'Give your folder a name',
				input: 'text',
				showCancelButton: true,
				confirmButtonText: 'Submit',
				preConfirm: (name) => {
					return new Promise((resolve) => {
						setTimeout(() => {
							if (name === '') {
								swal.showValidationError(
									'You must give your folder a name.'
								)
							}
							resolve()
						}, 0)
					})
				},
				allowOutsideClick: false
			}).then((result) => {
				if (result.value) {
					var folder_rel_path = instant_ide_get_rel_path(element, e);
					var folder_rel_path_slash = folder_rel_path.indexOf('/') > -1 ? true: false;
					var actual_folder_rel_path = folder_rel_path_slash ? folder_rel_path.substr(0, folder_rel_path.lastIndexOf('/') + 1) : '';
					var name = element.attr('id') == 'iideft-root-directory' ? '' : element.find('a').first().text();
					var folder_name = result.value;
					$.ajax({
						type : 'POST',
						url : iide_url+'editor/file-editor-ajax.php',
						data : {
							action : 'instant_ide_file_tree_right_click_menu_action',
							context_menu_key : key,
							rel_path : actual_folder_rel_path,
							name : name,
							folder_name : folder_name
						},
						success : function(response) {
							var response_error = response.substring(0, 5) == 'Error' ? true : false;
							if(response_error) {
								var response_error = false;
								instant_ide_error_message_popup(response.split('|')[0]);
							} else {
								var created_folder_name = response.substr(response.indexOf('|') + 1);
								if(key == 'folder_create_folder') {
									var folder_name_id = folder_rel_path.replace(/[_\W]+/g, '-').toLowerCase();
									if(!element.find('a').first().hasClass('iideft-directory-open')) {
										element.find('a').first().dblclick();
									}
									element.find('ul:first').prepend('<li id="iideft-directory-'+folder_name_id+'-'+created_folder_name+'" class="iideft-directory" title="'+created_folder_name+'"><i class="fa fa-caret-right iideft-directory-icon" aria-hidden="true"></i><a href="#">'+created_folder_name+'</a><ul style="display: none;"></ul></li>');
								} else {
									var folder_name_id = folder_rel_path.substr(0, folder_rel_path.lastIndexOf('/')).replace(/[_\W]+/g, '-').toLowerCase();
									element.after('<li id="iideft-directory-'+folder_name_id+'-'+created_folder_name+'" class="iideft-directory" title="'+created_folder_name+'"><i class="fa fa-caret-right iideft-directory-icon" aria-hidden="true"></i><a href="#">'+created_folder_name+'</a><ul style="display: none;"></ul></li>');
								}
								instant_ide_elements_draggable();
								instant_ide_elements_droppable();
							}
						}
					});
				}
			})
		}
		
		function instant_ide_file_tree_right_click_cut_copy_callback(element, action, source, e) {
			localStorage.iide_copy_file_folder_path = instant_ide_get_rel_path(element, e);
			localStorage.iide_copy_file_folder_action = action;
			localStorage.iide_copy_file_folder_source = source;
			$('.instant-ide-file-tree li').removeClass('instant-ide-file-tree-copied-item');
			element.addClass('instant-ide-file-tree-copied-item');
		}
		
		/* PHP File Tree */
		
		function instant_ide_get_rel_path(element, e, multi = false) {
			var rel_path = [];
			element.parents().each(function() {
				if($(this).hasClass('iideft-directory')) {
					rel_path.unshift($(this).find('a').first().text());
				}
			});
			var arrayLength = rel_path.length;
			var rel_path_string = '';
			for (var i = 0; i < arrayLength; i++) {
			    rel_path_string += rel_path[i]+'/';
			}
			if(!multi) {
				e.stopPropagation();
			}
			if(element.attr('id') == 'iideft-root-directory') {
				var final_path = '';
			} else {
				var final_path = rel_path_string+element.find('a').first().text();
			}
			return final_path.substring(final_path.indexOf('/') + 1);
		}
	
		// Hide all subfolders on page load.
		$('.instant-ide-file-tree').find('ul').slice(1).hide();
	
		$('#instant-ide-file-tree-container').on('dblclick', '.iideft-directory > a', function(e) {
			$(this).toggleClass('iideft-directory-open');
			if(!$(this).hasClass('iideft-directory-opened')) {
				$(this).css('background-image', 'url("'+iide_url+'assets/css/images/ajax-save-in-progress.gif")').css('background-size', '16px 16px');
				$(this).addClass('iideft-directory-opened');
				var folder_rel_path = instant_ide_get_rel_path($(this).parent(), e);
				instant_ide_file_tree_li_folder_dblclicked_ajax($(this).parent(), folder_rel_path);
				var element = $(this);
				setTimeout(function() {
					element.css('background-image', '').css('background-size', '');
					element.parent().find('ul:first').slideToggle('medium');
					if(element.hasClass('iideft-directory-open')) {
						element.parent().find('i:first').replaceWith('<i class="fa fa-caret-down iideft-directory-icon" aria-hidden="true"></i>');
					} else {
						element.parent().find('i:first').replaceWith('<i class="fa fa-caret-right iideft-directory-icon" aria-hidden="true"></i>');
					}
					instant_ide_elements_draggable();
					instant_ide_elements_droppable();
				}, 300);
			} else {
				$(this).parent().find('ul:first').slideToggle('medium');
				if($(this).hasClass('iideft-directory-open')) {
					$(this).parent().find('i:first').replaceWith('<i class="fa fa-caret-down iideft-directory-icon" aria-hidden="true"></i>');
				} else {
					$(this).parent().find('i:first').replaceWith('<i class="fa fa-caret-right iideft-directory-icon" aria-hidden="true"></i>');
				}
				instant_ide_elements_draggable();
				instant_ide_elements_droppable();
			}
		});
		
		$('.instant-ide-file-tree').on('click', '.iideft-directory-icon', function() {
			$(this).next().dblclick(); 
		});
		
		$('#instant-ide-file-tree-container').on('click', '.iideft-file a', function(e) {
		    e.preventDefault();
		});
		
		$('#instant-ide-file-tree-container').on('dblclick', '.iideft-file-edit', function(e) {
			var file_rel_path = instant_ide_get_rel_path($(this), e);
			var file_name_id = file_rel_path.replace(/[_\W]+/g, '-').toLowerCase();
			var tab_id = 'instant-ide-'+file_name_id+'-textarea-container-tab';
			$(this).addClass('iideft-file-dblclicked');
			
			if(!$('#'+tab_id).length) {
				var file_name = $(this).text();
				var file_ext = file_rel_path.split('.').pop();
				var file_ext_array = ['php', 'css', 'json', 'less', 'sass', 'scss', 'xml', 'ini', 'html', 'htm', 'yaml'];
				var file_ext_text = $('body').hasClass('instant-ide-monaco-editor-active') ? 'plaintext' : 'text';
				
				if(file_ext == 'js') {
					var data_editor = 'javascript';
				} else if(file_ext == 'md') {
					var data_editor = 'markdown';
				} else if($.inArray(file_ext, file_ext_array) > -1) {
					if(file_ext == 'htm') {
						var data_editor = 'html';
					} else {
						var data_editor = file_ext;
					}
				} else {
					var data_editor = file_ext_text;
				}
				
				var textarea = '<div id="instant-ide-'+file_name_id+'-textarea-container" class="instant-ide-file-editor-textarea-container">';
				textarea += '<form action="/" id="instant-ide-'+file_name_id+'-textarea-form" class="instant-ide-file-editor-textarea-form" title="'+file_rel_path+'">';
				
				textarea += '<input type="hidden" name="action" value="instant_ide_file_editor_save" />';
				textarea += '<input class="instant-ide-file-editor-save-button" type="submit" value="Save Changes" name="Submit" alt="Save Changes" />';
				
				textarea += '<p style="margin:0;">';
				textarea += '<textarea data-editor="'+data_editor+'" wrap="off" id="instant-ide-'+file_name_id+'-textarea" class="instant-ide-tabby-textarea" name="iide[file]" rows="27" style="display:none;"></textarea>';
				textarea += '</p>';
				
				textarea += '</form>';
				textarea += '</div>';
				
				$('#instant-ide-file-editor-container').append(textarea);
				
				$('.instant-ide-file-editor-tab').removeClass('instant-ide-file-editor-tab-active');
				$('#instant-ide-file-editor-tab-container').append('<div id="'+tab_id+'" class="instant-ide-file-editor-tab instant-ide-file-editor-tab-active" title="'+file_rel_path+'"><div class="instant-ide-file-editor-tab-text">'+file_name+'</div><div class="instant-ide-file-editor-tab-quit"></div></div>');
				
				instant_ide_file_tree_li_dblclicked_ajax(file_rel_path, file_name_id);
				instant_ide_file_tree_li_dblclicked($(this), file_name_id);
			} else {
				instant_ide_file_editor_tab_clicked(tab_id);
			}
		});
		
		$('#instant-ide-file-tree-container').on('dblclick', '.iideft-file-image', function(e) {
			var file_rel_path = instant_ide_get_rel_path($(this), e);
			$('body').addClass('instant-ide-image-view-active');
			$(this).addClass('iideft-file-dblclicked');
			
			$('#instant-ide-image-view-info-name').text($(this).text());
			$('#instant-ide-image-view-info-link').attr('href', platform_url_dev_path+'/'+file_rel_path);
			
			var img = new Image();
			img.src = platform_url_dev_path+'/'+file_rel_path;
			img.onload = function() {
				$('#instant-ide-image-view-info-width').text(this.width+'px');
				$('#instant-ide-image-view-info-height').text(this.height+'px');
				$('#instant-ide-image-file-preview').css('width', (this.width + 2)+'px');
			}
			
			var xhr = new XMLHttpRequest();
			xhr.open('HEAD', platform_url_dev_path+'/'+file_rel_path, true);
			xhr.onreadystatechange = function() {
				if ( xhr.readyState == 4 ) {
					if ( xhr.status == 200 ) {
						$('#instant-ide-image-view-info-size').text(instant_ide_bytes_to_size(xhr.getResponseHeader('Content-Length'), 2));
					} else {
						alert('ERROR');
					}
				}
			};
			xhr.send(null);
			
			$('#instant-ide-image-file-preview img').attr('src', platform_url_dev_path+'/'+file_rel_path);
			setTimeout(function() {
				$('#instant-ide-file-editor-image-view-overlay').show();
				$('#instant-ide-file-editor-image-view-container').show();
			}, 300);
		});
		
		$('#instant-ide-file-tree-container').on('dblclick', '.iideft-file-zip', function(e) {
			instant_ide_file_tree_right_click_unzip_file_ajax('open_file', $(this), e);
		});
		
		$('#instant-ide-file-editor-image-view-container i.instant-ide-file-editor-image-view-close').click(function() {
			$('body').removeClass('instant-ide-image-view-active');
			$('#instant-ide-file-editor-image-view-container').fadeOut('medium');
			$('#instant-ide-file-editor-image-view-overlay').fadeOut('medium');
		});
		
		$('#instant-ide-file-editor-tab-container').sortable();
		
		/*
		 * NOTE: 'distance' is deprecated, but should be around
		 * for some time to come. I currently know of no better
		 * solution, so it will remain until either I find one
		 * or it has actually been removed from the JQUI library.
		 */
		function instant_ide_elements_draggable() {
			$('.iideft-file').not(':hidden').draggable({
				containment: '#instant-ide-file-tree-container',
				cursor: 'move',
				helper: 'clone',
				distance: 10,
				refreshPositions: true
			});
			
			$('.iideft-directory').not('#iideft-root-directory').not(':hidden').draggable({
				containment: '#instant-ide-file-tree-container',
				cursor: 'move',
				helper: 'clone',
				distance: 10,
				refreshPositions: true
			});
		}
		
		instant_ide_elements_draggable();
		
		function instant_ide_elements_droppable() {
			var drag_hover_timeout;
		
			$('.iideft-directory').not(':hidden').droppable({
				greedy: true,
				over: function(e, ui){
					$this = $(this).find('a').first();
					if(!$(this).find('a').first().hasClass('iideft-directory-open')) {
						drag_hover_timeout = setTimeout(function() { $this.dblclick(); }, 1000);
					}
				},
				out: function (e, ui){
					clearTimeout(drag_hover_timeout);
				},
				drop: instant_ide_file_folder_drop_event
			});
		}
		
		instant_ide_elements_droppable();
	 
		function instant_ide_file_folder_drop_event(e, ui) {
			var draggable = ui.draggable;
			if(draggable.hasClass('iideft-file')) {
				instant_ide_file_tree_right_click_cut_copy_callback(draggable, 'cut', 'file', e);
				instant_ide_file_tree_right_click_paste_file_ajax('paste_file', $(this).find('a').first(), e, true);
			} else {
				instant_ide_file_tree_right_click_cut_copy_callback(draggable, 'cut', 'folder', e);
				instant_ide_file_tree_right_click_paste_file_ajax('paste_folder', $(this), e);
			}
		}
		
		$('#instant-ide-file-tree-container').on('click', '.iideft-file', function(e) {
			if(!e.shiftKey) {
				$('.iideft-directory a').removeClass('iideft-folder-clicked');
				$('.iideft-file').removeClass('iideft-file-selected').removeClass('iideft-file-dblclicked');
				$(this).addClass('iideft-file-selected');
			}
		});
		
		$('#instant-ide-file-tree-container').on('click', '.iideft-directory > a', function() {
			$('.iideft-file').removeClass('iideft-file-selected').removeClass('iideft-file-dblclicked');
			$('.iideft-directory a').removeClass('iideft-folder-clicked');
			$(this).addClass('iideft-folder-clicked');
		});
		
		$('#instant-ide-file-editor-container').on('click', function() {
			$('.iideft-file-selected').addClass('iideft-file-dblclicked');
			$('.iideft-file-selected').removeClass('iideft-file-selected');
		});
		
		$('.iideft-file-selected').blur(function() {
			$('.iideft-file').removeClass('iideft-file-selected').removeClass('iideft-file-dblclicked');
			$(this).addClass('iideft-file-dblclicked');
		});
		
		$('#instant-ide-file-tree-container').on('contextmenu', '.iideft-file-dblclicked', function() {
			$('.iideft-file-dblclicked').addClass('iideft-file-selected').removeClass('iideft-file-dblclicked');
		});
		
		$('.instant-ide-settings-wrap').on('click', '.instant-ide-file-editor-tab', function(e) {
	        if($(e.target).is('.instant-ide-file-editor-tab-quit')) {
	            e.preventDefault();
	            return false;
	        }
			var tab_id = $(this).attr('id');
			instant_ide_file_editor_tab_clicked(tab_id);
		});
		
		function instant_ide_file_editor_tab_clicked(tab_id) {
			var textarea_id = tab_id.slice(0,-4);
			$('.instant-ide-file-editor-tab').removeClass('instant-ide-file-editor-tab-active');
			$('#'+tab_id).addClass('instant-ide-file-editor-tab-active');
			$('.instant-ide-file-editor-textarea-container').hide();
			$('#'+textarea_id).show();
			$('form.instant-ide-file-editor-textarea-form').removeClass('instant-ide-file-editor-active-form');
			$('form#'+textarea_id.slice(0, -9)+'form').addClass('instant-ide-file-editor-active-form');
		}
		
		$('.instant-ide-settings-wrap').on('click', '.instant-ide-file-editor-tab-quit', function() {
			var confirm_message = 'This file has unsaved changes. Are you sure you want to close it?';
			if($(this).hasClass('file-changed') && !confirm(confirm_message)) {
				return false;
			}
			var tab_id = $(this).parent().attr('id');
			$(this).parent().remove();
			instant_ide_file_editor_tab_quit_clicked(tab_id);
		});
		
		function instant_ide_file_editor_tab_quit_clicked(tab_id) {
			var textarea_id = tab_id.slice(0,-4);
			var active_textarea = $('form#'+textarea_id.slice(0, -9)+'form').hasClass('instant-ide-file-editor-active-form') ? true : false;
			var next_tab_id = $('#instant-ide-file-editor-tab-container').find('.instant-ide-file-editor-tab').first().attr('id');
			if(active_textarea && next_tab_id != undefined) {
				instant_ide_file_editor_tab_clicked(next_tab_id);
			}
			$('#'+textarea_id).remove();
		}
		
		function instant_ide_file_tree_li_dblclicked(element, file_name_id) {
			var textarea_id = 'instant-ide-'+file_name_id+'-textarea-container';
			$('.instant-ide-file-editor-textarea-container').hide();
			$('#'+textarea_id).show();
			$('form.instant-ide-file-editor-textarea-form').removeClass('instant-ide-file-editor-active-form');
			$('form#'+textarea_id.slice(0, -9)+'form').addClass('instant-ide-file-editor-active-form');
			$('.iideft-file').removeClass('iideft-file-active');
			element.addClass('iideft-file-active');		
		}
		
		function instant_ide_file_tree_li_dblclicked_ajax(file_rel_path, file_name_id) {
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_file_tree_file_open',
					file_rel_path : file_rel_path
				},
				success : function(response) {
					if(response) {
						$('#instant-ide-'+file_name_id+'-textarea').text(response);
						if($('body').hasClass('instant-ide-monaco-editor-active')) {
							instant_ide_monaco_editor_build('instant-ide-'+file_name_id+'-textarea');
						} else {
							instant_ide_ace_editor_build('#instant-ide-'+file_name_id+'-textarea');
						}
					}
				}
			});
		}
		
		function instant_ide_file_tree_li_folder_dblclicked_ajax(element, folder_rel_path) {
			var element = element;
			$.ajax({
				type : 'POST',
				url : iide_url+'editor/file-editor-ajax.php',
				data : {
					action : 'instant_ide_file_tree_folder_open',
					folder_rel_path : folder_rel_path
				},
				success : function(response) {
					if(response) {
						element.find('ul:first').empty().append(response);
					}
				}
			});
		}
		
		function instant_ide_bytes_to_size(bytes,decimals) {
			if(bytes == 0) return '0 Bytes';
			var k = 1024,
			dm = decimals || 2,
			sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
			i = Math.floor(Math.log(bytes) / Math.log(k));
			return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
		}
		
		function instant_ide_get_url_parameter(sParam) {
		    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		        sURLVariables = sPageURL.split('&'),
		        sParameterName,
		        i;
	
		    for(i = 0; i < sURLVariables.length; i++) {
		        sParameterName = sURLVariables[i].split('=');
	
		        if(sParameterName[0] === sParam) {
		            return sParameterName[1] === undefined ? true : sParameterName[1];
		        }
		    }
		}
		
		/* END PHP File Tree */
		
		function instant_ide_file_editor_form_submit() {
			function show_message(response) {
				$('#instant-ide-file-editor-menu .instant-ide-ajax-save-spinner').hide();
				$('.instant-ide-file-editor-tab-active .instant-ide-file-editor-tab-quit').removeClass('file-changed');
				$('#instant-ide-file-editor-menu .instant-ide-saved').html(response).fadeIn('slow');
				window.setTimeout(function() {
					$('#instant-ide-file-editor-menu .instant-ide-saved').fadeOut('slow'); 
				}, 2000);
			}
			
			$('.instant-ide-file-editor-active-form').on('submit', function() {
				$('#instant-ide-file-editor-menu .instant-ide-ajax-save-spinner').show();
				var file_rel_path = $(this).attr('title');
				var data = $(this).serialize()+'&file_rel_path='+file_rel_path+'&save_action=file-editor-save';
				$.post(iide_url+'editor/file-editor-ajax.php', data, function(response) {
					if(response) {
						show_message(response);
						if($('body').hasClass('instant-ide-site-preview-active')) {
							document.getElementById('instant-ide-site-preview').contentWindow.location.reload();
						}
					}
				});
				
				return false;
			});
		}
		
		$('#instant-ide-file-tree-container').on('dblclick', '.iideft-file-edit', instant_ide_file_editor_form_submit);
		
		$('#sub-menu-add-users').click(function() {
			window.location.href = iide_url+'?enable_setup=true&add_users=true';
		});
		
		$('#sub-menu-delete-users').click(function() {
			window.location.href = iide_url+'?enable_setup=true&delete_users=true';
		});
		
		$('#sub-menu-visit-home').click(function() {
			window.location.href = platform_url;
		});
		
		$('#sub-menu-logout').click(function() {
			window.location.href = iide_url+'logout.php';
		});
		
		$('#sub-menu-open').click(function() {
			$('.iideft-file-selected').dblclick();	
		});
		
		$('#sub-menu-save').click(function() {
			$('.instant-ide-file-editor-active-form .instant-ide-file-editor-save-button').click();	
		});
		
		$('#sub-menu-console-open').click(function() {
			if(!$('body').hasClass('instant-ide-console-active')) {
				$('body').addClass('instant-ide-console-active');
				$('#instant-ide-file-editor-console-container').html(console_iframe);
				setTimeout(function () {
					var iframe_dom = $('iframe#instant-ide-file-editor-console').contents();
					iframe_dom.find('body').removeClass(function(index, className) {
						return (className.match(/(^|\s)instant-ide-theme-\S+/g) || []).join(' ');
					}).addClass('instant-ide-theme-'+localStorage.getItem('iide_theme'));
				}, 500);
				$(window).resize();
			}
		});
		
		$('#sub-menu-console-close').click(function() {
			$('body').removeClass('instant-ide-console-active');
			$('#instant-ide-file-editor-console-container').html('');
			$(window).resize();
		});
		
		$('#sub-menu-console-restart').click(function() {
			if($('body').hasClass('instant-ide-console-active')) {
				$('#instant-ide-file-editor-console-container').html(console_iframe);
			}
		});
		
		$('#sub-menu-site-preview-open').click(function() {
			if(!$('body').hasClass('instant-ide-site-preview-active')) {
				$('body').addClass('instant-ide-site-preview-active');
				$('body').removeClass('instant-ide-site-preview-file-tree-active');
				$('.instant-ide-site-preview-icons-container').show();
				$('.instant-ide-file-tree-cog').hide();
				$('#instant-ide-site-preview-container').html('<iframe id="instant-ide-site-preview" src="'+platform_url+'?timestamp='+Date.now()+'"></iframe>');
				$(window).resize();
				if($('body').hasClass('instant-ide-ace-editor-active')) {
					resizeAce();
				}
				$('#instant-ide-site-preview-icons-url-view-url').text(platform_url+'/').attr('contenteditable', 'true').on('focusin', function() {
				    $('body').addClass('instant-ide-site-preview-address-bar-active');
				}).on('focusout', function() {
				    $('body').removeClass('instant-ide-site-preview-address-bar-active');
				});
			    $('#instant-ide-site-preview').on('load', function() {
			        $(this).contents().on('mousedown, mouseup, click', function() {
			        	setTimeout(function() {
							var current_url = document.getElementById('instant-ide-site-preview').contentWindow.location.href;
				            $('#instant-ide-site-preview-icons-url-view-url').text(current_url.split('?')[0]);
			        	}, 1000);
			        });
			    });
			}
		});
		
		$('#sub-menu-site-preview-close').click(function() {
			$('body').removeClass('instant-ide-site-preview-active');
			$('.instant-ide-site-preview-icons-container').hide();
			$('.instant-ide-file-tree-cog').show();
			$('#instant-ide-site-preview-container').html('');
			$(window).resize();
			if($('body').hasClass('instant-ide-ace-editor-active')) {
				resizeAce();
			}
		});
		
		$('#sub-menu-site-preview-restart').click(function() {
			if($('body').hasClass('instant-ide-site-preview-active')) {
				$('#instant-ide-site-preview-container').html('<iframe id="instant-ide-site-preview" src="'+platform_url+'?timestamp='+Date.now()+'"></iframe>');
				$('#instant-ide-site-preview-icons-url-view-url').text(platform_url+'/');
			    $('#instant-ide-site-preview').on('load', function() {
			        $(this).contents().on('mousedown, mouseup, click', function() {
			        	setTimeout(function() {
							var current_url = document.getElementById('instant-ide-site-preview').contentWindow.location.href;
				            $('#instant-ide-site-preview-icons-url-view-url').text(current_url.split('?')[0]);
			        	}, 1000);
			        });
			    });
			}
		});
		
		function instant_ide_file_tree_right_click_preview_folder_callback(element, root, e) {
			if(!$('body').hasClass('instant-ide-site-preview-active')) {
				$('#sub-menu-site-preview-open').click();
			}
			if(root) {
				var preview_url = platform_url+'/';
			} else {
				var preview_url = platform_url+'/'+instant_ide_get_rel_path(element, e)+'/';
			}
			$('#instant-ide-site-preview-container').html('<iframe id="instant-ide-site-preview" src="'+preview_url+'?timestamp='+Date.now()+'"></iframe>');
		    $('#instant-ide-site-preview-icons-url-view-url').text(preview_url);
		    $('#instant-ide-site-preview').on('load', function() {
		        $(this).contents().on('mousedown, mouseup, click', function() {
		        	setTimeout(function() {
						var current_url = document.getElementById('instant-ide-site-preview').contentWindow.location.href;
			            $('#instant-ide-site-preview-icons-url-view-url').text(current_url.split('?')[0]);
		        	}, 1000);
		        });
		    });
		}
		
		$('.instant-ide-site-preview-icons-container i:not(.fa-external-link)').click(function() {
			if($(this).hasClass('fa-refresh')) {
				document.getElementById('instant-ide-site-preview').contentWindow.location.reload();
			} else {
				var width = $(this).attr('title').split('x')[0];
				var height = $(this).attr('title').split('x')[1];
				if(width == '100') {
					$('#instant-ide-site-preview').css('width', '100%').css('min-width', '1300px').css('height', '100%');
				} else {
					$('#instant-ide-site-preview').css('width', width+'px').css('min-width', width+'px').css('height', height+'px');
				}
			}
		});
		
		$('.instant-ide-site-preview-icons-container i.fa-external-link').click(function() {
			var current_url = document.getElementById('instant-ide-site-preview').contentWindow.location.href;
			window.open(current_url.split('?')[0], '_blank');
		});

		$(window).resize(function() {
			$('#instant-ide-site-preview-icons-url-view').width($('#instant-ide-site-preview-container').width() - 171);
		});
		
		$('.menu-heading-options').click(function() {
			$('body').toggleClass('instant-ide-options-active');
			$('#instant-ide-file-editor-options-container').fadeToggle('medium');
			$('#instant-ide-file-editor-options-overlay').fadeToggle('medium');
		});
		
		$('#instant-ide-file-editor-options-container i').click(function() {
			$('.menu-heading-options').click();
		});
		
		$('#instant-ide-active-editor').val(localStorage.getItem('iide_active_editor'));
		
		$('#instant-ide-active-editor').change( function() {
			var active_editor = $(this).val();
			if(active_editor != localStorage.getItem('iide_active_editor')) {
				$.ajax({
					type : 'POST',
					url : iide_url+'editor/file-editor-ajax.php',
					data : {
						action : 'instant_ide_active_editor_write',
						active_editor : active_editor
					},
					success : function(response) {
						if(response) {
							swal(response)
		                    swal({
		                        text: response,
		                        type: 'success'
		                    })
		                    .then((value) => {
								swal({
									title: 'Reload To Finalize Changes!',
									text: 'To finalize these changes you must reload Instant IDE. Would you like to do so now?',
									type: 'info',
									showCancelButton: true,
									confirmButtonText: 'Yes',
									cancelButtonText: 'No'
								}).then((result) => {
									if (result.value) {
										setTimeout(function() { window.location.reload(true); }, 2000);
									}
								})
		                    })
						}
					}
				});
				localStorage.setItem('iide_active_editor', active_editor);
			}
		});
		
		$('#instant-ide-dev-path').val(localStorage.getItem('iide_dev_path'));
		
		$('#instant-ide-dev-path').blur( function() {
			var dev_path = $(this).val();
			if(dev_path != localStorage.getItem('iide_dev_path')) {
				$.ajax({
					type : 'POST',
					url : iide_url+'editor/file-editor-ajax.php',
					data : {
						action : 'instant_ide_dev_path_write',
						dev_path : dev_path
					},
					success : function(response) {
						if(response) {
							swal(response)
		                    swal({
		                        text: response,
		                        type: 'success'
		                    })
		                    .then((value) => {
								swal({
									title: 'Reload To Finalize Changes!',
									text: 'To finalize these changes you must reload Instant IDE. Would you like to do so now?',
									type: 'info',
									showCancelButton: true,
									confirmButtonText: 'Yes',
									cancelButtonText: 'No'
								}).then((result) => {
									if (result.value) {
										setTimeout(function() { window.location.reload(true); }, 2000);
									}
								})
		                    })
						}
					}
				});
				localStorage.setItem('iide_dev_path', dev_path);
			}
		});
		
		$('input[name="file_tree_width"][value="'+localStorage.getItem('iide_file_tree_width')+'"]').prop('checked', true);
		$('body').removeClass(function(index, className) {
			return (className.match(/(^|\s)instant-ide-file-tree-width-\S+/g) || []).join(' ');
		}).addClass('instant-ide-file-tree-width-'+localStorage.getItem('iide_file_tree_width'));
		
		$('input[name=file_tree_width]').change( function() {
			var selection = $(this).val();
			$('body').removeClass(function(index, className) {
				return (className.match(/(^|\s)instant-ide-file-tree-width-\S+/g) || []).join(' ');
			}).addClass('instant-ide-file-tree-width-'+selection);
			localStorage.setItem('iide_file_tree_width', selection);
			$(window).resize();
		});
	
		$('input[name="console_height"][value="'+localStorage.getItem('iide_console_height')+'"]').prop('checked', true);
		$('body').removeClass(function(index, className) {
			return (className.match(/(^|\s)instant-ide-console-height-\S+/g) || []).join(' ');
		}).addClass('instant-ide-console-height-'+localStorage.getItem('iide_console_height'));
		
		$('input[name=console_height]').change( function() {
			var selection = $(this).val();
			$('body').removeClass(function(index, className) {
				return (className.match(/(^|\s)instant-ide-console-height-\S+/g) || []).join(' ');
			}).addClass('instant-ide-console-height-'+selection);
			localStorage.setItem('iide_console_height', selection);
			$(window).resize();
		});
		
		$('input[name="site_preview_width"][value="'+localStorage.getItem('iide_site_preview_width')+'"]').prop('checked', true);
		$('body').removeClass(function(index, className) {
			return (className.match(/(^|\s)instant-ide-site-preview-width-\S+/g) || []).join(' ');
		}).addClass('instant-ide-site-preview-width-'+localStorage.getItem('iide_site_preview_width'));
		
		$('input[name=site_preview_width]').change( function() {
			var selection = $(this).val();
			$('body').removeClass(function(index, className) {
				return (className.match(/(^|\s)instant-ide-site-preview-width-\S+/g) || []).join(' ');
			}).addClass('instant-ide-site-preview-width-'+selection);
			localStorage.setItem('iide_site_preview_width', selection);
			$(window).resize();
		});
		
		$('input[name="iide_theme"][value="'+localStorage.getItem('iide_theme')+'"]').prop('checked', true);
		$('body').removeClass(function(index, className) {
			return (className.match(/(^|\s)instant-ide-theme-\S+/g) || []).join(' ');
		}).addClass('instant-ide-theme-'+localStorage.getItem('iide_theme'));
		
		$('input[name=iide_theme]').change( function() {
			var selection = $(this).val();
			$('body').removeClass(function(index, className) {
				return (className.match(/(^|\s)instant-ide-theme-\S+/g) || []).join(' ');
			}).addClass('instant-ide-theme-'+selection);
			var iframe_dom = $('iframe#instant-ide-file-editor-console').contents();
			if(iframe_dom !== null) {
				iframe_dom.find('body').removeClass(function(index, className) {
					return (className.match(/(^|\s)instant-ide-theme-\S+/g) || []).join(' ');
				}).addClass('instant-ide-theme-'+selection);
			}
			localStorage.setItem('iide_theme', selection);
		});
		
		if($('body').hasClass('instant-ide-monaco-editor-active')) {
			$('#instant-ide-monaco-editor-theme').val(localStorage.getItem('iide_monaco_editor_theme'));
			$('#instant-ide-monaco-editor-theme-preview img').attr('src', iide_url+'assets/css/images/monaco-themes/'+localStorage.getItem('iide_monaco_editor_theme')+'.png');
			$('body').removeClass(function(index, className) {
				return (className.match(/(^|\s)instant-ide-monaco-theme-\S+/g) || []).join(' ');
			}).addClass('instant-ide-monaco-theme-'+localStorage.getItem('iide_monaco_editor_theme'));
			
			$('#instant-ide-monaco-editor-theme').change( function() {
				var selection = $(this).val();
				$('#instant-ide-monaco-editor-theme-preview img').attr('src', iide_url+'assets/css/images/monaco-themes/'+selection+'.png');
				$('body').removeClass(function(index, className) {
					return (className.match(/(^|\s)instant-ide-monaco-theme-\S+/g) || []).join(' ');
				}).addClass('instant-ide-monaco-theme-'+selection);
				localStorage.setItem('iide_monaco_editor_theme', selection);
			});
		} else {
			$('#instant-ide-ace-editor-theme').val(localStorage.getItem('iide_ace_editor_theme'));
			$('#instant-ide-ace-editor-theme-preview img').attr('src', iide_url+'assets/css/images/ace-themes/'+localStorage.getItem('iide_ace_editor_theme')+'.png');
			
			$('#instant-ide-ace-editor-theme').change( function() {
				var selection = $(this).val();
				$('#instant-ide-ace-editor-theme-preview img').attr('src', iide_url+'assets/css/images/ace-themes/'+selection+'.png');
				localStorage.setItem('iide_ace_editor_theme', selection);
			});
		}
		
		$('.instant-ide-file-tree').on('click', '.iideft-file', function(e) {
	        if(e.shiftKey) {
				$(this).addClass('iideft-file-selected');
	        }
		});
	
		$(window).keydown(function(e) {
			if((e.ctrlKey || e.metaKey) && e.which == 83) {
				e.preventDefault();
				$('.instant-ide-file-editor-active-form .instant-ide-file-editor-save-button').click();
				return false;
			}
			if((e.ctrlKey || e.metaKey) && e.which == 70) {
				e.preventDefault();
				return false;
			}
	        if($('.iideft-file-selected')[0]) {
		        if(e.shiftKey && e.which == 38) {
					$('.instant-ide-file-tree').find('.iideft-file-selected').first().prev().not('.iideft-directory').addClass('iideft-file-selected');
		        }
		        if(e.shiftKey && e.which == 40) {
					$('.instant-ide-file-tree').find('.iideft-file-selected').last().next().addClass('iideft-file-selected');
		        }
	        }
	        if($('body').hasClass('instant-ide-image-view-active')) {
		        if(e.which == 27) {
					$('#instant-ide-file-editor-image-view-container i.instant-ide-file-editor-image-view-close').click();
		        }
	        }
	        if($('body').hasClass('instant-ide-options-active')) {
		        if(e.which == 27) {
					$('.menu-heading-options').click();
		        }
	        }
	        if($('body').hasClass('instant-ide-site-preview-address-bar-active')) {
		        if(e.which == 13) {
					$('#instant-ide-site-preview').attr('src', $('#instant-ide-site-preview-icons-url-view-url').text());
					return false;
		        }
	        }
		});
		
		window.onbeforeunload = function(e) {
			if($('.instant-ide-file-editor-tab-quit.file-changed').length) {
				return confirm('Confirm refresh');
			}
		};
	
	});
})(jQuery);