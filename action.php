<?php
    /**
          * @author   Myron Turner <turnermm02@shaw.ca>
     */
     
    if(!defined('DOKU_INC')) die();
     
     
    class action_plugin_overlay extends DokuWiki_Action_Plugin {
    
        function register(Doku_Event_Handler $controller) {             
            $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, 'set_admin');
            $controller->register_hook('TPL_ACT_RENDER', 'AFTER',  $this, 'print_overlay', array('after'));
           $controller->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'action_link');    
        }
        
        function set_admin(&$event, $param) {
            global $JSINFO, $ACT;         
            
            if($this->getConf('always')) {
                $JSINFO['overlay'] = 1;
            }
            else {
                $exclusions = array('admin','profile','recent','revisions','backlink','login','index','media','register','edit');
                $regex = "";
                foreach($exclusions as $xcl) {
                    if($this->getConf($xcl)) {
                     $regex .= "$xcl|" ;
                     }
                }
              $regex = trim($regex, '|');            
             $JSINFO['overlay'] = preg_match("/" . $regex ."/",$ACT);      
           }
        }

        function print_overlay(&$event, $param) {        
         $insert =  p_wiki_xhtml('wiki:nav');  
$text = <<<TEXT
       <div id='overlay'><div  class = "close">
        <a href="javascript:jQuery('#overlay').toggle();void(0);"  rel="nofollow" title="close">Close</a>
        </div> $insert</div>
TEXT;
          echo $text;
      //   echo '<li><a href="javascript:jQuery(\'#overlay\').toggle();void(0);"  rel="nofollow"   title="Index">Index</a></li>';
        }
        
   function action_link(&$event, $param)
    {
        $event->data['items']['overlay'] = '<li><a href="javascript:jQuery(\'#overlay\').toggle();void(0);"  rel="nofollow"   title="Index">Index</a></li>';

    }        
    }

