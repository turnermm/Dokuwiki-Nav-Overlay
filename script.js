if(JSINFO && !JSINFO['overlay']) {  
    jQuery( document ).ready(function() {   
      jQuery('#overlay').toggle();     
   });   
}
 var theUserposition= {'x':0,'y':0,'position':'absolute'};  
jQuery( document ).ready(function() {   
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
         jQuery(this).css('left',position.left);
         jQuery(this).css('top',position.top);
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
 jQuery( "a.ovl_fix_toggle" ).on('click', function() {
    var which = jQuery("#overlay").css('position');    
    var button_text;
    if(which == 'fixed') {
        which = "absolute";  
        button_text = LANG.plugins.overlay.absolute;
      }
    else if(which == "absolute") {
        which = 'fixed';
        button_text = LANG.plugins.overlay.fixed;
        theUserposition.x = 0;
        theUserposition.y = 0;
        jQuery("#overlay" ).css({top:  theUserposition.y, left:  theUserposition.x, position:which}); 
    }  
    else button_text = "---";
       var y = jQuery("#overlay").css('top');    
       var x = jQuery("#overlay").css('left');   
       jQuery(this).html(button_text);
       
       var pos = x.toString() + '#' + y.toString() + '#' + which;       
       theUserposition.x = x;
       theUserposition.y = y;
       setOverlayCookie('OverlayUserposition', pos) ;
       jQuery("#overlay").css('position',which);    
       
 });
 
 jQuery(window).on('beforeunload', function(){ 
   var which = jQuery("#overlay").css('position');    
   var y = jQuery("#overlay").css('top');    
   var x = jQuery("#overlay").css('left');    
   
    var pos =x.toString() + '#' + y.toString() + '#' + which;
    var pos = x.toString() + '#' + y.toString() + '#' + which;
    setOverlayCookie('OverlayUserposition', pos) ;
});
});

jQuery(window).load(function() {  
            var pos  = overlay_getCookie('OverlayUserposition') ;
            var  pos_ar, ptop=0, pleft=0;
            var ptype;       
        
            if(pos) {
                pos_ar = pos.split('#');
                pleft = parseInt(pos_ar[0]);
                ptop = parseInt(pos_ar[1]);                
                ptype=pos_ar[2];
            }
            else if(JSINFO['ol_left']) {                 
                pleft = JSINFO['ol_left'];
                ptop = JSINFO['ol_top'] ;               
                ptype=JSINFO['position'] ;
            }
            else ptype='absolute';
            
           if(ptype == 'fixed') {        
                button_text = LANG.plugins.overlay.fixed;
              }
            else if(ptype == "absolute")
            {        
                button_text = LANG.plugins.overlay.absolute;
              }  
            else    button_text = "---";      
      //     jQuery( "a.ovl_fix_toggle" ).html(button_text + "-" + ptype);      
           jQuery( "a.ovl_fix_toggle" ).html(button_text);      
            jQuery("#overlay" ).css({top: ptop, left: pleft, position:ptype});     
           
});

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
 
