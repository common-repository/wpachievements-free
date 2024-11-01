jQuery(document).ready(function(){
  var href = jQuery("#toplevel_page_edit-post_type-wpquests a").attr("href");
  jQuery("#toplevel_page_edit-post_type-wpquests").remove();
  jQuery("#toplevel_page_edit-post_type-wpachievements ul li.wp-first-item").after("<li><a href=\""+href+"\">WPQuests</a></li>");
});