jQuery(document).ready(function(){
  jQuery("#wpa_faq .wpa_faq_question h3").click(function(event){
    event.preventDefault();
    if( jQuery(this).parent().hasClass("active") ){
      jQuery("#wpa_faq .wpa_faq_question.active").find(".wpa_faq_answer").slideToggle();
      jQuery("#wpa_faq .wpa_faq_question.active").removeClass("active");
    } else{
      jQuery("#wpa_faq .wpa_faq_question.active").find(".wpa_faq_answer").slideToggle();
      jQuery("#wpa_faq .wpa_faq_question.active").removeClass("active");
      jQuery(this).parent().addClass("active");
      jQuery(this).next().slideToggle();
    }
  });
});