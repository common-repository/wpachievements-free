<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

get_header();
global $post;
$locked = get_post_meta( $post->ID, 'game_locked', true );
$ranks = (array) wpachievements_get_site_option('wpachievements_ranks_data');
ksort($ranks);
$count='';
if(!empty($locked) && $locked!='any'){
 foreach($ranks as $p=>$r){
  $count++;
  if($locked<=$p){
   if(is_array($r)){ $locked = $r[0]; } else{ $locked = $r; }
   break;
  }
 }
}
echo '<center>';
 if(is_user_logged_in()){
  echo '<div id="content_locked">
   <div id="locked_icon_holder">';

     list($lvlstat,$wid) = wpa_ranks_widget();
     echo $lvlstat;
     echo "<script>
     jQuery(document).ready(function(){
       jQuery('.pb_bar_user_login').animate({width:'".$wid."px'},1500);
     });
     </script>";

   echo '</div>
   <div style="clear:both;height:1px;"></div>
   <p style="font-weight:bold;line-height:22px;margin-top:5px;">'. sprintf( __('To unlock this %s you must reach', 'wpachievements'), WPACHIEVEMENTS_POST_TEXT ) .':<br />Rank '.$count.': '.$locked.'</p>
  </div>';
 } else{
  echo '<div id="content_locked">
   <div id="locked_icon_holder">
    <img src="'.WPACHIEVEMENTS_URL . '/assets/img/locked.png" alt="Content Locked Icon" height="58" />
    <a href="'.get_bloginfo('url').'/wp-login.php?action=register" id="locked_register">'. __('Register to View', 'wpachievements') .'</a>
   </div>
   <div style="clear:both;height:1px;"></div>
   <p style="font-weight:bold;line-height:22px;margin-top:5px;">'. sprintf( __('To unlock this %s you must reach', 'wpachievements'), WPACHIEVEMENTS_POST_TEXT ) .':<br />Rank '.$count.': '.$locked.'</p>
  </div>';
 }
echo '</center>';
get_footer(); ?>