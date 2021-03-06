<?PHP
if(!defined("HYN")) { exit; }

DOM::set_css( __DIR__ . DS . "lib" . DS . "docs" . DS . "assets" . DS . "css" . DS . "bootstrap.css" );

DOM::set_css( __DIR__ . DS . "overrideimg.css" );

DOM::set_css( "hyn.css" );

DOM::set_js( "https://www.google.com/jsapi" );
DOM::set_js( HYN_PATH_PUBLIC_JS . "libs" . DS . "jq". DS . "jq.js" );

#DOM::set_js( HYN_PATH_PUBLIC_JS . "libs" . DS . "jquery". DS . "lib" . DS . "dist" . DS . "jquery.js" );

DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-alert.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-button.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-carousel.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-collapse.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-dropdown.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-modal.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-tooltip.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-popover.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-scrollspy.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-tab.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-transition.js" );
DOM::set_js( __DIR__ . DS . "lib" . DS . "js" . DS . "bootstrap-typeahead.js" );
/**
*	Resizes elements to it's direct parent
*	@var 	.height-to-parent
*/
DOM::add_js( '
	if( jQuery(".height-to-parent").length > 0 ) {
		jQuery(".height-to-parent").each( function() {
			jQuery( this ).height( jQuery(this).parent().height() );
		});
	}
', "body" );

/**
*	Create button from all button classed elements
*	@var 	.button
*/
DOM::add_js( '
	if( jQuery(".button").length > 0 ) {
		jQuery(".button").button();
	}
', "body" );

/**
*	Toggle close any element with data-box-minify
*	@var	[data-box-minify]
*/
DOM::add_js( '
	if(jQuery("[data-box-minify]").length > 0) {
		jQuery("[data-box-minify]").click(function() {
//			alert(jQuery(this).attr("data-box-minify"));
			var elm					= jQuery("#"+jQuery(this).attr("data-box-minify"));
			
			// hiding
			if( jQuery(elm).find(".body").is(":visible") ) {
				jQuery(this).find("i").attr("class","icon-resize-full");
			}
			// otherwise showing
			else {
				jQuery(this).find("i").attr("class","icon-resize-small");
			}
			jQuery( elm ).find(".body").toggle();
			
			return false;
		});
	}' 
	, "body" );

/**
*	Show tooltips
*	@var	[data-original-title]
*/
DOM::add_js( 'if(jQuery("[data-original-title]:not([data-content])").length > 0) { jQuery("[data-original-title]:not([data-content])").tooltip({ live: true, html: true });}' , "body" );

/**
*	Popover; extended hover with html possibility
*	@var	[data-popover-placement]
*/
DOM::add_js( '	if(jQuery("[data-popover-placement]").length > 0) {
			jQuery("[data-popover-placement]").popover({ 
				live: true, 
				html: true, 
				placement: function(pop,elm) {
					return jQuery(elm).attr("data-popover-placement");
				}
			});
		}' , "body" );

/**
*	Carousel
*	@var	.carousel
*/
DOM::add_js( 'if(jQuery(".carousel .item").length > 0) {jQuery(".carousel").carousel();}' , "body" );