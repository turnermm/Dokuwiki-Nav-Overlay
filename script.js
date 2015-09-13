if(JSINFO && JSINFO['overlay']) {  
    jQuery( document ).ready(function() {   
       jQuery('#overlay').toggle();       
   });
   
}

  jQuery(function() {
    jQuery( "#overlay" ).draggable();
  });