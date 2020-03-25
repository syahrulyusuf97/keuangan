if (typeof jQuery === "undefined") {
	throw new Error("This template requires jQuery");
}

$.AdminLTE = {};

$.AdminLTE.options = {
	navbarMenuSlimscroll: true,
	navbarMenuSlimscrollWidth: "3px",
	navbarMenuHeight: "200px",
	animationSpeed: 500,
	sidebarToggleSelector: "[data-toggle='offcanvas']",
	sidebarPushMenu: true,
	sidebarSlimScroll: true,
	sidebarExpandOnHover: false,
	enableBoxRefresh: true,
	enableFastclick: false,
	enableControlTreeView: true,
	enableControlSidebar: true,
	controlSidebarOptions: {
		toggleBtnSelector: "[data-toggle='control-sidebar']",
		selector: ".control-sidebar",
		slide: true
	},
	colors: {
		lightBlue: "#3c8dbc",
		red: "#f56954",
		green: "#00a65a",
		aqua: "#00c0ef",
		yellow: "#f39c12",
		blue: "#0073b7",
		navy: "#001F3F",
		teal: "#39CCCC",
		olive: "#3D9970",
		lime: "#01FF70",
		orange: "#FF851B",
		fuchsia: "#F012BE",
		purple: "#8E24AA",
		maroon: "#D81B60",
		black: "#222222",
		gray: "#d2d6de"
	},
	screenSizes: {
		xs: 480,
		sm: 768,
		ms: 992,
		lg: 1200
	}
};

$(function(){
	"use strict";

	$("body").removeClass("hold-transition");

	if (typeof AdminLTEOptions !== "undefined") {
		$.extend(true, $.AdminLTE.options, AdminLTEOptions);
	}

	var o = $.AdminLTE.options;

	_init();

	$.AdminLTE.layout.activate();

	if (o.enableControlTreeView) {
		$.AdminLTE.tree('.sidebar');
	}

	if (o.enableControlSidebar) {
		$.AdminLTE.controlSidebar.activate();
	}

	// if (o.sidebarPushMenu) {
	// 	$.AdminLTE.pushMenu.activate(o.sidebarToggleSelector);
	// }

	if (o.enableFastClick && typeof FastClick != 'undefined') {
		FastClick.attach(document.body);
	}

	$('.btn-group[date-toggle="btn-toggle"]').each(function(){
		var group = $(this);
		$(this).find(".btn").on('click', function(e){
			group.find(".btn.active").removeClass("active");
			$(this).addClass("active");
			e.preventDefault();
		});
	});
});

function _init() {
	'use strict';

	$.AdminLTE.layout = {
		activate: function() {
			var _this = this;
			_this.fix();
			_this.fixSidebar();
			$('body, html, .wrapper').css('height', 'auto');
			$(window, ".wrapper").resize(function(){
				_this.fix();
				_this.fixSidebar();
			});
		},
		fix: function() {
			$(".layout-boxed > .wrapper").css('overflow', 'hidden');
			var footer_height = $('.main-footer').outerHeight() || 0;
			var neg = $('.main-header').outerHeight() + footer_height;
			var window_height = $(window).height();
			var sidebar_height = $('.sidebar').height() || 0;

			if ($("body").hasClass("fixed")) {
				$(".content-wrapper, .right-side").css('min-height', window_height - footer_height);
			} else {
				var postSetWidth;
				if (window_height >= sidebar_height) {
					$('.content-wrapper, .right-side').css('min-height', sidebar_height);
					postSetWidth = sidebar_height;
				}

				var controlSidebar = $($.AdminLTE.options.controlSidebarOptions.selector);
				if (typeof controlSidebar !== "undefined") {
					if (controlSidebar.height() > postSetWidth) {
						$(".content-wrapper, .right-side").css('min-height', controlSidebar.height());
					}
				}
			}
		},
		fixSidebar: function() {
			if (!$("body").hasClass("fixed")) {
				if (typeof $.fn.slimScroll != "undefined") {
					$(".sidebar").slimScroll({destroy: true}).height("auto");
				}
				return;
			} else if (typeof $.fn.slimScroll == "undefined" && window.console) {
				window.console.error("Error: the fixed layout requires the slimscroll plugin!");
			}

			if ($.AdminLTE.options.sidebarSlimScroll) {
				if (typeof $.fn.slimScroll != "undefined") {
					$(".sidebar").slimScroll({destroy: true}).height("auto");
					$(".sidebar").slimScroll({
						height: ($(window).height() - $(".main-header").height()) + "px",
						color: "rgba(0,0,0,0.2)",
						size: "3px"
					});
				}
			}
		}
	};

	$.AdminLTE.tree = function(menu) {
		var _this = this;
		var animationSpeed = $.AdminLTE.options.animationSpeed;
		$(document).off('click', menu + ' li a')
		.on('click', menu + ' li a', function(e){
			var $this = $(this);
			var checkElement = $this.next();

			if ((checkElement.is('.treeview-menu')) && (checkElement.is(':visible')) && (!$('body').hasClass('sidebar-collapse'))) {
				checkElement.slideUp(animationSpeed, function() {
					checkElement.removeClass('menu-open');
				});
				checkElement.parent("li").removeClass("active");
			} else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
				var parent = $this.parents('ul').first();
				var ul = parent.find('ul:visible').slideUp(animationSpeed);
				ul.removeClass('menu-open');
				var parent_li = $this.parent("li");

				checkElemet.slideDown(animationSpeed, function() {
					checkElement.addClass('menu-open');
					parent.find('li.active').removeClass('active');
					parent_li.addClass('active');
					_this.layout.fix();
				});
			}

			if (checkElement.is('.treeview-menu')) {
				e.preventDefault();
			}
		});
	};

	$.AdminLTE.controlSidebar = {
		activate: function() {
			var _this = this;
			var o = $.AdminLTE.options.controlSidebarOptions;
			var sidebar = $(o.selector);
			var btn = $(o.toggleBtnSelector);

			btn.on('click', function(e) {
				e.preventDefault();
				if (!sidebar.hasClass('control-sidebar-open')) {
					_this.open(sidebar, o.slide);
				} else {
					_this.close(sidebar, o.slide);
				}
			});

			var bg = $(".control-sidebar-bg");
			_this._fix(bg);

			if ($('body').hasClass('fixed')) {
				_this._fixForFixed(sidebar);
			} else {
				if ($(".content-wrapper, .right-side").height() < sidebar.height()) {
					_this._fixForContent(sidebar);
				}
			}
		},
		open: function(sidebar, slide) {
			if (slide) {
				sidebar.addClass('control-sidebar-open');
			} else {
				$('body').addClass('control-sidebar-open');
			}
		},
		close: function(sidebar, slide) {
			if (slide) {
				sidebar.removeClass('control-sidebar-open');
			} else {
				$('body').removeClass('control-sidebar-open');
			}
		},
		_fix: function(sidebar) {
			var _this = this;
			if ($('body').hasClass('layout-boxed')) {
				sidebar.css('position', 'absolute');
				sidebar.height($(".wrapper").height());

				if (_this.hasBindedResize) {return;}

				$(window).resize(function() {
					_this._fix(sidebar);
				});

				_this.hasBindedResize = true;
			} else {
				sidebar.css({
					'position': 'fixed',
					'height': 'auto'
				});
			}
		},
		_fixForFixed: function(sidebar) {
			sidebar.css({
				'position': 'fixed',
				'max-height': '100%',
				'overflow': 'auto',
				'padding-bottom': '50px'
			});
		},
		_fixForContent: function(sidebar) {
			$(".content-wrapper, .right-side").css('min-height', sidebar.height());
		}
	};
}(jQuery)