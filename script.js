if(JSINFO && !JSINFO['overlay']) {  
    jQuery( document ).ready(function() {   
      jQuery('#overlay').toggle();     
   });   
}

jQuery( document ).ready(function() {   
  var theUserposition= {'x':0,'y':0};        
    jQuery( "#overlay" ).draggable({
        drag: function(event,ui){
             var offset = jQuery(this).offset();
            var xPos = offset.left;
            var yPos = offset.top;
        }, 
        stop: function(){
            var finalOffset = jQuery(this).offset();
            theUserposition.x = finalOffset.left;
            theUserposition.y = finalOffset.top;          
        },
    });
    if(JSINFO ) {
       if(JSINFO['ol_width']) {
           jQuery( "#overlay" ).css('width',JSINFO['ol_width']); 
       }    
       if(JSINFO['ol_height']) {
           jQuery( "#overlay" ).css('height',JSINFO['ol_height']);            
        }
   }
 
 jQuery(window).on('beforeunload', function(){ 
    var pos = (theUserposition.x).toString() + '#' + (theUserposition.y).toString();
    setOverlayCookie('OverlayUserposition', pos) ;
});
});

function setOverlayCookie(cname, cvalue) {
    var d = new Date();  
    d.setTime(d.getTime() + (10*60*1000)); //10 minutes
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";    
}
