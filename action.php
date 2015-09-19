<?php
    /**
          * @author   Myron Turner <turnermm02@shaw.ca>
     */
     
    if(!defined('DOKU_INC')) die();
     
     
    class action_plugin_overlay extends DokuWiki_Action_Plugin {
    
        function register(Doku_Event_Handler $controller) {             
           $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, 'set_admin');
           $controller->register_hook('TPL_ACT_RENDER', 'AFTER',  $this, 'print_overlay', array('after'));
           $controller->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'action_link', array('page'));    
           $controller->register_hook('TEMPLATE_SITETOOLS_DISPLAY', 'BEFORE', $this, 'action_link', array('site'));    
           $controller->register_hook('TEMPLATE_USERTOOLS_DISPLAY', 'BEFORE', $this, 'action_link', array('user'));    
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
           $height = preg_replace('/[^0-9]+/', '', $this->getConf('height'));
           $width = preg_replace('/[^0-9]+/', '', $this->getConf('width'));
         
           if($height) {          
               $JSINFO['ol_height'] = $height . "px";
           }
           if($width) {               
               $JSINFO['ol_width'] = $width ."px";
           }
          
        }

        function print_overlay(&$event, $param) {        
            global $ID;
            $page = "";
            
           $tools = $this->getConf('tools');
           if(!empty($tools)) {
               $choices = explode(',',$tools);
               $action = tpl_action($choices[0], true, '', 1, '<span class = "oltools-left">', '</span>', ''); 
               for($i = 1; $i<count($choices); $i++)
                   $action .= tpl_action($choices[$i], true, '', 1, '<span class = "oltools-right">', '</span>', ''); 
               }    
            
           $namespaces = $this->getConf('nsoverlays');  // check for alternate overlay pages         
           $alternates = explode(',',$namespaces);
            
            $ns = getNS($ID);
            foreach($alternates as $nmsp) {
                if($ns ==  trim($nmsp,': ')) {
                  $wikiFile = wikiFN("$ns:overlay"); 
                  if(file_exists($wikiFile)) {
                      $page = "$ns:overlay";    // if an alternate exists put it in $page
                      break;
                  }
                }
          }
      
            if(!$page) $page = trim($this->getConf('page'));          
        if(!$page) return;
        $insert =  p_wiki_xhtml($page);  
        if(!$insert) return;
        $close = trim($this->getLang('close'));
$text = <<<TEXT
       <div id='overlay'><div  class = "close">$action
        <a href="javascript:jQuery('#overlay').toggle();void(0);"  rel="nofollow" title="$close">$close</a>
        </div> $insert</div>
TEXT;
          echo $text;
     
        }
        
   function action_link(&$event, $param)
    {
        $type = $this->getConf('menutype');
        if($type !=$param[0]) return;
        $name = $this->getLang('toggle_name');
        $event->data['items']['overlay'] = '<li><a href="javascript:jQuery(\'#overlay\').toggle();void(0);"  rel="nofollow"   title="' .$name. '">'. $name.'</a></li>';

    }        
}

