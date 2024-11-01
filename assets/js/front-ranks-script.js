jQuery(document).ready(function(){
  var timing;
  function refreshRank(){
    if(jQuery(".notice-wrap").is(":visible")){
      var pathArray = window.location;
      jQuery("#wp-admin-bar-custom_ranks_menu").load(pathArray+" #wp-admin-bar-custom_ranks_menu > *");
    }
    clearInterval(timing);
  }
  timing=setInterval(function(){refreshRank()},1000);
});