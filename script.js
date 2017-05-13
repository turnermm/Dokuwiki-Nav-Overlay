if(JSINFO && !JSINFO['overlay']) {  
    jQuery( document ).ready(function() {   
      jQuery('#overlay').toggle();     
   });   
}

jQuery( document ).ready(function() {   
  var theUserposition= {'x':0,'y':0};        
    jQuery( "#overlay" ).draggable({
        drag: function(event,ui){
        var position = jQuery(this).position();
        theUserposition.y = position.top;
         theUserposition.x =position.left;
        }, 
        stop: function(){
        var position = jQuery(this).position();
        theUserposition.y = position.top;
         theUserposition.x =position.left;
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

jQuery(window).load(function() {  
    jQuery('#overlay').toggle(); 
    ovl_setPosition();
    jQuery('#overlay').toggle();     
});
function  ovl_setPosition() {       
            var pos  = overlay_getCookie('OverlayUserposition') ;
           // if(!pos) return;
            var pos_ar, ptop, pleft;
            
            if(pos) {
                pos_ar = pos.split('#');
                pleft = parseInt(pos_ar[0]);
                ptop = parseInt(pos_ar[1]);                
            }
            else {
                pleft = JSINFO['ol_left'];
                ptop = JSINFO['ol_top']
            }

            jQuery("#overlay" ).css({top: ptop, left: pleft, position:'absolute'});     
           
};

function setOverlayCookie(cname, cvalue) {
    var d = new Date();  
    d.setTime(d.getTime() + (60*60*1000)); //60 minutes
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";    
}

 function overlay_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
 }
 
