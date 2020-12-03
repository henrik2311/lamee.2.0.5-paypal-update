$('.nav.nav-tabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

$('.add_popover').popover();

$('#plugin_navi').each(function () {
  var clip = $(this).offset().top - $('#plugin_topbar').height() - 51;
  $(this).width($(this).parent().width());
})

$('a.btn-cancel').on('click', function (event) {
  event.preventDefault();
  history.back(1);
})

if ( $('iframe.fullsize').length ) {
  $('iframe.fullsize').each( function () {
    var offest = $(this).offset().top
    $(this).css({ 'width': $(document).width(), 'height': $(window).height() })
    $('#plugin_topbar').css({ 'margin': 0 })
  })
}

$( function() {
  $('.panel.panel-plugin-doku').each( function () {
    var 
      header = $(this).children( '.panel-heading' ),
      panel = $(this).children( '.panel-body' )
    
    panel.hide();
    header.css({ cursor: 'pointer' }).append('<span class="status-icon icon-chevron-up pull-right" />')
    
    header.on( 'click', function () {
      
      $('.panel.panel-plugin-doku > .panel-body:visible').slideUp(150).prev( '.panel-heading' ).children( '.icon-chevron-down' ).removeClass( 'icon-chevron-down' ).addClass( 'icon-chevron-up' )
      
      if ( $(this).next( '.panel-body' ).is( ':visible' ) ) {
        
        $(this).find( '.status-icon' ).removeClass( 'icon-chevron-down' ).addClass( 'icon-chevron-up' )
        $(this).next( '.panel-body' ).slideUp(150)
        
      } else {
        
        $(this).find( '.status-icon' ).removeClass( 'icon-chevron-up' ).addClass( 'icon-chevron-down' )
        $(this).next( '.panel-body' ).slideDown(150)
        
      }
    })
  });
})

$('#plugin_tabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

$( function() {

  setTimeout(function () {
    var sideBar = $('#plugin_navi')
    var navOuterHeight  = $('#main_navigation').height()
    var titleBarMargin   = parseInt( $('#plugin_topbar').css('margin-bottom') )
  
    sideBar.affix({
      offset: {
        top: function () {
          var offsetTop       = sideBar.offset().top
          
          return (this.top = offsetTop - navOuterHeight - titleBarMargin)
        }
      }
    }).css({ top: navOuterHeight + titleBarMargin })
  }, 100);
  
})
  

/** bestätigung der loeschung **/
$('a.btn-delete-entry').on('click', function () {
  var val = $(this).attr('data-entry');
  return confirm( unescape( "L%F6schen des Eintrages %22"+val+"%22 best%E4tigen%21" ) );
})

$('.getEditor').wysihtml5({html: true});
