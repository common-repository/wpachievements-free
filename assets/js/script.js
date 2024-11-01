jQuery(document).ready(function($){
  $("#wpa_listing a").click(function(event){
    event.preventDefault();
    var tab = $(this).attr("id");
    $("#wpa_listing .acti_tab").removeClass("acti_tab");
    $(this).parent().addClass("acti_tab");
    $("#wpa_info .achive_tab").hide();
    $("#wpa_info #ach_"+tab).show();
  });
  $("#quest_listing a").click(function(event){
    event.preventDefault();
    var tab = $(this).attr("id");
    $("#quest_listing .acti_tab").removeClass("acti_tab");
    $(this).parent().addClass("acti_tab");
    $("#quest_info .achive_tab").hide();
    $("#quest_info #quest_"+tab).show();
  });

  $(".activity-code-form").submit(function(e) {
    e.preventDefault();

    var code = $(".activity-code-form input[name=activity_code]").val();

    if ( ! code ) {
      return false;
    }

    var options = {
      type: "POST",
      url: wpa_ajax_object.ajaxurl,
      data: {
        action: "wpa_handle_activity_codes",
        activity_code: code
      },
      dataType: "json",
      beforeSend: function() {
        $(".wpa_activity_code_message").html("");
        $(".wpa_activity_code_wrap .wpa_loader").fadeIn("fast");
      },
      success: function( response ) {
        $(".wpa_activity_code_wrap .wpa_loader").hide();
        $(".wpa_activity_code_message").html(response);
      },
    };

    $.ajax(options);

    return false;
  });
});