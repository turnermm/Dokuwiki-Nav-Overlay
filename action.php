<?php
    /**
          * @author   Myron Turner <turnermm02@shaw.ca>
     */
     
    if(!defined('DOKU_INC')) die();
     
     
    class action_plugin_overlay extends DokuWiki_Action_Plugin {
    
        function register(Doku_Event_Handler $controller) {             
            $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, 'set_admin');
        }
        
        function set_admin(&$event, $param) {
            global $JSINFO, $ACT;         
         
            $exclusions = array('admin','profile','recent','revisions','backlink','login','index','media','register');
            $regex = "";
            foreach($exclusions as $xcl) {
                if($this->getConf($xcl)) {
                 $regex .= "$xcl|" ;
                 }
            }
          $regex = trim($regex, '|');  
         //  $JSINFO['overlay'] = preg_match("/admin|profile|recent|revisions|backlink|login|index|media|register/",$ACT);      
           $JSINFO['overlay'] = preg_match("/" . $regex ."/",$ACT);      
        }        
    }

