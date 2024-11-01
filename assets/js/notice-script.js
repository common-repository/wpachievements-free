jQuery(document).ready(function(){  
  jQuery("#license_save").click(function(){
    var thisurl = jQuery(".updated input[name=url_holder]").val();
    var wpa_license = jQuery(".updated input[name=wpach_license]").val();
    if( wpa_license !== "" ){
      jQuery.post(ajaxurl, { "action": "wpachievements_notice_ajax", "wpa_license": wpa_license },function(data) {
        location.reload();
      });
    } else{
      alert("You must enter a Purchase Code.");
    }
  });
});