/* global woocommerce_settings_params */
( function( $ ) {

  // Color picker
  $( ".colorpick" ).iris({
    change: function( event, ui ) {
      $( this ).parent().find( ".colorpickpreview" ).css({ backgroundColor: ui.color.toString() });
    },
    hide: true,
    border: true
  }).click( function() {
    $( ".iris-picker" ).hide();
    $( this ).closest( "td" ).find( ".iris-picker" ).show();
  });

  $( "body" ).click( function() {
    $( ".iris-picker" ).hide();
  });

  $( ".colorpick" ).click( function( event ) {
    event.stopPropagation();
  });
  
})( jQuery );
