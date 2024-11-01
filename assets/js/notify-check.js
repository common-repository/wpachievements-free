jQuery(document).ready(function(){
  var checkrate;

  if( WPA_Ajax.check_rate !== 0 && WPA_Ajax.check_rate < 5 ){
    checkrate = 5;
  } else{
    checkrate = WPA_Ajax.check_rate;
  }
  if( checkrate !== 0 ){
    jQuery(document).on( "heartbeat-send.wpachievements-check", function( e, data ) {

      // For one-time send, use wp.heartbeat.enqueue( 'item', 'value' );
      data["wpachievements-check"] = WPA_Ajax.userid;

      // Speed up heartbeat for faster results.
      // Only has to be called every 2.5 minutes.
      window.wp.heartbeat.interval(checkrate);

    // Listen for the tick custom event.
    }).on( "heartbeat-tick.wpachievements-check", function( event, data ) {
    
      if ( data.hasOwnProperty( "wpachievements-check" ) ) {
        jQuery("body").append(data["wpachievements-check"]);
        // Prints to the console { 'hello' : 'world' }
      }
    });
  
    jQuery(document).on( "heartbeat-send.wpachievements-quest-check", function( e, data ) {

      // For one-time send, use wp.heartbeat.enqueue( 'item', 'value' );
      data["wpachievements-quest-check"] = WPA_Ajax.userid;

      // Speed up heartbeat for faster results.
      // Only has to be called every 2.5 minutes.
      window.wp.heartbeat.interval(checkrate);

    // Listen for the tick custom event.
    }).on( "heartbeat-tick.wpachievements-quest-check", function( event, data ) {
    
      if ( data.hasOwnProperty( "wpachievements-quest-check" ) ) {
        jQuery("body").append(data["wpachievements-quest-check"]);
        // Prints to the console { 'hello' : 'world' }
      }
    });
  
    // Initial connection to cement our new interval timing
    window.wp.heartbeat.connectNow();
  }
});