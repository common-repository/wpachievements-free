jQuery(document).ready(function(){
  jQuery("#toplevel_page_edit-post_type-wpachievements").addClass("wp-has-current-submenu");
  jQuery("#toplevel_page_edit-post_type-wpachievements a.menu-top").addClass("wp-has-current-submenu wp-menu-open");
  jQuery("a[href$=\"edit.php?post_type=wpquests\"]").parent().addClass("current");
  jQuery("a[href$=\"edit.php?post_type=wpquests\"]").addClass("current");
});