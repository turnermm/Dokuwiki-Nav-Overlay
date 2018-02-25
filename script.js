if(JSINFO && !JSINFO['overlay']) {  
    jQuery( document ).ready(function() {   
      jQuery('#overlay').toggle();     
   });   
}
 var theUserposition= {'x':0,'y':0,'position':'absolute'};  
jQuery( document ).ready(function() {   
    jQuery('.ui-resizable-se' ).css('position','sticky');
    jQuery('.ui-resizable-se' ).css('top','300');
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
    
    if (jQuery.fn.resizable) {
        jQuery( "#overlay" ).draggable().resizable({
              autoHide: true           
          }
       ); 
    }
  
OverlaySetSize();

 jQuery( "a.ovl_fix_toggle" ).on('click', function() {
    var which = jQuery("#overlay").css('position');    
    var button_text, title_text;
     
    if(which == 'fixed') {
        which = "absolute";  
        button_text = LANG.plugins.overlay.absolute;
        title_text = LANG.plugins.overlay.unfix_title;
      }
    else if(which == "absolute") {
        which = 'fixed';
        button_text = LANG.plugins.overlay.fixed;
        theUserposition.x = 0;
        theUserposition.y = 0;
        title_text = LANG.plugins.overlay.fix_title;
        jQuery("#overlay" ).css({top:  theUserposition.y, left:  theUserposition.x, position:which}); 
    }  
    else {
        button_text =  jQuery(this).html();
        title_text = jQuery(this).attr('title');
    }   
       var y = jQuery("#overlay").css('top');    
       var x = jQuery("#overlay").css('left');   
       jQuery(this).html(button_text);
       jQuery(this).attr('title', title_text);   
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
   var width = parseInt(jQuery("#overlay").css('width'));
   if(width < 200) width = 200;
   var height = parseInt(jQuery("#overlay").css('height'));   
   if(height < 200) height = 200;   
    var pos = x.toString() + '#' + y.toString() + '#' + which;
    setOverlayCookie('OverlayUserposition', pos) ;
    
    var dim = height.toString() + '#' + width.toString();    
    setOverlayCookie('OverlayUserDim',dim) ;
      
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
           else  {              
               button_text = LANG.plugins.overlay.fixed;
               ptop = 0;
               pleft = 0;
               ptype = 'fixed';   
            }
  
           jQuery( "a.ovl_fix_toggle" ).html(button_text);      
            jQuery("#overlay" ).css({top: ptop, left: pleft, position:ptype});     
           
           OverlaySetSize();
});

function OverlaySetSize() {
     var dim = overlay_getCookie('OverlayUserDim') ;
    if(JSINFO  && ! dim) {
       if(JSINFO['ol_width']) {
           jQuery( "#overlay" ).css('width',JSINFO['ol_width']); 
       }    
       if(JSINFO['ol_height']) {
           jQuery( "#overlay" ).css('height',JSINFO['ol_height']);            
        }
   }
   else if(dim) {
        var dim_ar = dim.split('#');
        var h = parseInt(dim_ar[0]);
        var w = parseInt(dim_ar[1]);
        if(w < 200) w = 200;
        jQuery("#overlay" ).css({'width': w, 'height': h});      
   }

}

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
 
