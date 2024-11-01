<?php
function wpachievements_twr_share_achievement(){
  echo "<div id='wpa_hidden'><div id='wpa_website_url'>".get_bloginfo('url')."</div><div id='wpa_website_name'>".get_bloginfo('name')."</div></div>";
  ?>
  <script>jQuery('.wpa_twr_link').click(function(event){
    event.preventDefault();
    
    var leftPosition = (screen.width/2)-(550/2);
    var topPosition = (screen.height/2)-(500/2);

    window.open( jQuery(this).attr('href') , "TwitterWindow", "status=no,height=450,width=550,resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");

  });</script>
  <?php
}
add_action('wpachievements_after_show_achievement', 'wpachievements_twr_share_achievement');

function wpachievements_twr_share_achievement_return(){
  $html = "<div id='wpa_hidden'><div id='wpa_website_url'>".get_bloginfo('url')."</div><div id='wpa_website_name'>".get_bloginfo('name')."</div></div>
  <script>jQuery('.wpa_twr_link').click(function(event){
    event.preventDefault();
    
    var leftPosition = (screen.width/2)-(550/2);
    var topPosition = (screen.height/2)-(500/2);

    window.open( jQuery(this).attr('href') , 'TwitterWindow', 'status=no,height=450,width=550,resizable=yes,left=' + leftPosition + ',top=' + topPosition + ',screenX=' + leftPosition + ',screenY=' + topPosition + ',toolbar=no,menubar=no,scrollbars=no,location=no,directories=no');

  });</script>";
  
  return $html;
}
?>