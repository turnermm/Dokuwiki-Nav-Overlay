if(JSINFO && !JSINFO['overlay']) {  
    jQuery( document ).ready(function() {   
      jQuery('#overlay').toggle();     
   });   
}

jQuery( document ).ready(function() {   
    jQuery( "#overlay" ).draggable();
   /*  jQuery( "#overlay" ).css('width','400px'); */
});

   
