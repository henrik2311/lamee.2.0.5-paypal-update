$(document).ready(function(){
	
	/* _global */
	$(function(){ $('a').click(function(){ this.blur(); }); });
	$(function(){ $('a').focus(function(){ this.blur(); }); });
	$(function(){ $('select, input[type=text], input[type=password], textarea').addClass('form-control') });

    $(".cbimages").colorbox({rel:'cbimages', scalePhotos:true, maxWidth: "90%", maxHeight: "90%", fixed: true});
    $(".iframe").colorbox({iframe:true, width:"780", height:"560", maxWidth: "90%", maxHeight: "90%", fixed: true});

    /* _searchbox */
    $('#box_search input,#box_search select').on({
        focus: function(){
          $('#box_search').addClass( 'searchbox_active' ).find( '.btn-inverse' ).removeClass( 'btn-inverse' ).addClass( 'btn-primary' )
          $('#box_search select').on( 'change', function(){ $('#box_search .keywords').focus() } )

          .find( '.keywords' ).focus()
        },
        blur: function(){
          $('#box_search').removeClass( 'searchbox_active' ).find( '.btn-primary' ).removeClass( 'btn-primary' ).addClass( 'btn-inverse' )
        }
    });
  
	/* _global > main-navi */
	$('#topnavi').each(function(){
	  var menu        = $(this);
	  var isMobile    = ( $('.body_wrap').hasClass('device_mobile') || $('.body_wrap').hasClass('device_tablet') || $(document).width() <= 768 ) ? true : false;
	  var isTablet    = ( $('.body_wrap').hasClass('device_tablet') ) ? true : false;
	  var navtype     = ( menu.hasClass('navtype_mega') && !isMobile ) ? 'mega' : ( isMobile ) ? 'mobile' : 'dropdown';
	  var isDropdown  = ( menu.hasClass('navtype_none') ) ? false : true;
    
    if ( isDropdown ) {
      // all dropdown types
      menu.find('a.level_1.has_sub').append('<span class="icon-angle-down" />');
      
      /**** prepare for mobile ****/
      if ( isMobile  ) {
      	// var menuOpen = ( $('#topmenu').find('a.active').length && $.session.get('menu_state')=='open') ? true : false;
    	  var menuopen = ( $('.body_wrap').is("#page_index") && !isTablet )
      	var actives  = menu.find('a.active,a.active_parent');
      	
    	  if ( actives.length > 0 || menuopen == false ) {
    	    if ( $('.mobile_menu_toggle').length > 0 )
    	      $('#topmenu').hide()
    	    actives.next('ul').show()
    	    $('.mobile_menu_toggle').removeClass('btn-inverse').addClass('btn-primary').removeClass('active').children('.icon-chevron-up').removeClass('icon-chevron-up').addClass('icon-chevron-down')
    	  }
        
        $('.mobile_menu_toggle').on('click', function() {
          if ( $('#topmenu').is(':hidden') ) {
            $.session.set('menu_state', 'open');
            $('#topmenu:hidden').slideDown(250);
            $(this).removeClass('btn-primary').addClass('btn-inverse').addClass('active').children('.icon-chevron-down').removeClass('icon-chevron-down').addClass('icon-chevron-up');
          } else {
            $.session.set('menu_state', 'closed');
            $('#topmenu:visible').slideUp(250);
            $(this).removeClass('btn-inverse').addClass('btn-primary').removeClass('active').children('.icon-chevron-up').removeClass('icon-chevron-up').addClass('icon-chevron-down');
          }
        });
      }
      
      /**** type: mobile ****/
      if ( isMobile ) {
        
        /* add class "navtype_mobile" and remove previous declared navtype */
        menu.removeClass('navtype_mega').removeClass('navtype_dropdown').addClass('navtype_mobile');
        menu.find('ul.menulevel_3,ul.menulevel_4,ul.menulevel_5,ul.menulevel_6').hide();
        menu.find('a.level_2.has_sub').prepend('<span class="icon-angle-down pull-right" /> &nbsp;');
        menu.find('a.level_3,a.level_4,a.level_5,a.level_6').prepend('<span class="icon-angle-right" />');
    		
        var activate = menu.find('a.has_sub.active');
			  if ( activate.length ) {
			    activate.next('ul').show();
        }
        
    	  menu.find('a').on('click', function(){
      	  var issub     = $(this).hasClass('has_sub')
      	  var dropdown  = $(this).next('ul')
      	  var opentree  = $(this).parent().parent().find('ul.open,ul.active')
      	  
      	  if ( issub ) {
        	  if ( dropdown.is(':hidden') ) {
        	    opentree.slideUp(150).removeClass('open').prev('a.selected').removeClass('selected')
        	    dropdown.slideDown(150).addClass('open')
        	    $(this).addClass('selected').find('.icon-angle-down').removeClass('icon-angle-down').addClass('icon-angle-up')
        	  } else {
        	    dropdown.slideUp(150).removeClass('open')
        	    $(this).removeClass('selected').find('.icon-angle-up').removeClass('icon-angle-up').addClass('icon-angle-down')
        	  }
        	  return false
      	  }
    	  })
      }
      /**** END / type: mobile ****/
      
      
      /**** type: dropdown & mega ****/
      if ( navtype!='mobile' ) {
    		$(this).children('li.has_sub').hoverIntent(
    			function(){
      		  if ( navtype == 'mega' ) {
      		    var top = $(this).find('ul.dropdown').parent().height() + $(this).find('ul.dropdown').parent().position().top;
              $(this).children('ul.dropdown').css({ top:top })
            }
    				
    				$(this).addClass('open').css({overflow:'visible'}).children('ul.dropdown').slideDown(150).css({overflow:'visible'});
    			},
    			function(){
    				$(this).removeClass('open').children('ul.dropdown').slideUp(150);
    			}
    		);
      }
      /**** END / type: dropdown & mega ****/
      
      
      /**** type: dropdown ****/
      if ( navtype=='dropdown' ) {
        $(this).find('a.level_2, a.level_3').prepend('<span class="icon-angle-right" />');
        
        // set left or right dropdown depending on position in manu
        $(this).children('li').each(function(){
          if ( $(this).position().left > ( $(this).parent().width()/2 ) )
            $(this).addClass('dropdown_left');
          else
            $(this).addClass('dropdown_right');
        });
        
        $(this).find('ul.dropdown li.has_sub').hoverIntent(
      		function(){
      			$(this).addClass('open').children('ul').slideDown(150);
      		},
      		function(){
      			$(this).removeClass('open').children('ul').slideUp(150);
      		}
      	);
        
      }
      /**** END / type: dropdown ****/
      
      
      /**** type: mega ****/
      if ( navtype=='mega' ) {
        $(this).find('a.level_3').prepend('<span class="icon-angle-right" />');
      }
      /**** END / type: mega ****/
      
      
    } else {
      $(this).find('ul.dropdown').remove();
    }
    
	});
	  
	/* _global > init tooltips */
  $(function() {
    var tooltip = $('[data-toggle^="tooltip"]');
    if (tooltip.length > 0) 
      tooltip.tooltip();
  });
  
  
	/* _product > tabs */
  $('.nav-tabs a:first').tab('show');
  $('.nav-tabs a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
    
    // equalize containers on tab load
    $('.equalize:visible').equalizer({ min: 280, columns : '.helper_equalize' });
  })
  
	/* _product > tabs > external link opens a tab */
  $('.ext-tab').on('click', function (e) {
    e.preventDefault();
    
    var linktotab = $(this).attr('href');
    $(function (e) {
      $('.nav-tabs a[href="' + linktotab + '"]').tab('show')
    });
  });
  
  $('#box_languages,#box_currencies').hoverIntent(
		function(){
			$(this).children('.toggle').addClass('open');
			$(this).children('.dropdown').slideDown(150);
		},
		function(){
			$(this).children('.toggle').removeClass('open');
			$(this).children('.dropdown').slideUp(150);
		}
	);

	/* _product > reviews-write > init star-rating */
  $(function(){ $('input.star-rating').rating() });
    
	/* __checkout > agb approval */
	$("#conditions_approval").each( function(){
	  var agbframe = $(this).children('.agbframe');
	  var selector = $(this).children('fieldset');
	  var label = selector.children('label');
	  var radio = label.children('input');
	  
	  label.click( function(){
	    if (radio.is(':checked')){
  	    selector.addClass('alert-success').removeClass('alert-warning');
	    } else {
  	    selector.removeClass('alert-success').addClass('alert-warning');
    	  conditions_approved = false;
	    }
	  });
	});
	
	/* __checkout > select shipping and payment methods */
	$(".method_selector .selectable").each(function() {
		var animationSpeed = 250;
		
		var deselectAll	= function (element){
			$('.selected_method').removeClass('selected_method').find('.icon-ok').remove();
			$('.method_description:visible').slideUp(animationSpeed);
		};
		
		var selectThis 	= function (element){
			$(element).addClass('selected_method');
			$(element).find('.method_description').slideDown(animationSpeed);
			$(element).find(':radio').trigger("click");
			$('<span class="icon-ok" />').insertAfter( $(element).children('.method_name') );
		};
		
		$(this).css("cursor","pointer");
		$('.method_description').hide();
		
		if ( $(this).find(":radio").is(":checked") ){
			selectThis(this);
		};
		
		$(this).click(function() {
		  if (!$(this).hasClass('selected_method')) {
  			deselectAll();
  			selectThis($(this));
  		}
		});
	});
	
	$(".make_pagination").each(function() {
    var container = $(this).find('a').parent() , items = container.children('a') , current = container.children('strong')
    
    current.wrap( '<li class="active"><span /></li>' )
    items.wrap( '<li />' )
    $(this).find('li').wrapAll( '<ul class="pagination" />')
	});
	
  $('.pimage_slider_gallery li').click(function () {
    if ($(this).hasClass('active')) return;
    
    var current = $('.pimage_slider_gallery .active');
    if ( $(this).hasClass('next_slide') ) {
      current.next().addClass('active')
    } else {
      $(this).addClass('active').parent().children('.active').removeClass('active');
      $(this).addClass('active')
    }
  });
  $('.pimage_slider_gallery li:eq(0)').click() // gallery init
  
	/* _product-listing > equalize product-container height */
  $('.equalize:visible').equalizer({ min: 280, columns : '.helper_equalize' });
  
	/* _global > topmenu affixed */
	if ($('#topmenu').length)
    $('#topmenu').affix({ offset: { top: $('#topmenu').offset().top } }).width( ( $('.page_container.boxed').length ) ?  $('.page_container').width() :  $('.page_container').width() );
  
	$(".resize").fitToParent();
	$("._resize").fitToParent({spacing: 10});
}); /* $(document).ready */


$(window).on("resize", function(){
  $('.equalize:visible').equalizer({ min: 280, columns : '.helper_equalize' });
	if ($('#topmenu').length)
    $('#topmenu').affix({ offset: { top: $('#topmenu').offset().top } }).width( ( $('.page_container.boxed').length ) ?  $('.page_container').width() :  $('.page_container').width() );
});

$(window).bind("load", function(){
  $('.equalize:visible').equalizer({ min: 280, columns : '.helper_equalize' });
	$(".resize").fitToParent();
	$("._resize").fitToParent({spacing: 10});
  
	/* _global > scroll to target */
  $('.scroll-to').on('click', function(e) {
    e.preventDefault();
    
    var linktotab   = $(this).attr('href');
    var offeset     = $('#topmenu').height() * 2;
    var speed       = $(this).is('[scroll-speed]') ? parseInt($(this).attr('scroll-speed')) : 500;
    
    $.scrollTo(linktotab, speed, {offset: -offeset});
  });
});
