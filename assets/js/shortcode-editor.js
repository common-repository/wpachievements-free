jQuery(document).ready( function($){
  $.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();

    $.each(a, function () {
      if (o[this.name] !== undefined) {
        if (!o[this.name].push) {
          o[this.name] = [o[this.name]];
        }

        o[this.name].push(this.value || "");
      }
      else {
        o[this.name] = this.value || "";
      }
    });

    var $radio = $("input[type=radio],input[type=checkbox]",this);

    $.each($radio,function(){
      if(!o.hasOwnProperty(this.name)){
        o[this.name] = false;
      }
      else {
        o[this.name] = true;
      }
    });

    return o;
  };

  function wpachievements_insert_shortcode() {
    var shortcode = $( "#wpa_select_shortcode").val();
    var data = $("#wpachievements-shortcode-editor-form").serializeObject();
    var output = shortcode;
    var sanitized_key;
    var conditional_value = "]";

    $.each(data, function( key, value ){
      if ( key.match( "^" + shortcode + "__" ) && value !== "" ) {
        // Remove the shortcode prefix
        sanitized_key = key.replace( shortcode + "__", "");
        // Remove square brackets (required for e.g. multiselect fields)
        sanitized_key = sanitized_key.replace( "[]", "" );

        if ( "conditional" === sanitized_key ) {
          conditional_value += " " + value;
        }
        else {
          // Generate the shortcode call with parameters
          output += " " + sanitized_key + "=\"" + value + "\"";
        }
      }
    });

    window.send_to_editor( "[" + output + conditional_value );
  }

  // Listen for changes to the selected shortcode
  $( "#wpa_select_shortcode" ).on( "change", function() {
    var shortcode = $( "#wpa_select_shortcode").val();

    $( ".wpa-shortcode-section" ).hide();
    $( "#" + shortcode  + "_wrapper" ).show();
  }).change();

  $( "#wpachievements_insert" ).on( "click", function(e) {
    e.preventDefault();
    wpachievements_insert_shortcode();
  });

  $( "#wpachievements_cancel" ).on( "click", function(e) {
    e.preventDefault();
    tb_remove();
  });
});