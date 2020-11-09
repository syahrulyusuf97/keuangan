// AJax setup header
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
///////////////////////////////////////////////////////////////////////////
$.AdminLTE = {};
$.AdminLTE.tree = function (menu) {
    var _this = this;
    var animationSpeed = $.AdminLTE.options.animationSpeed;
    $(document).off('click', menu + ' li a')
      .on('click', menu + ' li a', function (e) {
        //Get the clicked link and the next element
        var $this = $(this);
        var checkElement = $this.next();

        //Check if the next element is a menu and is visible
        if ((checkElement.is('.treeview-menu')) && (checkElement.is(':visible')) && (!$('body').hasClass('sidebar-collapse'))) {
          //Close the menu
          checkElement.slideUp(animationSpeed, function () {
            checkElement.removeClass('menu-open');
            //Fix the layout in case the sidebar stretches over the height of the window
            //_this.layout.fix();
          });
          checkElement.parent("li").removeClass("active");
        }
        //If the menu is not visible
        else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
          //Get the parent menu
          var parent = $this.parents('ul').first();
          //Close all open menus within the parent
          var ul = parent.find('ul:visible').slideUp(animationSpeed);
          //Remove the menu-open class from the parent
          ul.removeClass('menu-open');
          //Get the parent li
          var parent_li = $this.parent("li");

          //Open the target menu and add the menu-open class
          checkElement.slideDown(animationSpeed, function () {
            //Add the class active to the parent li
            checkElement.addClass('menu-open');
            parent.find('li.active').removeClass('active');
            parent_li.addClass('active');
            //Fix the layout in case the sidebar stretches over the height of the window
            _this.layout.fix();
          });
        }
        //if this isn't a link, prevent the page from being redirected
        if (checkElement.is('.treeview-menu')) {
          e.preventDefault();
        }
      });
  };

  /* Tree()
 * ======
 * Converts a nested list into a multilevel
 * tree view menu.
 *
 * @Usage: $('.my-menu').tree(options)
 *         or add [data-widget="tree"] to the ul element
 *         Pass any option as data-option="value"
 */
+function ($) {
  'use strict'

  var DataKey = 'lte.tree'

  var Default = {
    animationSpeed: 500,
    accordion     : true,
    followLink    : false,
    trigger       : '.treeview a'
  }

  var Selector = {
    tree        : '.tree',
    treeview    : '.treeview',
    treeviewMenu: '.treeview-menu',
    open        : '.menu-open, .active',
    li          : 'li',
    data        : '[data-widget="tree"]',
    active      : '.active'
  }

  var ClassName = {
    open: 'menu-open',
    tree: 'tree'
  }

  var Event = {
    collapsed: 'collapsed.tree',
    expanded : 'expanded.tree'
  }

  // Tree Class Definition
  // =====================
  var Tree = function (element, options) {
    this.element = element
    this.options = options

    $(this.element).addClass(ClassName.tree)

    $(Selector.treeview + Selector.active, this.element).addClass(ClassName.open)

    this._setUpListeners()
  }

  Tree.prototype.toggle = function (link, event) {
    var treeviewMenu = link.next(Selector.treeviewMenu)
    var parentLi     = link.parent()
    var isOpen       = parentLi.hasClass(ClassName.open)

    if (!parentLi.is(Selector.treeview)) {
      return
    }

    if (!this.options.followLink || link.attr('href') == '#') {
      event.preventDefault()
    }

    if (isOpen) {
      this.collapse(treeviewMenu, parentLi)
    } else {
      this.expand(treeviewMenu, parentLi)
    }
  }

  Tree.prototype.expand = function (tree, parent) {
    var expandedEvent = $.Event(Event.expanded)

    if (this.options.accordion) {
      var openMenuLi = parent.siblings(Selector.open)
      var openTree   = openMenuLi.children(Selector.treeviewMenu)
      this.collapse(openTree, openMenuLi)
    }

    parent.addClass(ClassName.open)
    tree.slideDown(this.options.animationSpeed, function () {
      $(this.element).trigger(expandedEvent)
    }.bind(this))
  }

  Tree.prototype.collapse = function (tree, parentLi) {
    var collapsedEvent = $.Event(Event.collapsed)

    tree.find(Selector.open).removeClass(ClassName.open)
    parentLi.removeClass(ClassName.open)
    tree.slideUp(this.options.animationSpeed, function () {
      tree.find(Selector.open + ' > ' + Selector.treeview).slideUp()
      $(this.element).trigger(collapsedEvent)
    }.bind(this))
  }

  // Private

  Tree.prototype._setUpListeners = function () {
    var that = this

    $(this.element).on('click', this.options.trigger, function (event) {
      that.toggle($(this), event)
    })
  }

  // Plugin Definition
  // =================
  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data(DataKey)

      if (!data) {
        var options = $.extend({}, Default, $this.data(), typeof option == 'object' && option)
        $this.data(DataKey, new Tree($this, options))
      }
    })
  }

  var old = $.fn.tree

  $.fn.tree             = Plugin
  $.fn.tree.Constructor = Tree

  // No Conflict Mode
  // ================
  $.fn.tree.noConflict = function () {
    $.fn.tree = old
    return this
  }

  // Tree Data API
  // =============
  $(window).on('load', function () {
    $(Selector.data).each(function () {
      Plugin.call($(this))
    })
  })

}(jQuery)
// Loader
$(document).ready(function () {
    setTimeout(() => {
        $("#loader").fadeToggle(250);
    }, 800); // hide delay when page load
});

$(".page-redirect").click(function(){
    $("#loader").fadeToggle(250);
})

$(".loading-submit").click(function(){
    $(".loading").fadeIn(200);
})

$(document).ajaxSend(function(){
    // show loading
    $(".loading").fadeIn(200);
});

$(document).ajaxComplete(function(){
    // close loading
    $(".loading").fadeOut(200);
});

function hasWhiteSpace(text) {
    return /\s/g.test(text);
}

// Message
function errorMessage(title, message) {
    $("#error_message .modal-title").html(title);
    $("#error_message .modal-body").html(message);
    $("#error_message").modal('show');
}

function warningMessage(title, message, url) {
    $("#warning_message .modal-title").html(title);
    $("#warning_message .modal-body").html(message);
    $("#warning_message .url").attr('href', url);
    $("#warning_message").modal('show');
}

function confirmMessage(title, message, id, url) {
    $("#confirm_message .modal-title").html(title);
    $("#confirm_message .modal-body").html(message);
    $("#confirm_id").val(id);
    $("#confirm_url").val(url);
    $("#confirm_message").modal('show');
}

function infoMessage(title, message) {
    $("#info_message .modal-title").html(title);
    $("#info_message .modal-body").html(message);
    $("#info_message").modal('show');
}

function successMessage(title, message) {
    $("#success_message .modal-title").html(title);
    $("#success_message .modal-body").html(message);
    $("#success_message").modal('show');
}
// End Message

// Confirmed
function confirmed() {
    postData($("#confirm_url").val(), $("#form_confirm").serialize()).done(function(response){
        if (response.status == "success") {table.api().ajax.reload();if(response.data.saldo != null){$(".total-saldo").text(response.data.saldo);}}
    });
}

// Post Data
function postData(url, data, idmodal="", Form_Data=false) {
    if (Form_Data) {
        return $.ajax({
            url : url,
            type: "post",
            data: new FormData(data),
            processData: false,
            contentType: false,
            cache: false,
            success: function(response){
                if (response.status == "success") {
                    if (idmodal != "") {$(idmodal).modal('hide')}
                    successMessage('Sukses', response.message);
                } else if (response.status == "failed") {
                    if (idmodal != "") {$(idmodal).modal('hide')}
                    infoMessage('Gagal', response.message)
                }
            },
            error: function(xhr, status, errorThrown) {
                // console.log(xhr);
                // console.log(status);
                // console.log(errorThrown);
                if (idmodal != "") {$(idmodal).modal('hide')}
                errorMessage('Error', '#'+xhr.status+' - '+xhr.statusText);
            }
        });
    } else {
        return $.ajax({
            url : url,
            type: "post",
            data: data,
            dataType: "json",
            success: function(response){
                if (response.status == "success") {
                    if (idmodal != "") {$(idmodal).modal('hide')}
                    successMessage('Sukses', response.message);
                } else if (response.status == "failed") {
                  if (idmodal != "") {$(idmodal).modal('hide')}
                    infoMessage('Gagal', response.message)
                }
            },
            error: function(xhr, status, errorThrown) {
                // console.log(xhr);
                // console.log(status);
                // console.log(errorThrown);
                if (idmodal != "") {$(idmodal).modal('hide')}
                errorMessage('Error', '#'+xhr.status+' - '+xhr.statusText);
            }
        });
    }
}
// End Post Data

function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
  return true;
}

function number_format(number, decimals, dec_point, thousands_sep) {
  // http://kevin.vanzonneveld.net
  // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +     bugfix by: Michael White (http://getsprink.com)
  // +     bugfix by: Benjamin Lupton
  // +     bugfix by: Allan Jensen (http://www.winternet.no)
  // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // +     bugfix by: Howard Yeend
  // +    revised by: Luke Smith (http://lucassmith.name)
  // +     bugfix by: Diogo Resende
  // +     bugfix by: Rival
  // +      input by: Kheang Hok Chin (http://www.distantia.ca/)
  // +   improved by: davook
  // +   improved by: Brett Zamir (http://brett-zamir.me)
  // +      input by: Jay Klehr
  // +   improved by: Brett Zamir (http://brett-zamir.me)
  // +      input by: Amir Habibi (http://www.residence-mixte.com/)
  // +     bugfix by: Brett Zamir (http://brett-zamir.me)
  // +   improved by: Theriault
  // +   improved by: Drew Noakes
  // *     example 1: number_format(1234.56);
  // *     returns 1: '1,235'
  // *     example 2: number_format(1234.56, 2, ',', ' ');
  // *     returns 2: '1 234,56'
  // *     example 3: number_format(1234.5678, 2, '.', '');
  // *     returns 3: '1234.57'
  // *     example 4: number_format(67, 2, ',', '.');
  // *     returns 4: '67,00'
  // *     example 5: number_format(1000);
  // *     returns 5: '1,000'
  // *     example 6: number_format(67.311, 2);
  // *     returns 6: '67.31'
  // *     example 7: number_format(1000.55, 1);
  // *     returns 7: '1,000.6'
  // *     example 8: number_format(67000, 5, ',', '.');
  // *     returns 8: '67.000,00000'
  // *     example 9: number_format(0.9, 0);
  // *     returns 9: '1'
  // *    example 10: number_format('1.20', 2);
  // *    returns 10: '1.20'
  // *    example 11: number_format('1.20', 4);
  // *    returns 11: '1.2000'
  // *    example 12: number_format('1.2000', 3);
  // *    returns 12: '1.200'
  var n = !isFinite(+number) ? 0 : +number, 
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      toFixedFix = function (n, prec) {
          // Fix for IE parseFloat(0.55).toFixed(0) = 0;
          var k = Math.pow(10, prec);
          return Math.round(n * k) / k;
      },
      s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
  if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

function month(string)
{
  var bulan = [];
  bulan["Januari"]    = "01";
  bulan["Februari"]   = "02";
  bulan["Maret"]      = "03";
  bulan["April"]      = "04";
  bulan["Mei"]        = "05";
  bulan["Juni"]       = "06";
  bulan["Juli"]       = "07";
  bulan["Agustus"]    = "08";
  bulan["September"]  = "09";
  bulan["Oktober"]    = "10";
  bulan["November"]   = "11";
  bulan["Desember"]   = "12";

  return bulan[string];
}

function getFormattedDate(data) {
// var date = new Date(data);
// var year = date.getFullYear();

// var month = (1 + date.getMonth()).toString();
// month = month.length > 1 ? month : '0' + month;

// var day = date.getDate().toString();
// day = day.length > 1 ? day : '0' + day;
// return year + '-' + month + '-' + day;
var date_split = data.split(" ");
return date_split[2]+'-'+month(date_split[1])+'-'+date_split[0];
}

function getFormattedMonth(data) {
// var date = new Date(Date.parse(data));
//   var year = date.getFullYear();

//   var month = (1 + date.getMonth()).toString();
//   month = month.length > 1 ? month : '0' + month;

//   return year + '-' + month;
var date_split = data.split(" ");
return month(date_split[0])+'-'+date_split[1];
}

function dateFormat(data, format="d-m-Y") {
var date = new Date(data);

var monthNames = [
    "Januari", "Februari", "Maret",
    "April", "Mei", "Juni", "Juli",
    "Agustus", "September", "Oktober",
    "November", "Desember"
  ];

var year = date.getFullYear();

var monthIndex = date.getMonth();

var month = (1 + date.getMonth()).toString();
month = month.length > 1 ? month : '0' + month;

var day = date.getDate().toString();
day = day.length > 1 ? day : '0' + day;

if (format == "d-m-Y") {
  return day+'-'+month+'-'+year;
} else if (format == "Y-m-d") {
  return year + '-' + month + '-' + day;
} else if (format == "d M Y") {
  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}
}

function formatRupiah(angka, prefix)
{
var number_string = angka.replace(/[^,\d]/g, '').toString(),
  split = number_string.split(','),
  sisa  = split[0].length % 3,
  rupiah  = split[0].substr(0, sisa),
  ribuan  = split[0].substr(sisa).match(/\d{3}/gi);
  
if (ribuan) {
  separator = sisa ? '.' : '';
  rupiah += separator + ribuan.join('.');
}

rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
return prefix == undefined ? rupiah : (rupiah ? 'Rp' + rupiah : '');
}

function rupiah(angka, prefix)
{
  var number_string = angka.toString(),
      split = number_string.split(','),
      sisa  = split[0].length % 3,
      rupiah  = split[0].substr(0, sisa),
      ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

  if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  return prefix == undefined ? rupiah : (rupiah ? 'Rp' + rupiah : '');
}

function toRupiah(angka) {
  parseInt(angka);
  var rupiah = '';
  var angkarev = angka.toString().split('').reverse().join('');
  for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
  var hasil = rupiah.split('',rupiah.length-1).reverse().join('');
  return hasil;
}

$(".nominal").keyup(function(e){
    $(".nominal").val(formatRupiah($(this).val(), 'Rp'));
})


///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Go Back
$(".goBack").click(function () {
    window.history.back();
});
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Input
$(".clear-input").click(function () {
    $(this).parent(".input-wrapper").find(".form-control").focus();
    $(this).parent(".input-wrapper").find(".form-control").val("");
    $(this).parent(".input-wrapper").removeClass("not-empty");
});
// active
$(".form-group .form-control").focus(function () {
    $(this).parent(".input-wrapper").addClass("active");
}).blur(function () {
    $(this).parent(".input-wrapper").removeClass("active");
})
// empty check
$(".form-group .form-control").keyup(function () {
    var inputCheck = $(this).val().length;
    if (inputCheck > 0) {
        $(this).parent(".input-wrapper").addClass("not-empty");
    }
    else {
        $(this).parent(".input-wrapper").removeClass("not-empty");
    }
});
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Searchbox Toggle
$(".toggle-searchbox").click(function () {
    $("#search").fadeToggle(200);
    $("#search .form-control").focus();
});
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Owl Carousel
$('.carousel-full').owlCarousel({
    loop:true,
    margin:8,
    nav:false,
    items: 1,
    dots: false,
});
$('.carousel-single').owlCarousel({
    stagePadding: 30,
    loop:true,
    margin:16,
    nav:false,
    items: 1,
    dots: false,
});
$('.carousel-multiple').owlCarousel({
    stagePadding: 32,
    loop:true,
    margin:16,
    nav:false,
    items: 2,
    dots: false,
});
$('.carousel-small').owlCarousel({
    stagePadding: 32,
    loop:true,
    margin:8,
    nav:false,
    items: 4,
    dots: false,
});
$('.carousel-slider').owlCarousel({
    loop:true,
    margin:8,
    nav:false,
    items: 1,
    dots: true,
});
///////////////////////////////////////////////////////////////////////////
