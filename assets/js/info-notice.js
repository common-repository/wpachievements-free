jQuery(document).ready(function(){
  jQuery("#wpa_important_submit").click(function(event){
    event.preventDefault();
    var pathname = document.URL;
    pathname = pathname.substr(0, pathname.lastIndexOf("/"));
    if( pathname.toLowerCase().indexOf("network") >= 0 ){
      pathname = pathname.replace("network", "");
    }
    if( pathname.substr(pathname.length - 1) !== "/" ){
      pathname = pathname+"/";
    }
    window.location.href = pathname+"edit.php?post_type=wpachievements&page=wpachievements_faq";
  });
});