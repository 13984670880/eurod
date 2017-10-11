function goToByScroll(id){
  // Scroll
  $('html,body').animate({scrollTop: $("#"+id).offset().top}, 'slow');
}