(function($) {
    $.log = function(text) {
        if(typeof(window['console'])!='undefined') console.log(text);
    };

    $.swift_page_builder = {
        isMainContainerEmpty: function() {
            if(!jQuery('.spb_main_sortable > div').length) {
                $('.metabox-builder-content').addClass('empty-builder');
            } else {
                $('.metabox-builder-content').removeClass('empty-builder');
            }
        },
        cloneSelectedImagesFromMediaTab: function(html, $ids) {
            var $button = $('.spb_current_active_media_button').removeClass('.spb_current_active_media_button');

            var attached_img_div = $button.next(),
                site_img_div	 = $button.next().next();

            var hidden_ids = attached_img_div.prev().prev(),
                img_ul = attached_img_div.find('.gallery_widget_attached_images_list');

            img_ul.html(html);

            var hidden_ids_value = '';
            img_ul.find('li').each(function() {
                hidden_ids_value += (hidden_ids_value.length>0 ? ',' : '') + $(this).attr('media_id');
            });

            hidden_ids.val(hidden_ids_value);

            attachedImgSortable(img_ul);

            tb_remove();

        },
        galleryImagesControls: function() {
            $('.gallery_widget_add_images').live("click", function(e) {
                	
            	e.preventDefault();
            	
            	var file_frame = "",
            		parentField = $(this).parent().find('.attach_image'),
            		attachedImages = $(this).parent().find('.gallery_widget_attached_images_list');
            	
            	// If the media frame already exists, reopen it.
            	if ( file_frame ) {
            		file_frame.open();
            		return;
            	}
            	
            	// Create the media frame.
            	file_frame = wp.media.frames.file_frame = wp.media({
            		title: jQuery( this ).data( 'uploader_title' ),
            		button: {
            			text: jQuery( this ).data( 'uploader_button_text' ),
            		},
            		multiple: false  // Set to true to allow multiple files to be selected
            	});
            	
            	// When an image is selected, run a callback.
            	file_frame.on( 'select', function() {
            		// We set multiple to false so only get one image from the uploader
            		attachment = file_frame.state().get('selection').first().toJSON();
            		
            		parentField.val(attachment.id);
            		attachedImages.empty();
            		attachedImages.append('<li class="added" media_id="'+attachment.id+'"><img src="'+attachment.url+'" alt="" rel="'+attachment.id+'"></li>');
            	});
            	
            	// Finally, open the modal
            	file_frame.open();
            });

            $('.gallery_widget_img_select li').live("click", function(e) {
                $(this).toggleClass('added');

                var hidden_ids = $(this).parent().parent().prev().prev().prev(),
                    ids_array = (hidden_ids.val().length > 0) ? hidden_ids.val().split(",") : new Array(),
                    img_rel = $(this).find("img").attr("rel"),
                    id_pos = $.inArray(img_rel, ids_array);

                /* if not found */
                if ( id_pos == -1 ) {
                    ids_array.push(img_rel);
                }
                else {
                    ids_array.splice(id_pos, 1);
                }

                hidden_ids.val(ids_array.join(","));

            });
        },
        initializeFormEditing: function(element) {
            //
            $('#spb_edit_form .wp-editor-wrap .textarea_html').each(function(index) {
                initTinyMce($(this));
            });

            $('#spb_edit_form .gallery_widget_attached_images_list').each(function(index) {
                attachedImgSortable($(this));
            });


            // Get callback function name
            var cb = element.children(".spb_edit_callback");
            //
            if ( cb.length == 1 ) {
                var fn = window[cb.attr("value")];
                if ( typeof fn === 'function' ) {
                    var tmp_output = fn(element);
                }
            }

            $('.spb_save_edit_form').unbind('click').click(function(e) {
                e.preventDefault();
                saveFormEditing(element);//(element);

            });

            $('#cancel-background-options').unbind('click').click(function(e){
                e.preventDefault();
                jQuery('body').css('overflow', '');
                //$('.spb_main_sortable, #spb-elements, .spb_switch-to-builder').show();
                $('.spb_tinymce').each(function () {
                    if (tinyMCE.majorVersion >= 4) {
                    	tinyMCE.execCommand("mceRemoveEditor", true, $(this).attr('id'));
                    } else {
                    	tinyMCE.execCommand("mceRemoveControl", true, $(this).attr('id'));
                    }
                });
                $('#spb_edit_form').html('').fadeOut();
                //$('body, html').scrollTop(current_scroll_pos);
                $("#publish").show();
            });
        },
        onDragPlaceholder: function() {
            return $('<div id="drag_placeholder"></div>');
        },
        addLastClass: function(dom_tree) {
            var total_width, width, next_width;
            total_width = 0;
            width = 0;
            next_width = 0;
            $dom_tree = $(dom_tree);

            $dom_tree.children(".spb_sortable").removeClass("spb_first spb_last");
            if ($dom_tree.hasClass("spb_main_sortable")) {
                $dom_tree.find(".spb_sortable .spb_sortable").removeClass("sortable_1st_level");
                $dom_tree.children(".spb_sortable").addClass("sortable_1st_level");
                $dom_tree.children(".spb_sortable:eq(0)").addClass("spb_first");
                $dom_tree.children(".spb_sortable:last").addClass("spb_last");
            }

            if ($dom_tree.hasClass("spb_column_container")) {
                $dom_tree.children(".spb_sortable:eq(0)").addClass("spb_first");
                $dom_tree.children(".spb_sortable:last").addClass("spb_last");
            }

            $dom_tree.children(".spb_sortable").each(function (index) {

                var cur_el = $(this);

                // Width of current element
                if (cur_el.hasClass("span12")
                    || cur_el.hasClass("spb_widget")) {
                    width = 12;
                }
                else if (cur_el.hasClass("span10")) {
                    width = 10;
                }
                else if (cur_el.hasClass("span9")) {
                    width = 9;
                }
                else if (cur_el.hasClass("span8")) {
                    width = 8;
                }
                else if (cur_el.hasClass("span6")) {
                    width = 6;
                }
                else if (cur_el.hasClass("span4")) {
                    width = 4;
                }
                else if (cur_el.hasClass("span3")) {
                    width = 3;
                }
                else if (cur_el.hasClass("span2")) {
                    width = 2;
                }
                total_width += width;

                if (total_width > 10 && total_width <= 12) {
                    cur_el.addClass("spb_last");
                    cur_el.next('.spb_sortable').addClass("spb_first");
                    total_width = 0;
                }
                if (total_width > 12) {
                    cur_el.addClass('spb_first');
                    cur_el.prev('.spb_sortable').addClass("spb_last");
                    total_width = width;
                }

                if (cur_el.hasClass('spb_column') || cur_el.hasClass('spb_tabs') || cur_el.hasClass('spb_tour') || cur_el.hasClass('spb_accordion')) {

                    if (cur_el.find('.spb_element_wrapper .spb_column_container').length > 0) {
                        cur_el.removeClass('empty_column');
                        cur_el.addClass('not_empty_column');
                        //addLastClass(cur_el.find('.spb_element_wrapper .spb_column_container'));
                        cur_el.find('.spb_element_wrapper .spb_column_container').each(function (index) {
                            $.swift_page_builder.addLastClass($(this)); // Seems it does nothing

                            if($(this).find('div:not(.container-helper)').length==0) {
                                $(this).addClass('empty_column');
                                $(this).html($('#container-helper-block').html());
                            } else {
                                $(this).removeClass('empty_column');
                            }
                        });
                    }
                    else if (cur_el.find('.spb_element_wrapper .spb_column_container').length == 0) {
                        cur_el.removeClass('not_empty_column');
                        cur_el.addClass('empty_column');
                    }
                    else {
                        cur_el.removeClass('empty_column not_empty_column');
                    }
                }
            });
        }, // endjQuery.swift_page_builder.addLastClass()
        save_spb_html: function() {
            this.addLastClass($(".spb_main_sortable"));

            var shortcodes = generateShortcodesFromHtml($(".spb_main_sortable"));

            if ( isTinyMceActive() != true ) {
                $('#content').val(shortcodes);
            } else {
                //tinyMCE.activeEditor.setContent(shortcodes, {format : 'html'});
                tinyMCE.get('content').setContent(shortcodes, {format : 'html'});
            }
        }
    }
})(jQuery);

jQuery(document).ready(function($) {
	/* On load initialize sortable and dragable elements
	---------------------------------------------------------- */
    /*
	$('.spb_main_sortable').sortable({
		forcePlaceholderSize: true,
		placeholder: "widgets-placeholder",
		// cursorAt: { left: 10, top : 20 },
		cursor: "move",
		items: "div.sortable_1st_level",//spb_sortable
		update: function() {$.swift_page_builder.save_spb_html(); }
	});
    */
    $( "#swift_page_builder .dropable_el, #swift_page_builder .dropable_column" ).draggable({
        helper: function() { return $('<div id="drag_placeholder"></div>').appendTo('body')},
        zIndex: 99999,
        // cursorAt: { left: 10, top : 20 },
        cursor: "move",
        // appendTo: "body",
        revert: "invalid",
        start: function(event, ui) { renderCorrectPlaceholder(event, ui);}
    });
    initDroppable();
    
    
   
	 /* Make menu elements dropable */

	//$('.dropdown-toggle').dropdown();
	
	$('.dropdown-toggle').on('click', function(e) {
				
		e.preventDefault();
			
		$('.dropdown-toggle').removeClass('selected');
		
		if ($('#spb-elements').hasClass('subnav-fixed') || $(this).hasClass('spb_templates')) {
			
			slideOutClose();
			
			if ($(this).parent().hasClass('open')) {
				$(this).parent().removeClass('open');
				return
			} else {
				$('#spb-elements .dropdown').removeClass('open')
				$(this).parent().addClass('open');
			}
				
		} else {
			
			$('.dropdown').removeClass('open');
			
			var slideOutWrap = $('#spb-option-slideout'),
				slideout = $(this).attr('data-slideout'),
				slideoutElement = $('.'+slideout);
			
			if (slideoutElement.hasClass('selected')) {
				$('.spb-item-slideout').removeClass('selected');
				slideoutElement.slideUp(400);
				return;
			}
			
			$(this).addClass('selected');
			
			if (slideOutWrap.hasClass('open')) {
				
				slideOutClose();
				$('.spb-item-slideout').removeClass('selected');
				setTimeout(function() {
					slideoutElement.slideDown();
				}, 600);
				slideoutElement.addClass('selected');
				slideOutWrap.removeClass('open');
			
			} else {
			
				slideOutWrap.addClass('open');
				slideoutElement.addClass('selected');
				slideoutElement.slideDown();
			
			}
			
		}
		
	});
	
	function slideOutClose() {
		$('.spb-item-slideout').slideUp(400);
	};

	/* Add action for menu buttons with 'clickable_action' class name */
	$("#spb-elements .clickable_action").click(function(e) {
		e.preventDefault();
		getElementMarkup($('.main_wrapper'), $(this), "initDroppable");
	});
	
	$(".spb-content-elements .clickable_action").click(function(e) {
		e.preventDefault();
		getElementMarkup($('.main_wrapper'), $(this), "initDroppable");
	});

	$("#spb-elements .clickable_layout_action").click(function(e) {
		e.preventDefault();
		getElementMarkup($('.main_wrapper'), $(this), "initDroppable");
	});

	columnControls(); /* Set action for column sizes and delete buttons */


	if ( $("#swift_page_builder").length == 1 ) {
		$('div#postdivrich').before('<p class="page-builder-switch"><a class="spb_switch-to-builder button-primary" href="#">Swift Page Builder</a></p>');

		var postdivrich = $('#postdivrich'),
			swiftPageBuilder = $('#swift_page_builder');

		$('.spb_switch-to-builder').click(function(e){
			e.preventDefault();
			if ( postdivrich.is(":visible") ) {

				if (!isTinyMceActive()) {
                    if(switchEditors!=undefined) switchEditors.switchto($('#content-tmce').get(0));
                }
					postdivrich.hide();
					swiftPageBuilder.show();
					$('#spb_js_status').val("true");
					$(this).html('Classic editor');

					spb_shortcodesToBuilder();
					spb_navOnScroll();
				// } else {
				//	alert("Please switch default WordPress editor to 'Visual' mode first.");
				// }
			}
			else {
				postdivrich.show();
				swiftPageBuilder.hide();
				$('#spb_js_status').val("false");
				$(this).html('Swift Page Builder');
			}
		});

		/* Decide what editor to show on load
		---------------------------------------------------------- */
		if ( $('#spb_js_status').val() == 'true' && jQuery('#wp-content-wrap').hasClass('tmce-active') ) {
			//if ( isTinyMceActive() == true ) {
				postdivrich.hide();
				swiftPageBuilder.show();
				$('.spb_switch-to-builder').html('Classic editor');
			//} else {
			//	alert("Please switch default WordPress editor to 'Visual' mode first.");
			//}

			//spb_shortcodesToBuilder();
		} else {
			postdivrich.show();
			swiftPageBuilder.hide();
			$('.spb_switch-to-builder').html('Swift Page Builder');
		}
	}
	jQuery(window).load(function() {
		if ( $('#spb_js_status').val() == 'true' && jQuery('#wp-content-wrap').hasClass('tmce-active') ) {
			//spb_shortcodesToBuilder();
			window.setTimeout('spb_shortcodesToBuilder()', 50);
			spb_navOnScroll();
		}
	});

	/*** Toggle click (FAQ) ***/
	jQuery(".toggle_title").live("click", function(e) {
		if ( jQuery(this).hasClass('toggle_title_active') ) {
			jQuery(this).removeClass('toggle_title_active').next().hide();
		} else {
			jQuery(this).addClass('toggle_title_active').next().show();
		}
	});

	/*** Gallery Controls / Site attached images ***/
    $.swift_page_builder.galleryImagesControls(); /* Actions for gallery images handling */
	/*jQuery('.gallery_widget_attached_images_list').each(function(index) {
		attachedImgSortable(jQuery(this));
	});*/

	/*** Template System ***/
	spb_templateSystem();

    $('#swift_page_builder').on('click', '.add-text-block-to-content', function(e) {
        e.preventDefault();
        if($(this).attr('parent-container')) {
        	if ($(this).parent().parent().hasClass('ui-accordion-content') || $(this).parent().parent().hasClass('ui-tabs-panel')) {
        		getElementMarkup($(this).parent().parent(), $('#spb_text_block'));
        	} else if ($(this).parent().parent().parent().hasClass('ui-accordion-content') || $(this).parent().parent().parent().hasClass('ui-tabs-panel')) {
        		getElementMarkup($(this).parent().parent(), $('#spb_text_block'));
        	} else {
           		getElementMarkup($($(this).attr('parent-container')), $('#spb_text_block'));
           	}
        } else {
            getElementMarkup($(this).parent().parent().parent(), $('#spb_text_block'));
        }
    });
    
    function sortElementsDropdown() {
   		
   		var mylist = $('.spb_content_elements').parent().find('ul');
   		var listitems = mylist.children('li').get();
   		listitems.sort(function(a, b) {
   		   var compA = $(a).text().toUpperCase();
   		   var compB = $(b).text().toUpperCase();
   		   return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
   		})
   		$.each(listitems, function(idx, itm) { mylist.append(itm); });
    	
    } sortElementsDropdown();
    
    function sortElementsSlideout() {
    		
    		var mylist = $('.spb-content-elements');
    		var listitems = mylist.children('li').get();
    		listitems.sort(function(a, b) {
    		   var compA = $(a).text().toUpperCase();
    		   var compB = $(b).text().toUpperCase();
    		   return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
    		})
    		$.each(listitems, function(idx, itm) { mylist.append(itm); });
    	
    } sortElementsSlideout();
    
    
    $('.alt_background').live('change',function(){
        $('.altbg-preview').attr('class', 'altbg-preview');
        $('.altbg-preview').addClass(jQuery(this).val());
    });
    
    $('#clear-spb').on('click', function(e) {
    	e.preventDefault();
    	clear_page_builder_content();
    });
    
}); // end jQuery(document).ready

function open_elements_dropdown() {
    jQuery('.spb_content_elements:first').trigger('click');
}

function open_layouts_dropdown() {
    jQuery('.spb_popular_layouts:first').trigger('click');
}

/**
 * Swift Page Builder class
 */

function spb_templateSystem() {
	jQuery('#spb_save_template').live("click", function(e) {
		e.preventDefault();

		var template_name = prompt("Please enter templates name", '');
		if ( template_name != null && template_name != "" ) {
			var template = generateShortcodesFromHtml(jQuery(".spb_main_sortable"));
			var data = {
				action: 'spb_save_template',
				template: template,
				template_name: template_name
			};

			jQuery.post(ajaxurl, data, function(response) {
				jQuery('.spb_templates_ul').html(response);
			});
		} else {
			alert("Error. Please try again.");
		}
	});
	
	jQuery('.sf_prebuilt_template a').live("click", function(e) {
		e.preventDefault();
		
		var data = {
			action: 'sf_load_template',
			template_id: jQuery(this).attr('data-template_id')
		};

		jQuery.post(ajaxurl, data, function(response) {	
			jQuery('.spb_main_sortable').append(response).find(".spb_init_callback").each(function(index) {
				var fn = window[jQuery(this).attr("value")];
				if ( typeof fn === 'function' ) {
				    fn(jQuery(this).closest('.spb_content_element'));
				}
			});
			//
			initDroppable();
			save_spb_html();
		});
	});

	jQuery('.spb_template_li a').live("click", function(e) {
		e.preventDefault();
		var data = {
			action: 'spb_load_template',
			template_id: jQuery(this).attr('data-template_id')
		};

		jQuery.post(ajaxurl, data, function(response) {
			jQuery('.spb_main_sortable').append(response).find(".spb_init_callback").each(function(index) {
				var fn = window[jQuery(this).attr("value")];
				if ( typeof fn === 'function' ) {
				    fn(jQuery(this).closest('.spb_content_element'));
				}
			});
			//
			initDroppable();
			save_spb_html();
		});
	});

	jQuery('.spb_remove_template').live("click", function(e) {
		e.preventDefault();
		var template_name = jQuery(this).closest('.spb_template_li').find('a').text();
		var answer = confirm ("Confirm deleting '"+template_name+"' template, press Cancel to leave. This action cannot be undone.");
		if (answer) {
			//alert("delete");
			var data = {
				action: 'spb_delete_template',
				template_id: jQuery(this).closest('.spb_template_li').find('a').attr('data-template_id')
			};

			jQuery.post(ajaxurl, data, function(response) {
				jQuery('.spb_templates_ul').html(response);
			});
		}
	});
}

// fix sub nav on scroll
var $win, $nav,	navTop,	isFixed = 0;
function spb_navOnScroll() {
	$win = jQuery(window);
	$nav = jQuery('#spb-elements');
	navTop = jQuery('#spb-elements').length && jQuery('#spb-elements').offset().top - 28;
	isFixed = 0;

	spb_processScroll();
	$win.on('scroll', spb_processScroll);
}
function spb_processScroll() {
	var i,
		scrollTop = $win.scrollTop();

	if ( scrollTop >= navTop && !isFixed ) {
		isFixed = 1;
		$nav.addClass('subnav-fixed')
		
	} else if (scrollTop <= navTop && isFixed) {
		isFixed = 0;
		$nav.removeClass('subnav-fixed');
		jQuery('#spb-elements .dropdown').removeClass('open')
	}
}





function hideEditFormSaveButton() {
	jQuery('#spb_edit_form .edit_form_actions').hide();
}
function showEditFormSaveButton() {
	jQuery('#spb_edit_form .edit_form_actions').show();
}

/* Updates ids order in hidden input field, on drag-n-drop reorder */
function updateSelectedImagesOrderIds(img_ul) {
	var img_ids = new Array();

	jQuery(img_ul).find('.added img').each(function() {
		img_ids.push(jQuery(this).attr("rel"));
	});

	jQuery(img_ul).parent().prev().prev().val(img_ids.join(','));
}

/* Takes ids from hidden field and clone li's */
function cloneSelectedImages(site_img_div, attached_img_div) {
	var hidden_ids = jQuery(attached_img_div).prev().prev(),
		ids_array = (hidden_ids.val().length > 0) ? hidden_ids.val().split(",") : new Array(),
		img_ul = attached_img_div.find('.gallery_widget_attached_images_list');

	img_ul.html('');

	jQuery.each(ids_array, function(index, value) {
		jQuery(site_img_div).find('img[rel='+value+']').parent().clone().appendTo(img_ul);
	});
	attachedImgSortable(img_ul);
}

function attachedImgSortable(img_ul) {
	jQuery(img_ul).sortable({
		forcePlaceholderSize: true,
		placeholder: "widgets-placeholder",
		cursor: "move",
		items: "li",
		update: function() { updateSelectedImagesOrderIds(img_ul); }
	});
}



/* Get content from tinyMCE editor and convert it to Page Builder Assets
---------------------------------------------------------- */
function spb_shortcodesToBuilder() {
	var content = spb_getContentFromTinyMCE();

	jQuery('.spb_main_sortable').html(jQuery('#spb_loading').val());
		
	var data = {
		action: 'spb_shortcodes_to_builder',
		content: content
	};

	jQuery.post(ajaxurl, data, function(response) {
		jQuery('.spb_main_sortable').html(response);
        jQuery.swift_page_builder.isMainContainerEmpty();
		//
		//console.log(response);
		jQuery.swift_page_builder.addLastClass(jQuery(".spb_main_sortable"));
		initDroppable();

		//Fire INIT callback if it is defined
		jQuery('.spb_main_sortable').find(".spb_init_callback").each(function(index) {
			var fn = window[jQuery(this).attr("value")];
			if ( typeof fn === 'function' ) {
			    fn(jQuery(this).closest('.spb_sortable'));
			}
		});
	});
}

/* get content from tinyMCE editor
---------------------------------------------------------- */
function spb_getContentFromTinyMCE() {
	var content = '';

	//if ( tinyMCE.activeEditor ) {
	if ( isTinyMceActive() ) {
		var spb_ed = tinyMCE.get('content'); // get editor instance
		content = spb_ed.save();
	} else {
		content = jQuery('#content').val();
	}
	return content;
}


/* This makes layout elements droppable, so user can drag
   them from on column to another and sort them (re-order)
   within the current column
---------------------------------------------------------- */
function initDroppable() {
    jQuery('.spb_sortable_container').sortable({
        forcePlaceholderSize: true,
        connectWith: ".spb_sortable_container",
        placeholder: "widgets-placeholder",
        // cursorAt: { left: 10, top : 20 },
        cursor: "move",
        items: "div.spb_sortable",//spb_sortablee
        distance: 0.5,
        start: function() {
            jQuery('#spb_content').addClass('sorting-started');
        },
        stop: function(event, ui) {
            jQuery('#spb_content').removeClass('sorting-started');
        },
        update: function() {jQuery.swift_page_builder.save_spb_html(); },
        over: function(event, ui) {
            ui.placeholder.css({maxWidth: ui.placeholder.parent().width()});
            ui.placeholder.removeClass('hidden-placeholder');
            if( ui.item.hasClass('not-column-inherit') && ui.placeholder.parent().hasClass('not-column-inherit')) {
                ui.placeholder.addClass('hidden-placeholder');
            }

        },
        beforeStop: function(event, ui) {
            if( ui.item.hasClass('not-column-inherit') && ui.placeholder.parent().hasClass('not-column-inherit')) {
                return false;
            }
        }
    });


/*
    jQuery('.spb_column_container').sortable({
        connectWith: ".spb_column_container, .spb_main_sortable",
        //connectWith: ".sortable_1st_level.spb_column",
        forcePlaceholderSize: true,
        placeholder: "widgets-placeholder",
        // cursorAt: { left: 10, top : 20 },
        cursor: "move",
        items: "div.spb_sortable:not(.spb_column)",
        update: function() { jQuery.swift_page_builder.save_spb_html(); },
    });
*/
    jQuery('.spb_main_sortable').droppable({
        greedy: true,
        accept: ".dropable_el, .dropable_column",
        hoverClass: "spb_ui-state-active",
        drop: function( event, ui ) {
            //console.log(jQuery(this));
            getElementMarkup(jQuery(this), ui.draggable, "addLastClass");
        }
    });

    jQuery('.spb_column_container').droppable({
        greedy: true,
        accept: function(dropable_el) {
            if ( dropable_el.hasClass('dropable_el') && jQuery(this).hasClass('ui-droppable') && dropable_el.hasClass('not_dropable_in_third_level_nav') ) {
                return false;
            } else if ( dropable_el.hasClass('dropable_el') == true ) {
                return true;
            }

            //".dropable_el",
        },
        hoverClass: "spb_ui-state-active",
        over: function( event, ui ) {
            jQuery(this).parent().addClass("spb_ui-state-active");
        },
        out: function( event, ui ) {
            jQuery(this).parent().removeClass("spb_ui-state-active");
        },
        drop: function( event, ui ) {
            //console.log(jQuery(this));
            jQuery(this).parent().removeClass("spb_ui-state-active");
            getElementMarkup(jQuery(this), ui.draggable, "addLastClass");
        }
    });



}




function initDroppable2() {

    jQuery('.spb_main_sortable').sortable({
        forcePlaceholderSize: true,
        connectWith: ".spb_column_container",
        placeholder: "widgets-placeholder",
        // cursorAt: { left: 10, top : 20 },
        cursor: "move",
        items: "div.sortable_1st_level",//spb_sortable
        update: function() {jQuery.swift_page_builder.save_spb_html(); }
    });

	jQuery('.spb_column_container').sortable({
		connectWith: ".spb_column_container, .spb_main_sortable",
		//connectWith: ".sortable_1st_level.spb_column",
		forcePlaceholderSize: false,
		placeholder: "widgets-placeholder",
		// cursorAt: { left: 10, top : 20 },
		cursor: "move",
		items: "div.spb_sortable:not(.spb_column)",
		update: function() { jQuery.swift_page_builder.save_spb_html(); }
	});

	jQuery('.spb_main_sortable').droppable({
		greedy: true,
		accept: ".dropable_el, .dropable_column",
		hoverClass: "spb_ui-state-active",
		drop: function( event, ui ) {
			//console.log(jQuery(this));
			getElementMarkup(jQuery(this), ui.draggable, "addLastClass");
		}
	});
	jQuery('.spb_column_container').droppable({
		greedy: true,
		accept: function(dropable_el) {
			if ( dropable_el.hasClass('dropable_el') && jQuery(this).hasClass('ui-droppable') && dropable_el.hasClass('not_dropable_in_third_level_nav') ) {
				return false;
			} else if ( dropable_el.hasClass('dropable_el') == true ) {
				return true;
			}

			//".dropable_el",
		},
		hoverClass: "spb_ui-state-active",
		over: function( event, ui ) {
			jQuery(this).parent().addClass("spb_ui-state-active");
		},
		out: function( event, ui ) {
			jQuery(this).parent().removeClass("spb_ui-state-active");
		},
		drop: function( event, ui ) {
			//console.log(jQuery(this));
			jQuery(this).parent().removeClass("spb_ui-state-active");
			getElementMarkup(jQuery(this), ui.draggable, "addLastClass");
		}
	});



} // end initDroppable()


/* Get initial html markup for content element. This function
   use AJAX to run do_shortcode and then place output code into
   main content holder
---------------------------------------------------------- */
function getElementMarkup (target, element, action) {

	var data = {
		action: 'spb_get_element_backend_html',
		//column_index: jQuery(".spb_main_sortable .spb_sortable").length + 1,
		element: element.attr('id'),
		data_element: element.attr('data-element'),
		data_width: element.attr('data-width')
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);
		//jQuery(target).append(response);

		//Fire INIT callback if it is defined
		//jQuery(response).find(".spb_init_callback").each(function(index) {
        target.removeClass('empty_column');
		jQuery(target).append(response).find(".spb_init_callback").each(function(index) {
			var fn = window[jQuery(this).attr("value")];
			if ( typeof fn === 'function' ) {
			    fn(jQuery(this).closest('.spb_content_element').removeClass('empty_column'));
			}
		});
        jQuery.swift_page_builder.isMainContainerEmpty();
		////


		//initTabs();
		//if (action == 'initDroppable') { initDroppable(); }
		initDroppable();
		save_spb_html();
		jQuery('.spb-item-slideout').slideUp(400);
		jQuery('.spb-item-slideout').removeClass('selected');
		jQuery('#spb-elements .dropdown .dropdown-toggle').removeClass('selected');
	});

} // end getElementMarkup()



/* Set action for column size and delete buttons
---------------------------------------------------------- */
function columnControls() {
	jQuery(".column_delete").live("click", function(e) {
		e.preventDefault();
		var answer = confirm ("If you'd like to delete this block, press OK. If you want to return, press Cancel.");
		if (answer) {
            $parent = jQuery(this).closest(".spb_sortable");
			jQuery(this).closest(".spb_sortable").remove();
            $parent.addClass('empty_column');
			save_spb_html();
		}
	});
	jQuery(".column_clone").live("click", function(e) {
		e.preventDefault();
		var closest_el = jQuery(this).closest(".spb_sortable"),
			cloned = closest_el.clone();

		cloned.insertAfter(closest_el).hide().fadeIn();

		//Fire INIT callback if it is defined
		cloned.find('.spb_initialized').removeClass('spb_initialized');
		cloned.find(".spb_init_callback").each(function(index) {
			var fn = window[jQuery(this).attr("value")];
			if ( typeof fn === 'function' ) {
			    fn(cloned);
			}
		});

		//closest_el.clone().appendTo(jQuery(this).closest(".spb_main_sortable, .spb_column_container")).hide().fadeIn();
		save_spb_html();
	});

	jQuery(".spb_sortable .spb_sortable .column_popup").live("click", function(e) {
		e.preventDefault();
		var answer = confirm ("Press OK to pop (move) section to the top level, Cancel to leave");
		if (answer) {
			jQuery(this).closest(".spb_sortable").appendTo('.spb_main_sortable');//insertBefore('.spb_main_sortable div.spb_clear:last');
			initDroppable();
			save_spb_html();
		}
	});

	jQuery(".column_edit, .column_edit_trigger").live("click", function(e) {
		e.preventDefault();
		//jQuery('body,html').animate({ scrollTop: 0});
		var element = jQuery(this).closest('.spb_sortable');
		showEditForm(element);
	});



	jQuery(".column_increase").live("click", function(e) {
		e.preventDefault();
		var column = jQuery(this).closest(".spb_sortable"),
			sizes = getColumnSize(column),
			assetTypeIsCarousel = column.hasClass('spb_carousel') || column.hasClass('spb_team') || column.hasClass('spb_products');
		if (assetTypeIsCarousel) {
			sizes = getAltColumnSize(column);
			if (sizes[1]) {
				column.removeClass(sizes[0]).addClass(sizes[1]);
				/* get updated column size */
				sizes = getAltColumnSize(column);
				jQuery(column).find(".column_size:first").html(sizes[3]);
				save_spb_html();
			}
		} else {
			if (sizes[1]) {
				column.removeClass(sizes[0]).addClass(sizes[1]);
				/* get updated column size */
				sizes = getColumnSize(column);
				jQuery(column).find(".column_size:first").html(sizes[3]);
				save_spb_html();
			}
		}
	});
	
	jQuery(".column_decrease").live("click", function(e) {
		e.preventDefault();
		var column = jQuery(this).closest(".spb_sortable"),
			sizes = getColumnSize(column),
			assetTypeIsCarousel = column.hasClass('spb_carousel') || column.hasClass('spb_team') || column.hasClass('spb_products');
		if (assetTypeIsCarousel) {
			sizes = getAltColumnSize(column);
			if (sizes[2]) {
				column.removeClass(sizes[0]).addClass(sizes[2]);
				/* get updated column size */
				sizes = getAltColumnSize(column);
				jQuery(column).find(".column_size:first").html(sizes[3]);
				save_spb_html();
			}
		} else {
			if (sizes[2]) {
				column.removeClass(sizes[0]).addClass(sizes[2]);
				/* get updated column size */
				sizes = getColumnSize(column);
				jQuery(column).find(".column_size:first").html(sizes[3]);
				save_spb_html();
			}
		}
	});
} // end columnControls()


/* Show widget edit form
---------------------------------------------------------- */
var current_scroll_pos = 0;
function showEditForm(element) {
	//current_scroll_pos = jQuery('body, html').scrollTop();
	//
	var element_shortcode = generateShortcodesFromHtml(element, true),
		element_type = element.attr("data-element_type");

	jQuery('body').css('overflow', 'hidden');
	jQuery('#spb_edit_form').html(jQuery('#spb_loading').val()).show().css({"padding-top" : 60});
	jQuery("#publish").hide(); // hide main publish button
	//jQuery('.spb_main_sortable, #spb-elements, .spb_switch-to-builder').hide();

	var data = {
		action: 'spb_show_edit_form',
		element: element_type,
		shortcode: element_shortcode
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		jQuery('#spb_edit_form').html(response).css({"padding-top" : 0});
        jQuery.swift_page_builder.initializeFormEditing(element);
		// ALT BACKGROUND PREVIEW INIT
		var altBackgroundValue = jQuery('#spb_edit_form').find('.alt_background').val();
		if (altBackgroundValue != "") {
			jQuery('#spb_edit_form').find('.altbg-preview').addClass(altBackgroundValue);
		}
	});
}



function saveFormEditing(element) {
	jQuery("#publish").show(); // show main publish button
	jQuery('.spb_main_sortable, #spb-elements, .spb_switch-to-builder').show();

	//save data
	jQuery("#spb_edit_form .spb_param_value").each(function(index) {
		var element_to_update = jQuery(this).attr("name"),
			new_value = '';

		// Textfield - input
		if ( jQuery(this).hasClass("textfield") ) {
			new_value = jQuery(this).val();
		}
		// Dropdown - select
		else if ( jQuery(this).hasClass("dropdown") ) {
			new_value = jQuery(this).val(); // get selected element

			var all_classes_ar = new Array(),
				all_classes = '';
			jQuery(this).find('option').each(function() {
				var val = jQuery(this).attr('value');
				all_classes_ar.push(val); //populate all posible dropdown values
			});

			all_classes = all_classes_ar.join(" "); // convert array to string

			//element.removeClass(all_classes).addClass(new_value); // remove all possible class names and add only selected one
			element.find('.spb_element_wrapper').removeClass(all_classes).addClass(new_value); // remove all possible class names and add only selected one
		}
		else if ( jQuery(this).hasClass("select-multiple") ) {
					
			var selected = jQuery(this).val();
					
			all_selected = selected.join(","); // convert array to string
						
			new_value = all_selected; // get selected element
						
			//element.removeClass(all_classes).addClass(new_value); // remove all possible class names and add only selected one
			//element.find('.wpb_element_wrapper').removeClass(all_classes).addClass(new_value); // remove all possible class names and add only selected one
		}
		// WYSIWYG field
		else if ( jQuery(this).hasClass("textarea_html") ) {
			new_value = getTinyMceHtml(jQuery(this));
		}
		// Check boxes
		else if ( jQuery(this).hasClass("spb-checkboxes") ) {
			var posstypes_arr = new Array();
			jQuery(this).closest('.edit_form_line').find('input').each(function(index) {
				var self = jQuery(this);
				element_to_update = self.attr("name");
				if ( self.is(':checked') ) {
					posstypes_arr.push(self.attr("id"));
				}
			});
			if ( posstypes_arr.length > 0 ) {
				new_value = posstypes_arr.join(',');
			}
		}
		// Exploded textarea
		else if ( jQuery(this).hasClass("exploded_textarea") ) {
			new_value = jQuery(this).val().replace(/\n/g, ",");
		}
		// Regular textarea
		else if ( jQuery(this).hasClass("textarea") ) {
			new_value = jQuery(this).val();
		}
        else if ( jQuery(this).hasClass("textarea_raw_html") ) {
            new_value = jQuery(this).val();
            element.find('[name='+element_to_update+'_code]').val(btoa(new_value));
            new_value = jQuery("<div/>").text(new_value).html();
        }
		// Attach images
		else if ( jQuery(this).hasClass("attach_images") ) {
			new_value = jQuery(this).val();
		}
        else if ( jQuery(this).hasClass("attach_image") ) {
            new_value = jQuery(this).val();
            /* KLUDGE: to change image */
            var $thumbnail = element.find('[name='+element_to_update+']').next('.attachment-thumbnail');

            $thumbnail.attr('src', jQuery(this).parent().find('li.added img').attr('src'));
            $thumbnail.next().addClass('image-exists');
        }

		element_to_update = element_to_update.replace('spb_tinymce_', '');
		if ( element.find('.'+element_to_update).is('div, h1,h2,h3,h4,h5,h6, span, i, b, strong, button') ) {

			//element.find('.'+element_to_update).html(new_value);
			element.find('[name='+element_to_update+']').html(new_value);
		} else {
			//element.find('.'+element_to_update).val(new_value);
			element.find('[name='+element_to_update+']').val(new_value);
		}
	});

	// Get callback function name
	var cb = element.children(".spb_save_callback");
	//
	if ( cb.length == 1 ) {
		var fn = window[cb.attr("value")];
		if ( typeof fn === 'function' ) {
		    var tmp_output = fn(element);
		}
	}

	save_spb_html();
	jQuery('#spb_edit_form').html('').hide();
	jQuery('body').css('overflow', '');
	//jQuery('body, html').scrollTop(current_scroll_pos);
}

function getTinyMceHtml(obj) {

	var mce_id = obj.attr('id'),
		html_back;
	
	// Switch back to visual editor
	window.switchEditors.go(mce_id, 'tmce');
	
	try {
		html_back = tinyMCE.get(mce_id).getContent();
		if (tinyMCE.majorVersion >= 4) {
			tinyMCE.execCommand("mceRemoveEditor", true, mce_id);
		} else {
			tinyMCE.execCommand("mceRemoveControl", true, mce_id);
		}
	}
	catch (err) {
		html_back = switchEditors.wpautop(obj.val());
	}

	return html_back;
}

function initTinyMce(element) {
	var qt, textfield_id = element.attr("id"),
	    form_line = element.closest('.edit_form_line'),
	   	content_holder = form_line.find('.spb-textarea.textarea_html');
	    content = content_holder.val();
	    
	window.tinyMCEPreInit.mceInit[textfield_id] = _.extend({}, tinyMCEPreInit.mceInit['content'], {
		resize: 'vertical',
		height: 200
	});
	
	if (_.isUndefined(tinyMCEPreInit.qtInit[textfield_id])) {
	    window.tinyMCEPreInit.qtInit[textfield_id] = _.extend({}, tinyMCEPreInit.qtInit['replycontent'], {
	    	id: textfield_id
	    });
	}
	
	element.val(content_holder.val());
	qt = quicktags( window.tinyMCEPreInit.qtInit[textfield_id] );
	QTags._buttonsInit();
	window.switchEditors.go(textfield_id, 'tmce');
	
	if (tinymce.majorVersion >= "4") {
		tinymce.execCommand( 'mceAddEditor', true, textfield_id );
	}
}

function isTinyMceActive() {
	var rich = (typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden();
	return rich;
}

/* This function helps when you need to determine current
   column size.

   Returns Array("current size", "larger size", "smaller size", "size string");
---------------------------------------------------------- */
function getColumnSize(column) {
	if (column.hasClass("span12")) //full-width
		return new Array("span12", "span12", "span9", "1/1");

	else if (column.hasClass("span9")) //three-fourth
		return new Array("span9", "span12", "span8", "3/4");

	else if (column.hasClass("span8")) //two-third
		return new Array("span8", "span9", "span6", "2/3");

	else if (column.hasClass("span6")) //one-half
		return new Array("span6", "span8", "span4", "1/2");

	else if (column.hasClass("span4")) // one-third
		return new Array("span4", "span6", "span3", "1/3");

	else if (column.hasClass("span3")) // one-fourth
		return new Array("span3", "span4", "span3", "1/4");
	else
		return false;
} // end getColumnSize()

function getAltColumnSize(column) {
	if (column.hasClass("span12")) //full-width
		return new Array("span12", "span12", "span9", "1/1");

	else if (column.hasClass("span9")) //three-fourth
		return new Array("span9", "span12", "span6", "3/4");

	else if (column.hasClass("span6")) //one-half
		return new Array("span6", "span9", "span3", "1/2");

	else if (column.hasClass("span3")) // one-fourth
		return new Array("span3", "span6", "span3", "1/4");
	else
		return false;
} // end getAltColumnSize()

/* This functions goes throw the dom tree and automatically
   adds 'last' class name to the columns elements.
---------------------------------------------------------- */
function addLastClass(dom_tree) {
    return jQuery.swift_page_builder.addLastClass(dom_tree);
	//jQuery(dom_tree).children(".column:first").addClass("first");
	//jQuery(dom_tree).children(".column:last").addClass("last");
} // endjQuery.swift_page_builder.addLastClass()

/* This functions copies html code into custom field and
   then on page reload/refresh it is used to build the
   initial layout.
---------------------------------------------------------- */
function save_spb_html() {
jQuery.swift_page_builder.addLastClass(jQuery(".spb_main_sortable"));

	var shortcodes = generateShortcodesFromHtml(jQuery(".spb_main_sortable"));
		
	if ( isTinyMceActive() != true ) {
		jQuery('#content').val(shortcodes);
	} else {
		//tinyMCE.activeEditor.setContent(shortcodes, {format : 'html'});
//		if (tinyMCE.get('excerpt')) {
//		tinyMCE.get('excerpt').setContent(shortcodes);
//		}
		if (tinyMCE.get('content')) {
		tinyMCE.get('content').setContent(shortcodes);
		}
	}

    jQuery.swift_page_builder.isMainContainerEmpty();
}

function clear_page_builder_content() {
	
	var answer = confirm("This will clear the contents of the page, are you sure?");
	if (answer) {
		if ( isTinyMceActive() != true ) {
			jQuery('#content').val('');
		} else {
			tinyMCE.activeEditor.setContent('');
		}
		jQuery('#spb_content').find(".spb_sortable").remove();
		save_spb_html();
	}
}

/* Generates shortcode values
---------------------------------------------------------- */
var current_top_level = null;
function generateShortcodesFromHtml(dom_tree, single_element) {
	var output = '';
	if ( single_element ) {
		// this is used to generate shortcode for a single content element
		selector_to_go_throw = jQuery(dom_tree);
	} else {
		selector_to_go_throw = jQuery(dom_tree).children(".spb_sortable");
	}

	selector_to_go_throw.each(function(index) {
	//jQuery(dom_tree.selector+" > .spb_sortable").each(function(index) {
		var element = jQuery(this),
			current_top_level = element,
			sc_base = element.find('.spb_sc_base').val(),
			column_el_width = getColumnSize(element),
			params = '',
			sc_ending = ']';

			element.children('.spb_element_wrapper').children('.spb_param_value').each(function(index) {
				var param_name = jQuery(this).attr("name"),
					new_value = '';
				if ( jQuery(this).hasClass("textfield") ) {
					if (jQuery(this).is('div, h1,h2,h3,h4,h5,h6, span, i, b, strong')) {
						new_value = jQuery(this).html();
					} else if ( jQuery(this).is('button') ) {
						new_value = jQuery(this).text();
					} else {
						new_value = jQuery(this).val();
					}
				}
				else if ( jQuery(this).hasClass("dropdown") ) {
					new_value = jQuery(this).val();
				}
				else if ( jQuery(this).hasClass("select-multiple") ) {
					new_value = jQuery(this).val();
				}
				else if ( jQuery(this).hasClass("textarea_raw_html") && element.children('.spb_sortable').length == 0 ) {
					content_value = jQuery(this).next('.' + param_name + '_code').val();
					sc_ending = '] '+ content_value +' [/'+sc_base+']';
				}
                else if ( jQuery(this).hasClass("textarea_html") && element.children('.spb_sortable').length == 0 ) {
                    content_value = jQuery(this).html();
                    sc_ending = '] '+content_value+' [/'+sc_base+']';
                }
				else if ( jQuery(this).hasClass("posttypes") ) {
					new_value = jQuery(this).val();
				}
				else if ( jQuery(this).hasClass("exploded_textarea") ) {
					new_value = jQuery(this).val();
				}
				else if ( jQuery(this).hasClass("textarea") ) {
					if ( jQuery(this).is('div, h1,h2,h3,h4,h5,h6, span, i, b, strong') ) {
						new_value = jQuery(this).html();
					} else {
						new_value = jQuery(this).val();
					}
				}
				else if ( jQuery(this).hasClass("attach_images") ) {
                    new_value = jQuery(this).val();
                }
                else if ( jQuery(this).hasClass("attach_image") ) {
                    new_value = jQuery(this).val();
                }
				else if ( jQuery(this).hasClass("widgetised_sidebars") ) {
					new_value = jQuery(this).val();
				}

				new_value = jQuery.trim(new_value);
				if (new_value != '') { params += ' '+param_name+'="'+new_value+'"'; }
			});


			params += ' width="'+column_el_width[3]+'"'

			if ( element.hasClass("spb_first") || element.hasClass("spb_last")) {
				var spb_first = (element.hasClass("spb_first")) ? 'first' : '';
				var spb_last = (element.hasClass("spb_last")) ? 'last' : '';
				var pos_space = (element.hasClass("spb_last") && element.hasClass("spb_first")) ? ' ' : '';
				params += ' el_position="'+spb_first+pos_space+spb_last+'"';
			}

			// Get callback function name
			var cb = element.children(".spb_shortcode_callback");
			//
			if ( cb.length == 1 ) {
				var fn = window[cb.attr("value")];
				if ( typeof fn === 'function' ) {
				    var tmp_output = fn(element);
				}
			}


			output += '['+sc_base+params+sc_ending+' ';

			//deeper
			//if ( element.children('.spb_element_wrapper').children('.spb_column_container').children('.spb_sortable').length > 0 ) {
			if ( element.children('.spb_element_wrapper').find('.spb_column_container').length > 0 ) {
				//output += generateShortcodesFromHtml(element.children('.spb_element_wrapper').children('.spb_column_container'));

				// Get callback function name
				var cb = element.children(".spb_shortcode_callback"),
					inner_element_count = 0;
				//
				element.children('.spb_element_wrapper').find('.spb_column_container').each(function(index) {
					//output += '[aaa]'+generateShortcodesFromHtml(jQuery(this))+'[/aaa]';

					var sc = generateShortcodesFromHtml(jQuery(this));
					//Fire SHORTCODE GENERATION callback if it is defined
					if ( cb.length == 1 ) {
						var fn = window[cb.attr("value")];
						if ( typeof fn === 'function' ) {
						    var tmp_output = fn(current_top_level, inner_element_count);
						}
						sc = " " + tmp_output.replace("%inner_shortcodes", sc) + " ";

						//console.log(current_top_level[0]);

						//sc = " " + tmp_output.replace("%inner_shortcodes", sc);
						inner_element_count++;
					}
					//else {
					//	output += sc;
					//}
					output += sc;
				});

				output += '[/'+sc_base+'] ';
			}
	});

	return output;
} // end generateShortcodesFromHtml()

/* This function adds a class name to the div#drag_placeholder,
   and this helps us to give a style to the draging placeholder
---------------------------------------------------------- */
function renderCorrectPlaceholder(event, ui) {
	jQuery("#drag_placeholder").addClass("column_placeholder").html("Drag and drop me into the editor");
}


/* Custom Callbacks
---------------------------------------------------------- */


/* Tabs Callbacks
---------------------------------------------------------- */
function spbTabsInitCallBack(element) {
	element.find('.spb_tabs_holder').not('.spb_initialized').each(function(index) {
		jQuery(this).addClass('spb_initialized');
		//var tab_counter = 4;
		//
		var $tabs,
			add_btn = jQuery(this).closest('.spb_element_wrapper').find('.add_tab');
		//
		$tabs = jQuery(this).tabs({
			panelTemplate: '<div class="row-fluid spb_column_container empty_column spb_sortable_container not-column-inherit">' + jQuery('#container-helper-block').html() + '</div>',
			add: function( event, ui ) {
				var tabs_count = jQuery(this).tabs( "length" ) - 1;
				jQuery(this).tabs( "select" , tabs_count);
				//
				save_spb_html();
			}
		});
		
		var sort_axis = ( jQuery(this).closest('.spb_sortable').hasClass('spb_tour')) ? 'y' : 'x';
		$tabs.find( ".ui-tabs-nav" ).sortable({
			axis: sort_axis,
			stop: function(event, ui) {
				$tabs.find('ul li').each(function(index) {
					var href = jQuery(this).find('a').attr('href').replace("#", "");
					$tabs.find('div.spb_column_container#'+href).appendTo($tabs);
				});
				//
				save_spb_html();
			}
		});
		//
		
		add_btn.click( function(e) {
			e.preventDefault();
			var tab_title = ( jQuery(this).closest('.spb_sortable').hasClass('spb_tour')) ? 'Slide' : 'Tab',
				tabs_count = jQuery(this).parent().parent().find('.ui-tabs-nav li').length + 1,
				tabs_asset = jQuery(this).parent().parent().find('.spb_tabs_holder'),
				tabs_nav = tabs_asset.find('.ui-tabs-nav');
			
			if (jQuery(this).closest('.spb_sortable').hasClass('spb_tour')) {
				tabs_nav.append('<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tab-'+tabs_count+'" aria-labelledby="ui-id-'+tabs_count+'" aria-selected="false" style="top: 0px;"><a href="#tab-'+tabs_count+'" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-'+tabs_count+'"><span>'+tab_title+' '+tabs_count+'</span></a><a class="delete_tab"></a><a class="edit_tab"></a></li>');
				
				tabs_asset.append('<div id="tab-'+tabs_count+'" class="row-fluid spb_column_container spb_sortable_container not-column-inherit ui-sortable ui-droppable ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-'+tabs_count+'" role="tabpanel" aria-expanded="true" aria-hidden="false"><div data-element_type="spb_text_block" class="spb_text_block spb_content_element spb_sortable span12 spb_first spb_last ui-sortable-helper" style="width: 615px; height: 73px; position: absolute; z-index: 1000; left: 275.6875px; top: 56px;"><input type="hidden" class="spb_sc_base" name="element_name-spb_text_block" value="spb_text_block"><div class="controls sidebar-name"> <div class="column_size_wrapper"> <a class="column_decrease" href="#" title="Decrease width"></a> <span class="column_size">1/1</span> <a class="column_increase" href="#" title="Increase width"></a> </div><div class="controls_right"> <a class="column_popup" href="#" title="Pop up"></a> <a class="column_edit" href="#" title="Edit"></a> <a class="column_clone" href="#" title="Clone"></a> <a class="column_delete" href="#" title="Delete"></a></div></div><div class="spb_element_wrapper clearfix"><input type="hidden" class="spb_param_value title textfield " name="title" value=""><input type="hidden" class="spb_param_value icon textfield " name="icon" value=""><div class="spb_param_value content textarea_html " name="content"><p> This is a text block. Click the edit button to change this text. </p></div><input type="hidden" class="spb_param_value pb_margin_bottom dropdown " name="pb_margin_bottom" value="no"><input type="hidden" class="spb_param_value pb_border_bottom dropdown " name="pb_border_bottom" value="no"><input type="hidden" class="spb_param_value el_class textfield " name="el_class" value=""></div> <!-- end .spb_element_wrapper --></div><div class="widgets-placeholder" style="height: 73px; width: 615px;"></div> <!-- end #element-spb_text_block --></div>');
			} else {
				tabs_nav.append('<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tab-'+tabs_count+'" aria-labelledby="ui-id-'+tabs_count+'" aria-selected="false" style="top: 0px;"><a href="#tab-'+tabs_count+'" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-'+tabs_count+'"><span>'+tab_title+' '+tabs_count+'</span></a><a class="edit_tab"></a><a class="delete_tab"></a></li>');
				tabs_asset.append('<div id="tab-'+tabs_count+'" class="row-fluid spb_column_container spb_sortable_container not-column-inherit ui-tabs-panel ui-widget-content ui-corner-bottom ui-sortable ui-droppable" aria-labelledby="ui-id-'+tabs_count+'" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;"><div data-element_type="spb_text_block" class="spb_text_block spb_content_element spb_sortable span12 spb_first spb_last"><input type="hidden" class="spb_sc_base" name="element_name-spb_text_block" value="spb_text_block"><div class="controls sidebar-name"> <div class="column_size_wrapper"> <a class="column_decrease" href="#" title="Decrease width"></a> <span class="column_size">1/1</span> <a class="column_increase" href="#" title="Increase width"></a> </div><div class="controls_right"> <a class="column_popup" href="#" title="Pop up"></a> <a class="column_edit" href="#" title="Edit"></a> <a class="column_clone" href="#" title="Clone"></a> <a class="column_delete" href="#" title="Delete"></a></div></div><div class="spb_element_wrapper clearfix"><input type="hidden" class="spb_param_value title textfield " name="title" value=""><input type="hidden" class="spb_param_value icon textfield " name="icon" value=""><div class="spb_param_value content textarea_html " name="content"><p> This is a text block. Click the edit button to change this text. </p></div><input type="hidden" class="spb_param_value pb_margin_bottom dropdown " name="pb_margin_bottom" value="no"><input type="hidden" class="spb_param_value pb_border_bottom dropdown " name="pb_border_bottom" value="no"><input type="hidden" class="spb_param_value el_class textfield " name="el_class" value=""></div> <!-- end .spb_element_wrapper --></div> <!-- end #element-spb_text_block --></div>');
			}
			
			$tabs.tabs('refresh');
			//tab_counter++;
			//
			setTabButtons();
			
			initDroppable();
			save_spb_html();
		});

		function setTabButtons() {
			jQuery('.ui-tabs-nav .edit_tab').click( function() {
				var tab_name = jQuery(this).parent().find('span').text();
				var tab_title = prompt("Please enter new tab title", tab_name);
				if ( tab_title != null && tab_title != "" ) {
					jQuery(this).parent().find('span').text(tab_title);
					//
					save_spb_html();
				}	
			return false;
			});
			
			jQuery('.ui-tabs-nav .delete_tab').click( function() {
				var tab_name = jQuery(this).parent().text(),
					tab_pos = jQuery(this).closest('li').index(),
					alt_tab_pos = tab_pos + 1;;
								
				var answer = confirm ("If you'd like to delete the '"+tab_name+"' tab, press OK. If you want to return, press Cancel.");
				if ( answer ) {
					$tabs.find('.ui-tabs-nav li:eq('+tab_pos+')').remove();
					if ($tabs.closest('.spb_sortable').hasClass('spb_tour')) {
					$tabs.find('#tab-'+alt_tab_pos).remove();
					$tabs.find('#tab-slide-'+alt_tab_pos).remove();
					} else {
					$tabs.find('#tab-'+alt_tab_pos).remove();
					$tabs.find('#tab-tab-'+alt_tab_pos).remove();
					}
					//
					$tabs.tabs('refresh');
					save_spb_html();
				}
				return false;
			});
		}
		
		setTabButtons();

	});

	initDroppable();
}

function spbTabsGenerateShortcodeCallBack(current_top_level, inner_element_count) {
	var tab_title = current_top_level.find(".ui-tabs-nav li:eq("+inner_element_count+") a").text();
	output = '[spb_tab title="'+tab_title+'"] %inner_shortcodes [/spb_tab]';
	return output;
}

/* Accordion Callback
---------------------------------------------------------- */
function spbAccordionInitCallBack(element) {
	element.find('.spb_accordion_holder').not('.spb_initialized').each(function(index) {
		jQuery(this).addClass('spb_initialized');
		//var tab_counter = 4;
		//
		var $tabs,
			add_btn = jQuery(this).closest('.spb_element_wrapper').find('.add_tab');
		//
		
		$tabs = jQuery(this).accordion({
			header: "> div > h3",
			autoHeight: true,
			heightStyle: "content"
		})
		.sortable({
			axis: "y",
			handle: "h3",
			stop: function( event, ui ) {
				// IE doesn't register the blur when sorting
				// so trigger focusout handlers to remove .ui-state-focus
				ui.item.children( "h3" ).triggerHandler( "focusout" );
				//
				save_spb_html();
			}
		});
		
		setAccordionButtons();

		add_btn.click( function(e) {
			e.preventDefault();
			var tab_title = 'Section',
				section_template = '<div class="group"><h3><a class="title-text" href="#">Section</a><a class="delete_tab"></a><a class="edit_tab"></a></h3><div class="row-fluid spb_column_container spb_sortable_container not-column-inherit"></div></div>';
			$tabs.append(section_template);
			$tabs.accordion( "destroy" )
			.accordion({
				header: "> div > h3",
				autoHeight: true,
				heightStyle: "content"
			})
			.sortable({
				axis: "y",
				handle: "h3",
				stop: function( event, ui ) {
					// IE doesn't register the blur when sorting
					// so trigger focusout handlers to remove .ui-state-focus
					ui.item.children( "h3" ).triggerHandler( "focusout" );
					//
					save_spb_html();
				}
			});

			//$tabs.tabs( "add", "#tabs-" + tabs_count, tab_title );
			//tab_counter++;
			//
			setAccordionButtons();
			initDroppable();
			save_spb_html();
		});

		function setAccordionButtons() {
			jQuery('.spb_accordion_holder .delete_tab').click( function(e) {
				e.preventDefault();
	
				var tab_name = jQuery(this).parent().text();
				
				var answer = confirm ("If you'd like to delete the '"+tab_name+"' section, press OK. If you want to return, press Cancel.");
				if ( answer ) {
					jQuery(this).parent().closest('.group').remove();
					//
					save_spb_html();
				}
			});
			
			jQuery('.spb_accordion_holder .edit_tab').click( function() {
				var tab_name = jQuery(this).parent().find('.title-text').text();
				var tab_title = prompt("Please enter new section title", tab_name);
				if ( tab_title != null && tab_title != "" ) {
					jQuery(this).parent().find('.title-text').text(tab_title);
					//
					save_spb_html();
				}
				return false;
			});
		}
	});
	initDroppable();
}

function spbAccordionGenerateShortcodeCallBack(current_top_level, inner_element_count) {
	var tab_title = current_top_level.find(".group:eq("+inner_element_count+") > h3").text();
	output = '[spb_accordion_tab title="'+tab_title+'"] %inner_shortcodes [/spb_accordion_tab]';
	return output;
}

/* Message box Callbacks
---------------------------------------------------------- */
function spbMessageInitCallBack(element) {
	var el = element.find('.spb_param_value.color');
	var class_to_set = el.val();
	el.closest('.spb_element_wrapper').addClass(class_to_set);
}

/* Text Separator Callbacks
---------------------------------------------------------- */
function spbTextSeparatorInitCallBack(element) {
	var el = element.find('.spb_param_value.title_align');
	var class_to_set = el.val();
	el.closest('.spb_element_wrapper').addClass(class_to_set);
}

/* Call to action Callbacks
---------------------------------------------------------- */
function spbCallToActionInitCallBack(element) {
	var el = element.find('.spb_param_value.position');
	var class_to_set = el.val();
	el.closest('.spb_element_wrapper').addClass(class_to_set);
}
function spbCallToActionSaveCallBack(element) {
	var el_class = element.find('.spb_param_value.color').val() + " " + element.find('.spb_param_value.icon').val();
	//
	element.find('.spb_element_wrapper').removeClass(el_class);
}

/* Button Callbacks
---------------------------------------------------------- */
function spbButtonInitCallBack(element) {
	var el_class = element.find('.spb_param_value.color').val() + ' ' + element.find('.spb_param_value.size').val() + ' ' + element.find('.spb_param_value.icon').val();
	//
	element.find('button.title').attr({ "class" : "spb_param_value title textfield btn " + el_class });

	var icon = element.find('.spb_param_value.icon').val();
	if ( icon != 'none' && element.find('button i.icon').length == 0  ) {
		element.find('button.title').append(' <i class="icon"></i>');
	}
}

function spbButtonSaveCallBack(element) {
	var el_class = element.find('.spb_param_value.color').val() + ' ' + element.find('.spb_param_value.size').val() + ' ' + element.find('.spb_param_value.icon').val();
	//
	element.find('.spb_element_wrapper').removeClass(el_class);
	element.find('button.title').attr({ "class" : "spb_param_value title textfield btn " + el_class });

	var icon = element.find('.spb_param_value.icon').val();
	if ( icon != 'none' && element.find('button i.icon').length == 0 ) {
		element.find('button.title').append(' <i class="icon"></i>');
	} else {
		element.find('button.title i.icon').remove();
	}
}