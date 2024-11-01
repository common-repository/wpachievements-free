jQuery(document).ready(function($){
  function wpa_remove_error() {
    jQuery("#achievement_details .wpachievements-error").removeClass("wpachievements-error");
    jQuery("#achievement_details .wpachievements-error-border").removeClass("wpachievements-error-border");
    jQuery("#achievement_details .wpachievements-error-background").removeClass("wpachievements-error-background");
  }


  if(jQuery("#wpachievements_achievements_data_event,select.trigger_select").is(":disabled") ){
    jQuery("#event_details,.event_details_holder").show();
  }
  jQuery("#wpachievements_achievements_data_event").change(function(){
    if(jQuery(this).val() !== "none"){
      jQuery("#event_details,.event_details_holder").fadeIn("slow");
    } else{
      jQuery("#event_details,.event_details_holder").hide();
    }

    jQuery("#post_id").hide();

    if(jQuery(this).val() === "custom_trigger"){
      jQuery("#custom_event_details").fadeIn("slow");
      jQuery("#activity_code_event_details").hide();
    } else if (jQuery(this).val() === "activity_code_achievement" ) {
      jQuery("#activity_code_event_details").fadeIn("slow");
      jQuery("#custom_event_details").hide();
    }
    else{
      jQuery("#custom_event_details").hide();
      jQuery("#activity_code_event_details").hide();
    }

    switch( jQuery(this).val() ) {
    case "user_post_view":
    case "user_page_view":
        jQuery("#post_id label").html("Post ID: <small>(optional)</small>");
        jQuery("#post_id").fadeIn("slow");
        break;
    case "gform_sub":
        jQuery("#post_id label").text("Form ID:");
        jQuery("#post_id").fadeIn("slow");
        break;
    case "ld_lesson_complete":
    case "sensei_lesson_complete":
        jQuery("#post_id label").html("Lesson ID: <small>(optional)</small>");
        jQuery("#post_id").fadeIn("slow");
        break;
    case "wpcw_module_complete":
        jQuery("#post_id label").html("Module ID: <small>(optional)</small>");
        jQuery("#post_id").fadeIn("slow");
        break;
    case "wplms_unit_complete":
        jQuery("#post_id label").html("Unit ID: <small>(optional)</small>");
        jQuery("#post_id").fadeIn("slow");
        break;
    case "wplms_start_assignment":
    case "wplms_submit_assignment":
    case "wplms_evaluate_assignment":
        jQuery("#post_id label").html("Assignment ID: <small>(optional)</small>");
        jQuery("#post_id").fadeIn("slow");
        break;
    case "wplms_start_quiz":
    case "wplms_submit_quiz":
    case "wplms_evaluate_quiz":
    case "wplms_quiz_retake":
    case "wplms_quiz_reset":
        jQuery("#post_id label").html("Quiz ID: <small>(optional)</small>");
        jQuery("#post_id").fadeIn("slow");
        break;
    case "wplms_start_course":
    case "wplms_submit_course":
    case "wplms_evaluate_course":
    case "ld_course_complete":
    case "sensei_course_complete":
    case "wpcw_course_complete":
    case "wplms_certificate_earned":
    case "wplms_badge_earned":
    case "wplms_course_subscribed":
    case "wplms_course_reset":
    case "wplms_course_retake":
    case "wplms_course_review":
    case "wplms_course_unsubscribe":
    case "wplms_renew_course":
        jQuery("#post_id label").html("Course ID: <small>(optional)</small>");
        jQuery("#post_id").fadeIn("slow");
        break;
    default:
        jQuery("#post_id").hide();
    }


    if(jQuery(this).val() === "wplms_evaluate_course" || jQuery(this).val() === "wplms_evaluate_quiz"){
      jQuery("#wplms_evaluate_limit").hide();
      if( jQuery(this).val() === "wc_user_spends" ){
        jQuery("#wplms_evaluate_limit label small").hide();
      } else{
        jQuery("#wplms_evaluate_limit label small").show();
      }
      jQuery("#wplms_evaluate_limit").fadeIn("slow");
    } else{
      jQuery("#wplms_evaluate_limit").hide();
    }


    if(jQuery(this).val() === "cp_bp_group_joined"){
      jQuery("#ass_title").fadeIn("slow");
    } else{
      jQuery("#ass_title").hide();
    }
    if(jQuery(this).val() === "wc_order_complete" || jQuery(this).val() === "wc_user_spends"){
      jQuery("#woo_order_limit").hide();
      if( jQuery(this).val() === "wc_user_spends" ){
        jQuery("#woo_order_limit label small").hide();
      } else{
        jQuery("#woo_order_limit label small").show();
      }
      jQuery("#woo_order_limit").fadeIn("slow");
    } else{
      jQuery("#woo_order_limit").hide();
    }
    if(jQuery(this).val() === "ld_quiz_perfect" || jQuery(this).val() === "sensei_quiz_perfect"){
      jQuery("#first_try").hide();
      jQuery("#first_try").fadeIn("slow");
    } else{
      jQuery("#first_try").hide();
    }

    wpa_remove_error();
  });

  jQuery("#quest_item_holder").on("change", "select.trigger_select", function(){
    var parentID = jQuery(this).parent().parent().attr("id");
    if(jQuery(this).val() !== "none"){
      jQuery("#event_details,.event_details_holder").fadeIn("slow");
    } else{
      jQuery("#event_details,.event_details_holder").hide();
    }
    if(jQuery(this).val() === "wpachievements_achievement"){
      jQuery("#"+parentID+" #custom_event_details").fadeIn("slow");
    } else{
      jQuery("#"+parentID+" #custom_event_details").hide();
    }
    if(jQuery(this).val() === "gform_sub"){
      jQuery("#"+parentID+" #post_id").hide();
      jQuery("#"+parentID+" #post_id label").text("Form ID:");
      jQuery("#"+parentID+" #post_id").fadeIn("slow");
    } else if( jQuery(this).val().indexOf("ld_quiz_pass") >= 0 || jQuery(this).val().indexOf("wpcw_quiz") >= 0 || jQuery(this).val().indexOf("sensei_quiz_pass") >= 0 ){
      jQuery("#"+parentID+" #post_id").hide();
      jQuery("#"+parentID+" #post_id label").html("Quiz ID: <small>(optional)</small>");
      jQuery("#"+parentID+" #post_id").fadeIn("slow");
    } else if(jQuery(this).val() === "ld_lesson_complete" || jQuery(this).val() === "sensei_lesson_complete"){
      jQuery("#"+parentID+" #post_id").hide();
      jQuery("#"+parentID+" #post_id label").html("Lesson ID: <small>(optional)</small>");
      jQuery("#"+parentID+" #post_id").fadeIn("slow");
    } else if(jQuery(this).val() === "ld_course_complete" || jQuery(this).val() === "sensei_course_complete" || jQuery(this).val() === "wpcw_course_complete"){
      jQuery("#"+parentID+" #post_id").hide();
      jQuery("#"+parentID+" #post_id label").html("Course ID: <small>(optional)</small>");
      jQuery("#"+parentID+" #post_id").fadeIn("slow");
    } else if(jQuery(this).val() === "wpcw_module_complete"){
      jQuery("#"+parentID+" #post_id").hide();
      jQuery("#"+parentID+" #post_id label").html("Module ID: <small>(optional)</small>");
      jQuery("#"+parentID+" #post_id").fadeIn("slow");
    } else{
      jQuery("#"+parentID+" #post_id").hide();
    }
    if(jQuery(this).val() === "cp_bp_group_joined"){
      jQuery("#"+parentID+" #ass_title").fadeIn("slow");
    } else{
      jQuery("#"+parentID+" #ass_title").hide();
    }
    if(jQuery(this).val() === "wc_order_complete" || jQuery(this).val() === "wc_user_spends"){
      jQuery("#"+parentID+" #woo_order_limit").hide();
      if( jQuery(this).val() === "wc_user_spends" ){
        jQuery("#"+parentID+" #woo_order_limit label small").hide();
      } else{
        jQuery("#"+parentID+" #woo_order_limit label small").show();
      }
      jQuery("#"+parentID+" #woo_order_limit").fadeIn("slow");
    } else{
      jQuery("#"+parentID+" #woo_order_limit").hide();
    }
    if(jQuery(this).val() === "ld_quiz_perfect" || jQuery(this).val() === "sensei_quiz_perfect"){
      jQuery("#"+parentID+" #first_try").hide();
      jQuery("#"+parentID+" #first_try").fadeIn("slow");
    } else{
      jQuery("#"+parentID+" #first_try").hide();
    }

    wpa_remove_error();
  });

  jQuery("#image_preview_holder").on("click", "#achievement_image_remove", function(event){
    event.preventDefault();
    jQuery("#achievement_image #image_preview_holder").empty();
    jQuery("#achievement_image #no-image-links").show();
    jQuery("#upload_image").val("");
  });

  jQuery("span").on("click", "#achievement_image_pick", function(event){
    event.preventDefault();
    jQuery("#image_preview_holder").hide();
    jQuery("#default-image-selection").show();
  });

  jQuery("span").on("click", "#rank_image_pick", function (event){
    event.preventDefault();
    jQuery("#image_preview_holder").hide();
    jQuery("#default-image-selection").show();
    jQuery("#rank_image_pick").hide();
  });

  jQuery("div.clear").on("click", "#rank_image_close", function (event){
    event.preventDefault();
    jQuery("#default-image-selection").hide();
    jQuery("#rank_image_pick").show();
  });

  jQuery("#default-image-selection").on("click", ".radio_btn", function (event){
    jQuery("#selected_btn").attr("id","");
    jQuery(this).attr("id","selected_btn");
    jQuery("input[name=achievement_badge]:checked").attr("checked",false);
    jQuery(this).parent().find("input[name=achievement_badge]").attr("checked",true);
    jQuery("#image_preview_holder #image_preview_inner").empty();
    jQuery("#upload_image").val( jQuery(this).attr("src") );
  });

  var custom_uploader;
  jQuery("span").on("click", "#upload_image_button", function (event){
    event.preventDefault();
    jQuery("#default-image-selection").hide();
    jQuery("#default-image-selection input[name=achievement_badge]:checked").attr("checked", false);
    jQuery("#default-image-selection input[type=\"radio\"]:checked").prop("checked", false);
    jQuery("#selected_btn").attr("id","");
    if(custom_uploader){
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
    custom_uploader.on("select", function(){
      var attachment = custom_uploader.state().get("selection").first().toJSON();
      jQuery("#upload_image").val(attachment.url);

      jQuery("#image_preview_holder").empty();
      jQuery("#image_preview_holder").append("<img src=\""+attachment.url+"\" alt=\"Uploaded Achievement Image\" /><br/><a href=\"#\" id=\"achievement_image_remove\">Remove</a>");

      jQuery("#achievement_image #no-image-links").hide();
      jQuery("#achievement_image #image_preview_holder").fadeIn();

    });
    custom_uploader.open();
  });
  jQuery("#titlewrap #title").bind("keyup",function(){
    jQuery(this).removeClass("wpachievements-error-border");
  });

  function wpa_check_upload_image() {
    if( jQuery("#upload_image").val() === "" ) {
      jQuery("#achievement_image").addClass("wpachievements-error-border");
      jQuery("#achievement_image .hndle").addClass("wpachievements-error-background");
      return false;
    }
    return true;
  }

  jQuery(".post-type-wpachievements").on("click", "input[type=\"submit\"]", function (event){
    event.preventDefault();
    var error = "";
    var thisevent = jQuery("#wpachievements_achievements_data_event").val();

    if( jQuery("#titlewrap #title").val() === "" ){
      jQuery("#titlewrap #title").addClass("wpachievements-error-border");
      error = "error";
    }
    if( thisevent === "" ){
      jQuery("#wpachievements_achievements_data_event").addClass("wpachievements-error-border");
      jQuery("label[for=\"wpachievements_achievements_data_event\"").addClass("wpachievements-error");
      error = "error";
    }
    if( thisevent === "gform_sub" && jQuery("#wpachievements_achievements_data_post_id").val() <= 0 ){
      jQuery("#wpachievements_achievements_data_post_id").addClass("wpachievements-error-border");
      jQuery("label[for=\"wpachievements_achievements_data_post_id\"").addClass("wpachievements-error");
      error = "error";
    }
    if( thisevent === "wc_user_spends" && jQuery("#wpachievements_achievement_woo_order_limit").val() <= 0 ){
      jQuery("#wpachievements_achievement_woo_order_limit").addClass("wpachievements-error-border");
      jQuery("#wpachievements_achievement_woo_order_limit").next().find("input[type=\"button\"]").addClass("wpachievements-error-border");
      jQuery("label[for=\"wpachievements_achievement_woo_order_limit\"").addClass("wpachievements-error");
      error = "error";
    }
    if( ! wpa_check_upload_image() ){
      error = "error";
    }
    if( thisevent === "custom_trigger" && jQuery("#wpachievements_achievements_custom_trigger_id").val() === "" ){
      jQuery("#wpachievements_achievements_custom_trigger_id").addClass("wpachievements-error-border");
      jQuery("label[for=\"wpachievements_achievements_custom_trigger_id\"").addClass("wpachievements-error");
      error = "error";
    }
    if( thisevent === "custom_trigger" && jQuery("#wpachievements_achievements_custom_trigger_desc").val() === "" ){
      jQuery("#wpachievements_achievements_custom_trigger_desc").addClass("wpachievements-error-border");
      jQuery("label[for=\"wpachievements_achievements_custom_trigger_desc\"").addClass("wpachievements-error");
      error = "error";
    }

    if( error === "" ){
      jQuery("#wpachievements_achievements_data_event:disabled").prop("disabled",false).fadeTo(0, 0.5);
      jQuery(".trigger_select:disabled").prop("disabled",false).fadeTo(0, 0.5);
      jQuery("form#post").submit();
    }

  });

  jQuery(".post-type-wpquests").on("click", "input[type=\"submit\"]", function (event){
    event.preventDefault();
    var error = "";

    if( jQuery("#titlewrap #title").val() === "" ){
      jQuery("#titlewrap #title").addClass("wpachievements-error-border");
      error = "error";
    }
    if( ! wpa_check_upload_image() ){
      error = "error";
    }
    var count = jQuery("#quest_item_counter").val();
    for(var i = 1, limit = count; i <= limit; i++){

      if( jQuery("#wpachievements_achievements_data_event_"+i).val() === "" ){
        jQuery("#wpachievements_achievements_data_event_"+i).addClass("wpachievements-error-border");
        jQuery("label[for=\"wpachievements_achievements_data_event_"+i+"\"").addClass("wpachievements-error");
        error = "error";
      }
      if( jQuery("#wpachievements_achievements_data_event_"+i).val() === "gform_sub" && jQuery("#wpachievements_achievements_data_post_id_"+i).val() <= 0 ){
        jQuery("#wpachievements_achievements_data_post_id_"+i).addClass("wpachievements-error-border");
        jQuery("label[for=\"wpachievements_achievements_data_post_id_"+i+"\"").addClass("wpachievements-error");
        error = "error";
      }
      if( jQuery("#wpachievements_achievements_data_event_"+i).val() === "wc_user_spends" && jQuery("#wpachievements_achievement_woo_order_limit_"+i).val() <= 0 ){
        jQuery("#wpachievements_achievement_woo_order_limit_"+i).addClass("wpachievements-error-border");
        jQuery("#wpachievements_achievement_woo_order_limit_"+i).next().find("input[type=\"button\"]").addClass("wpachievements-error-border");
        jQuery("label[for=\"wpachievements_achievement_woo_order_limit_"+i+"\"").addClass("wpachievements-error");
        error = "error";
      }
      if( jQuery("#wpachievements_achievements_data_event_"+i).val() === "custom_trigger" && jQuery("#wpachievements_achievements_custom_trigger_id_"+i).val() === "" ){
        jQuery("#wpachievements_achievements_custom_trigger_id_"+i).addClass("wpachievements-error-border");
        jQuery("label[for=\"wpachievements_achievements_custom_trigger_id_"+i+"\"").addClass("wpachievements-error");
        error = "error";
      }
      if( jQuery("#wpachievements_achievements_data_event_"+i).val() === "wpachievements_achievement" && jQuery("#wpachievements_achievements_data_ach_id_"+i).val() === "" ){
        jQuery("#wpachievements_achievements_data_ach_id_"+i).addClass("wpachievements-error-border");
        jQuery("label[for=\"wpachievements_achievements_data_ach_id_"+i+"\"").addClass("wpachievements-error");
        error = "error";
      }

    }

    if( error === "" ){
      jQuery("#wpachievements_achievements_data_event:disabled").prop("disabled",false).fadeTo(0, 0.5);
      jQuery(".trigger_select:disabled").prop("disabled",false).fadeTo(0, 0.5);
      var count = jQuery("#quest_item_counter").val();
      for(var i = 1, limit = count; i <= limit; i++){
        jQuery("#wpachievements_achievements_data_ach_id_"+i+":disabled").prop("disabled",false).fadeTo(0, 0.5);
        jQuery("#wpachievements_achievements_custom_trigger_id_"+i+":disabled").prop("disabled",false).fadeTo(0, 0.5);
      }
      jQuery("form#post").submit();
    }

  });

  jQuery("#add_rank_form #rank_save").click(function(event){
    event.preventDefault();
    var wpachievements_ranks_data_rank = jQuery("#wpachievements_ranks_data_rank").val();
    var wpachievements_ranks_data_points = jQuery("#wpachievements_ranks_data_points").val();
    var wpachievements_ranks_data_image = jQuery("#upload_image").val();
    jQuery.post(ajaxurl, { "action": "wpachievements_update_rank_ajax", "wpachievements_ranks_data_rank": wpachievements_ranks_data_rank, "wpachievements_ranks_data_points": wpachievements_ranks_data_points, "wpachievements_ranks_data_image": wpachievements_ranks_data_image },function(data){
      var data = data.replace(/<\/div>\d+/g, "");
      jQuery("#error_holder").empty().append(data);
      if(jQuery("#error_holder .error").length === 0){location.reload();}
    });
  });
  jQuery(".wpachievements_rank_remove").click(function(){
var thisrank = jQuery(this).attr("id").substring(20);
    var wpachievements_rank_remove = thisrank;
    jQuery.post(ajaxurl, { "action": "wpachievements_remove_rank_ajax", "wpachievements_rank_remove": wpachievements_rank_remove },function(data){
      var data = data.replace(/<\/div>\d+/g, "");
      jQuery("#error_holder").empty().append(data);
      if(jQuery("#error_holder .error").length === 0){jQuery("tr#rank_"+thisrank).remove();}
    });
  });
  jQuery(".rank_edit_link").click(function(){
    var editthis = jQuery(this).attr("id").substring(33);
    jQuery("#wpachievements_ranks_action_edit_"+editthis).hide();
    jQuery("#ranks_action_remove_"+editthis).hide();
    jQuery("#wpachievements_ranks_action_save_"+editthis).show();
    jQuery("#rank_cancel_link_"+editthis).show();
    jQuery("#rank_edit_"+editthis).html("<input type=\"text\" class=\"inputbox\" id=\"rank_input_"+editthis+"\" value=\""+jQuery("#rank_edit_"+editthis).text()+"\">");
    if( jQuery("#image_edit_"+editthis+" img").length > 0 ){
      jQuery("#image_edit_"+editthis).html("<input type=\"text\" class=\"inputbox\" id=\"image_input_"+editthis+"\" value=\""+jQuery("#image_edit_"+editthis+" img").attr("src")+"\">");
    } else{
      jQuery("#image_edit_"+editthis).html("<input type=\"text\" class=\"inputbox\" id=\"image_input_"+editthis+"\" value=\"\">");
    }
    if(editthis!==0){jQuery("#points_edit_"+editthis).html("<input type=\"text\" class=\"inputbox\" id=\"points_input_"+editthis+"\" value=\""+jQuery("#points_edit_"+editthis).text()+"\">");}
  });
  jQuery(".rank_save_link").click(function(){
    var editthis = jQuery(this).attr("id").substring(33);
    var thisrank = jQuery("#rank_input_"+editthis).val();
    var thispoint = jQuery("#points_input_"+editthis).val();
    if(thisrank===""){alert("The Rank name cannot be empty!!");}
    else if(thispoint===""){alert("The Rank points cannot be empty!!");}
    else{
      if(editthis===0){thispoint=0;}
    jQuery("#wpachievements_ranks_action_edit_"+editthis).show();
      jQuery("#wpachievements_ranks_action_save_"+editthis).hide();
      jQuery("#ranks_action_remove_"+editthis).show();
      jQuery("#rank_cancel_link_"+editthis).hide();
      jQuery("#rank_edit_"+editthis).html(thisrank);
      jQuery("#points_edit_"+editthis).html(thispoint);
      var wpachievements_ranks_data_rank = thisrank;
      var wpachievements_ranks_data_points = thispoint;
      var wpachievements_ranks_data_image = jQuery("#image_input_"+editthis).val();
      jQuery.post(ajaxurl, { "action": "wpachievements_update_rank_ajax", "wpachievements_ranks_data_rank": wpachievements_ranks_data_rank, "wpachievements_ranks_data_points": wpachievements_ranks_data_points, "wpachievements_ranks_data_image": wpachievements_ranks_data_image, "editthis": editthis }, function(data){
        var data = data.replace(/<\/div>\d+/g, "");
        jQuery("#error_holder").empty().append(data);
        location.reload();
      });
    }
  });
  jQuery(".rank_cancel_link,.achievement_cancel_link").click(function(){location.reload();});

  jQuery("#quest_add").click(function(event){
    event.preventDefault();
    jQuery("#quest_spinner").addClass( "is-active" );
    var count = parseInt( jQuery("#quest_item_counter").val(), 10);

    jQuery.post(ajaxurl, { "action": "wpachievements_quest_html", "quest_count": count }, function(data){
      jQuery(".event_details_holder").before(data);
      jQuery("#quest_spinner").removeClass( "is-active" );
      count++;
      jQuery("#quest_item_counter").val( count );
      var triggerCount = jQuery(".button_quest_remove").length;
      if( triggerCount > 2 ){
        jQuery(".button_quest_remove").removeClass("disabled");
      } else{
        jQuery(".button_quest_remove").addClass("disabled");
      }
    });

  });

  jQuery("#achievement_details").on("click", "a.button_quest_remove", function (event){
    event.preventDefault();
    if( !jQuery(this).hasClass("disabled") ){
      var count = jQuery("#quest_item_counter").val();
      var thisID = jQuery(this).parent().attr("id");
      thisID = parseInt(thisID.replace("quest_item_",""));

      jQuery("#quest_item_"+thisID).fadeOut("fast",function(){
        jQuery("#quest_item_"+thisID).remove();
        jQuery(".button_quest_remove").each(function() {
          var oldID = jQuery(this).parent().attr("id");
          oldID = parseInt(oldID.replace("quest_item_",""));
          if( oldID > thisID ){
            var NewID = oldID - 1;
            jQuery(this).parent().attr("id","quest_item_"+NewID);
          }
        });
        jQuery("#quest_item_counter").val( count - 1 );
        var triggerCount = jQuery(".button_quest_remove").length;
        if( triggerCount <= 2 ){
          jQuery(".button_quest_remove").addClass("disabled");
        } else{
          jQuery(".button_quest_remove").removeClass("disabled");
        }
      });
    }

    return false;
  });


  var position = { offset: "0, -1" };
  if ( typeof isRtl !== "undefined" && isRtl ) {
    position.my = "right top";
    position.at = "right bottom";
  }

  $(".wpa-suggest-user").each( function(){
    var $this = $( this ),
      post_id = $("#to_award_post_id").val();

    $this.autocomplete({
      source:    ajaxurl + "?action=wpa_autocomplete_user&post_id=" + post_id,
      delay:     500,
      minLength: 3,
      position:  position,
      open: function() {
        $( this ).addClass("open");
      },
      close: function() {
        $( this ).removeClass("open");
      },
      select: function( event, ui ) {
        if ( ui.item.id !== "undefined" ) {
          $("#to_award_user_id").val( ui.item.id );
        }
      }
    });
  });

  $("#award_user").click(function(){
    $("#user_login").addClass( "ui-autocomplete-loading" );

    $.post( ajaxurl, {
      "action": "wpa_award_achievement",
      "post_id": $( "#to_award_post_id" ).val(),
      "user_id": $( "#to_award_user_id").val(),
    },
    function( data ) {
      $("#user_login").removeClass( "ui-autocomplete-loading" );
      $("span.nobody").remove();
      $(".already_gained > p").append( data );
    });
  });
});