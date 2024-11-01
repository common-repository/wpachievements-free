jQuery(document).ready(function(){

  function IsValidImageUrl(url) {
    jQuery("<img>", {
      src: url,
      error: function() { return true; },
      load: function() { return false; }
    });
  }

  var custom_uploader;
  jQuery("#wpachievements_achievements_data_form #upload_image_button").click(function(event){
    event.preventDefault();
    if (custom_uploader) {
      custom_uploader.open();
      return;
    }
    custom_uploader = wp.media.frames.file_frame = wp.media({
      title: "Choose Image",
      button: {
        text: "Choose Image"
      },
      multiple: false
    });
    custom_uploader.on("select", function() {
      var attachment = custom_uploader.state().get("selection").first().toJSON();
      jQuery("#wpachievements_achievements_data_form #upload_image").val(attachment.url);

      jQuery("#wpachievements_achievements_data_form #image_preview_holder #image_preview_inner").empty();
      jQuery("#wpachievements_achievements_data_form #image_preview_holder #image_preview_inner").append("<img src=\""+attachment.url+"\" alt=\"Uploaded Achievement Image\" style=\"max-width:50px;max-height:50px;\" />");
      jQuery("#wpachievements_achievements_data_form #image_preview_holder").fadeIn();

    });
    custom_uploader.open();
  });
  jQuery("#wpachievements_create").click(function(event){
    jQuery(this).hide();
    jQuery("#wpachievements_achievements_data_form").fadeIn();
    jQuery("html,body").animate({scrollTop: jQuery("#wpachievements_achievements_data_form").offset().top}, "slow");
  });
  jQuery("#wpachievements_achievements_data_form #image_pick").click(function(event){
    event.preventDefault();
    jQuery("#wpachievements_achievements_data_form #image_preview_holder").hide();
    jQuery("#wpachievements_achievements_data_form #radio_btn_holder").fadeIn();
  });
  jQuery("#wpachievements_achievements_data_form .radio_btn").click(function(event){
    jQuery("#wpachievements_achievements_data_form #selected_btn").attr("id","");
    jQuery(this).attr("id","selected_btn");
    jQuery(this).parent().find("input[name=achievement_badge]").attr("checked",true);
    jQuery("#wpachievements_achievements_data_form #image_preview_holder #image_preview_inner").empty();
    jQuery("#wpachievements_achievements_data_form #upload_image").val("");
  });
  jQuery("#wpachievements_achievements_data_form #achieve_sub").click(function(event){
    event.preventDefault();
    var wpa_mada = jQuery("#wpachievements_achievements_data_form #wpachievements_achievements_data_achievement").val();
    var wpa_madd = jQuery("#wpachievements_achievements_data_form #wpachievements_achievements_data_achievement_desc").val();
    var wpa_madp = jQuery("#wpachievements_achievements_data_form #wpachievements_achievements_data_points").val();
    var wpa_madwcp = "";

    if(jQuery("#wpachievements_achievements_data_form #wpachievements_achievements_data_wc_points").length > 0){
      wpa_madwcp = jQuery("#wpachievements_achievements_data_form #wpachievements_achievements_data_wc_points").val();
    }

    var wpa_madei = jQuery("#wpachievements_achievements_data_form #upload_image").val();
    var wpa_madeip = jQuery("#wpachievements_achievements_data_form input[name=achievement_badge]:checked").val();
    var wpa_uid = jQuery("#wpachievements_achievements_data_form input[name=wpa_uid]").val();

    jQuery.post(ajaxurl, { "action": "wpachievements_add_custom_achievement_ajax", "wpa_mada": wpa_mada, "wpa_madd": wpa_madd, "wpa_madp": wpa_madp, "wpa_madwcp": wpa_madwcp, "wpa_madei": wpa_madei, "wpa_madeip": wpa_madeip, "wpa_uid": wpa_uid },function(data) {
      var data = data.replace(/<\/div>\d+/g, "");
      jQuery("#error_holder").empty().append(data);
      if(jQuery("#error_holder .error").length === 0){
        location.reload();
      }
    });
  });
});