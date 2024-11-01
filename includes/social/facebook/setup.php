<?php
/**
*********************************************************
*    S O C I A L   M E D I A   I N T E G R A T I O N    *
*********************************************************
*/
$plugindir = dirname( __FILE__ );
$fbenab = wpachievements_get_site_option('wpachievements_pshare');

if ( $fbenab == 'yes' ) {
  function wpachievements_fb_share_achievement() {
    $appId = wpachievements_get_site_option('wpachievements_appID');
    if( !empty($appId) ){
      ?>
      <div id="fb-root"></div>
      <script>
        function wpa_fb_sharing( title, image, text ){
          var oldCB = window.fbAsyncInit;
          if(typeof oldCB === 'function'){
            FB.getLoginStatus(function(response) {
              if (response.status === 'connected') {
                FB.ui({
                  method: 'feed',
                  display: 'iframe',
                  name: title,
                  link: '<?php echo home_url(); ?>',
                  picture: image,
                  caption: text,
                  description: '<?php echo sprintf( __('Come to %s and gain achievements of your own!!!', 'wpachievements'), get_bloginfo('name') ); ?>'
                });
              }
              else {
                FB.ui({
                  method: 'feed',
                  name: title,
                  link: '<?php echo home_url(); ?>',
                  picture: image,
                  caption: text,
                  description: '<?php echo sprintf( __('Come to %s and gain achievements of your own!!!', 'wpachievements'), get_bloginfo('name') ); ?>'
                });
              }
            });
          }
          else{
            FB.getLoginStatus(function(response) {
              if (response.status === 'connected') {
                FB.ui({
                  method: 'feed',
                  display: 'iframe',
                  name: title,
                  link: '<?php echo home_url(); ?>',
                  picture: image,
                  caption: text,
                  description: '<?php echo sprintf( __('Come to %s and gain achievements of your own!!!', 'wpachievements'), get_bloginfo('name') ); ?>'
                });
              }
              else {
                FB.ui({
                  method: 'feed',
                  name: title,
                  link: '<?php echo home_url(); ?>',
                  picture: image,
                  caption: text,
                  description: '<?php echo sprintf( __('Come to %s and gain achievements of your own!!!', 'wpachievements'), get_bloginfo('name') ); ?>'
                });
              }
            });
          }
        }
      </script>
      <?php
    }
  }
  add_action('wpachievements_before_show_achievement', 'wpachievements_fb_share_achievement');

  function wpachievements_fb_share_achievement_filter($type) {
    $appId = wpachievements_get_site_option('wpachievements_appID');
    if( !empty($appId) ){

      ob_start();
      ?>
      <div id="fb-root"></div>
      <script type="text/javascript">
        function wpa_fb_sharing( title, image, text ) {
          FB.ui({
            method: 'feed',
            picture: image,
            name: title,
            link: "<?php echo home_url(); ?>",
            caption: text,
            description: "<?php printf( __('Come to %s and gain %s of your own!', 'wpachievements'), home_url(), $type ); ?>"
          });
        }
      </script>
      <?php
      $html = ob_get_clean();

      return $html;
    }
  }
}

function wpachievements_print_facebook_api() {
  $appId = wpachievements_get_site_option('wpachievements_appID');
  if ( is_user_logged_in() && $appId ) {
    ob_start();
    ?>
    <script type="text/javascript">
      window.fbAsyncInit = function() {
        FB.init({
        appId      : '<?php echo $appId; ?>',
        xfbml      : true,
        version    : 'v2.7'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>
    <?php
    echo ob_get_clean();
  }
}
add_action( 'wp_head', 'wpachievements_print_facebook_api' );
?>