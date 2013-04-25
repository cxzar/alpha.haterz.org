/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function($){

	var config = false;

	$(document).ready(function() {

		config = config  || $('body').data('config');
		
		// Accordion menu
		$('.menu-sidebar').accordionMenu({ mode:'slide', onaction: function(){ $(window).resize(); }});

		// Dropdown menu
		$('#menu').dropdownMenu({ mode: 'fade', duration: 500, dropdownSelector: 'div.dropdown' });

		// Smoothscroller
		$('a[href="#page"]').smoothScroller({ duration: 500 });

		// Social buttons
		$('article[data-permalink]').socialButtons(config);


		// Fancy active link effect
		if (config["menu-follower"]) {
			
			var follower       = $("#menu").append('<div id="menu-follower"></div>').find("#menu-follower"),
				activelink     = $("#menu").find("li.level1.active");

			$("#menu").on({
				"menu:enter": function(e, item, index){	
					follower.show().stop().animate({"top": item.position().top + 10});

					var dd = item.find(".dropdown");

					if(dd.length){

						dd.css("margin-top", 0);

						var ddtop    = item.offset().top - $(window).scrollTop(),
							ddheight = dd.height() ? dd.height() : parseFloat(dd.children().eq(0).css("height"));

						if(ddtop+ddheight > $(window).height()){
							dd.css("margin-top", $(window).height() -(ddtop+ddheight));
						}
					}
				},
				"menu:leave": function(e, item, index){
					
					if(e.relatedTarget==follower.get(0)) return;

					if(activelink.length){
						follower.stop().animate({"top": activelink.position().top + 10});
					}else{
						follower.hide();
					}
				}
			});


			if(activelink.length){
				follower.show().animate({"top": activelink.position().top + 10});
			}

			// Remove fixed sidebar-a if sidebar height > viewport		
			if($("#side-container").height() > $(window).height()){
				$("#page").removeClass("sidebar-a-fixed");
				$("#sidebar-a .module > div").removeClass("sidebar-a-bottom-fixed");
			}

		}
	});

	$.onMediaQuery('(min-width: 960px)', {
		init: function() {
			if (!this.supported) this.matches = true;
		},
		valid: function() {
			$.matchWidth('grid-block', '.grid-block', '.grid-h').match();

			$.matchHeight('top-a', '#top-a .grid-h', '.deepest').match();
			$.matchHeight('top-b', '#top-b .grid-h', '.deepest').match();
			$.matchHeight('bottom-a', '#bottom-a .grid-h', '.deepest').match();
			$.matchHeight('bottom-b', '#bottom-b .grid-h', '.deepest').match();
			$.matchHeight('innertop', '#innertop .grid-h', '.deepest').match();
			$.matchHeight('innerbottom', '#innerbottom .grid-h', '.deepest').match();
		},
		invalid: function() {
			$.matchWidth('grid-block').remove();
			$.matchHeight('top-a').remove();
			$.matchHeight('top-b').remove();
			$.matchHeight('bottom-a').remove();
			$.matchHeight('bottom-b').remove();
			$.matchHeight('innertop').remove();
			$.matchHeight('innerbottom').remove();
		}
	});

	$.onMediaQuery('(min-width: 768px)', {
		init: function() {
			if (!this.supported) this.matches = true;
		},
		valid: function() {

			if(this.supported) {

				// Set #side-container width for fixed sidebar
				$(window).bind("resize.sidebar", (function(){
						
						var $w   = $(window),
							$bar = $("#side-container"),
							$barFixed = $(".sidebar-a-bottom-fixed"),
							fn   = function(){
								
								var winwidth = $w.width();

								$bar.css("width", "");
								$barFixed.css("width", "");

								if (winwidth < config.template_width) {
									$bar.css("width", parseInt(winwidth / 100 * config.block_side_width) - 20);
									$barFixed.css("width", parseInt(winwidth / 100 * config.block_side_width) - 40);
								}
							};

						fn();

						return fn;

				})());
			}


			$.onMediaQuery.spyclockside = true;

			setInterval((function(){

				var ret = function() {
					if(!$.onMediaQuery.spyclockside) return;

					$("#block-side, #block-main").css('min-height', "");
					$("#block-side, #block-main").css('min-height', $(window).height());

					if($("#block-side").height() != $("#block-main").height()){
						$.matchHeight('main', '#block-side, #block-main').match();
					}
				}

				ret();

				return ret;

			})(), 1000);

		},
		invalid: function() {
			$("#block-side, #block-main").css('min-height', "");
			$.matchHeight('main').remove();

			// Remove #side-container width for phones 
			$("#side-container").css("width", "");
			$(window).unbind("resize.sidebar");

			$.onMediaQuery.spyclockside = false;
		}
	});

	var pairs  = [],
		pairs2 = [];

	$.onMediaQuery('(min-width: 480px) and (max-width: 959px)', {
		valid: function() {
			pairs = [];
			$.each(['#sidebar-b > .grid-box', '#top-a .grid-h', '#top-b .grid-h', '#bottom-a .grid-h', '#bottom-b .grid-h', '#innertop .grid-h', '#innerbottom .grid-h'], function(i, selector) {
				for (var i = 0, elms = $(selector), len = parseInt(elms.length / 2); i < len; i++) {
					var id = 'pair-' + pairs.length;
					$.matchHeight(id, [elms.get(i * 2), elms.get(i * 2 + 1)], '.deepest').match();
					pairs.push(id);
				}
			});
		},
		invalid: function() {
			$.each(pairs, function() { $.matchHeight(this).remove(); });
		}
	});

	$.onMediaQuery('(min-width: 480px) and (max-width: 767px)', {
		valid: function() {
			pairs2 = [];
			$.each(['#sidebar-a > .grid-box'], function(i, selector) {
				for (var i = 0, elms = $(selector), len = parseInt(elms.length / 2); i < len; i++) {
					var id = 'pair2-' + pairs2.length;
					$.matchHeight(id, [elms.get(i * 2), elms.get(i * 2 + 1)], '.deepest').match();
					pairs2.push(id);
				}
			});
		},
		invalid: function() {
			$.each(pairs2, function() { $.matchHeight(this).remove(); });
		}
	});

	$.onMediaQuery('(max-width: 767px)', {
		valid: function() {
			var header = $('#header-responsive');
			if (!header.length) {
				header = $('<div id="header-responsive"/>').prependTo('#header');
				$('#logo').clone().removeAttr('id').addClass('logo').appendTo(header);
				$('.searchbox').first().clone().removeAttr('id').appendTo(header);
				$('#menu').responsiveMenu().next().addClass('menu-responsive').appendTo(header);
			}
		}
	});
})(jQuery);