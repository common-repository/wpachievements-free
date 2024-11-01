<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 ***********************************
 *    R A N K I N G   S E T U P    *
 ***********************************
 */
 //*************** Setup ranking functions ***************\\
function wpachievements_getRank($uid){
  if(is_multisite()){
    global $wpdb;
    if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
      $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status'));
    } else{
      $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status'));
    }
  } else{
    $ranktype = strtolower(get_option('wpachievements_rank_type'));
  }
  if( $ranktype != 'achievements' ){
    $points = WPAchievements_User::get_points( $uid );
  } else{
    $points = (int)get_user_meta( $uid, 'achievements_count', true );
  }
  return wpachievements_pointsToRank( $points );
}

function wpachievements_getRankImage($uid){
  if(is_multisite()){
    global $wpdb;
    if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
      $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status'));
    } else{
      $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status'));
    }
  } else{
    $ranktype = strtolower(get_option('wpachievements_rank_type'));
  }
  if( $ranktype != 'achievements' ){
    $points = WPAchievements_User::get_points( $uid );
  } else{
    $points = (int)get_user_meta( $uid, 'achievements_count', true );
  }

  $ranks = (array) wpachievements_get_site_option( 'wpachievements_ranks_data' );
  ksort($ranks);
  $ranks = array_reverse($ranks, 1);

  foreach( $ranks as $p=>$r ) {
    if ( $points >= $p ) {
      if ( is_array($r) ) {
        return '<img src="'.$r[1].'" alt="Rank '.$r[0].' Image Icon" class="wpa_rank_badge" />';
      }

      return '';
    }
  }
}

function wpachievements_pointsToRank($points){
  $ranks = (array) wpachievements_get_site_option( 'wpachievements_ranks_data' );
  ksort($ranks);
  $ranks = array_reverse($ranks, 1);
  foreach($ranks as $p=>$r){
   if($points>=$p){
    if(is_array($r)){
      return $r[0];
    }

    return $r;
   }
  }
}

function wpachievements_rankToPoints($rank){
  $ranks = (array) wpachievements_get_site_option( 'wpachievements_ranks_data' );
  return array_search($rank, $ranks);
}

function wpachievements_rank_track($type, $uid, $postid, $points, $usersrank ){

  if(!empty($uid)){
    $currentusersrank = wpachievements_getRank($uid);
    if( !empty($usersrank) && $usersrank != $currentusersrank ){
      do_action('wpachievements_new_rank_gained', $uid);
    }
  }
 }
 add_action('wpachievements_after_new_activity', 'wpachievements_rank_track', 10, 5);
 add_action('wpachievements_after_new_custom_activity', 'wpachievements_rank_track', 10, 5);
/**
 *******************************************************
 *   W P A C H I E V E M E N T S   M E N U   T A B S   *
 *******************************************************
 */
 function wpa_ranks_widget($cur_user='') {
  global $wpdb;
  $current_user = wp_get_current_user();
  if( empty($cur_user) ){
    $cur_user = $current_user->ID;
  }

   if(is_multisite()){
    if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
      $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_type'));
    } else{
      $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_type'));
    }
  } else{
    $ranktype = strtolower(get_option('wpachievements_rank_type'));
  }

  $ranks = (array) wpachievements_get_site_option( 'wpachievements_ranks_data' );

  if( $ranktype != 'achievements' ){
    $points = WPAchievements_User::get_points( $cur_user );
    if(empty($points)){$points = 0;}
  } else{
    $points = get_user_meta( $cur_user, 'achievements_count', true );
    if(empty($points)){$points = 0;}
  }
  ksort($ranks);
  foreach($ranks as $p=>$r){
    if($points<$p){
      if(is_array($r)){ $nr = $r[0]; } else{ $nr = $r; }
      $tp = $p;
      $np = number_format($p - $points);
      $nrm = $np.' <span class="li_points_alt_col">'.__('until next rank', 'wpachievements').'</span>';
      break;
    }
  }
  $maxpoints=0;
  foreach($ranks as $p=>$r){
    if( $p > $maxpoints )
      $maxpoints = $p;
    if($points<$p){
      if(is_array($r)){ $nr = $r[0]; } else{ $nr = $r; }
      $tp = $p;
      $np = number_format($p - $points);

      $np = apply_filters( 'wpachievements_points_number_format', $p - $points );

      $nrm = $np.' <span class="li_points_alt_col">'.__('until next rank', 'wpachievements').'</span>';
      break;
    }
  }
  if ( empty($nrm) ) {
    $nrm = __('You are the highest rank!!', 'wpachievements');
    $tp = $maxpoints;
    $wid = 230;
    if ($points > $maxpoints) {
      $points = $maxpoints;
    }
  }
  else{
    if ( $points < 0 ) {
      $points = 0;
    }

    if ( $points ) {
      $count1 = $points / $tp;
      $count2 = $count1 * 100;
    }
    else {
      $count2 = 0;
    }

    $count = number_format($count2, 0);
    $wid = 230*($count/100);
  }
  $lvlstat='<div class="user_login_points"><div class="user_current_rank">'.wpachievements_getRank($cur_user).'</div><div class="pb_hold"><div class="pb_back_user_login"></div><div class="pb_bar_user_login"></div><div class="usr_point_count">'.$points.'/'.$tp.'</div></div><div class="li_points">'.$nrm.'</div></div>';
  return array($lvlstat,$wid);
 }