<?php
    /**
      * @author   Myron Turner <turnermm02@shaw.ca>
      * developed with suggestions from torpedo <dgtorpedo@gmail.com>
     */
     
    if(!defined('DOKU_INC')) die();
     
     
    class action_plugin_overlay extends DokuWiki_Action_Plugin {
    
        function register(Doku_Event_Handler $controller) {             
           $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, 'set_admin');
           $controller->register_hook('TPL_ACT_RENDER', 'AFTER',  $this, 'print_overlay', array('after'));
           $controller->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'action_link', array('page'));    
           $controller->register_hook('TEMPLATE_SITETOOLS_DISPLAY', 'BEFORE', $this, 'action_link', array('site'));    
           $controller->register_hook('TEMPLATE_USERTOOLS_DISPLAY', 'BEFORE', $this, 'action_link', array('user'));    
           $controller->register_hook('TEMPLATE_OVLTOOLS_DISPLAY', 'BEFORE', $this, 'overlay_tools', array('user'));
           $controller->register_hook('MENU_ITEMS_ASSEMBLY', 'AFTER', $this, 'addsvgbutton', array());
        }
        
        function set_admin(&$event, $param) {
            global $JSINFO, $ACT;         
            
            if(is_array($ACT)) {
                 $ACT = act_clean($ACT);
            }
                         
            if($this->getConf('always')){   
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
             $regex .= '|preview';             
             $JSINFO['overlay'] = preg_match("/" . $regex ."/",$ACT);      
           }
         
            $JSINFO['ol_left'] = 0;
            $JSINFO['ol_top'] = 0;
            $JSINFO['position'] = 'fixed';
            
           $height = preg_replace('/[^0-9]+/', '', $this->getConf('height'));
           $width = preg_replace('/[^0-9]+/', '', $this->getConf('width'));           

           if($height) {          
               $JSINFO['ol_height'] = $height;
           }
           if($width) {               
               $JSINFO['ol_width'] = $width ;
           }
        }

        function print_overlay(&$event, $param) {        
            global $ID, $INFO;
            $page = "";
            
           $tools = $this->getConf('tools');
           if(!empty($tools)) {
               $choices = explode(',',$tools);
               $action = tpl_action($choices[0], true, '', 1, '<span class = "oltools-left">', '</span>', ''); 
               for($i = 1; $i<count($choices); $i++)
                   $action .= tpl_action($choices[$i], true, '', 1, '<span class = "oltools-right">', '</span>', ''); 
               }    
            
           if($INFO['userinfo']) {
              $wiki_page = 'overlay:' . $INFO['client'];             
               $file = wikiFN($wiki_page); 
               if(file_exists($file) && auth_quickaclcheck('overlay:' . $INFO['client']) >= AUTH_READ) {  // check ACL for overlay page                                               
                   $page = $wiki_page;
               }
               else {
                   $wiki_page = $INFO['client'] . ':overlay';
                   $file = wikiFN($wiki_page);                 
                   if(file_exists($file) && auth_quickaclcheck($INFO['client'] . "overlay") >= AUTH_READ) {  // check ACL for overlay page                                                   
                       $page = $wiki_page;
                   }               
               }
           }
           if(!$page) { 
           $namespaces = $this->getConf('nsoverlays');  // check for alternate overlay pages         
           $alternates = explode(',',$namespaces);
            
            $ns = getNS($ID);
            foreach($alternates as $nmsp) {
                if($ns ==  trim($nmsp,': ')) {
                  $wikiFile = wikiFN("$ns:overlay"); 
                  if(file_exists($wikiFile) && auth_quickaclcheck("$ns:overlay") >= AUTH_READ) {  // check ACL for overlay page
                      $page = "$ns:overlay";    // if an alternate exists put it in $page
                      break;
                  }
                }
            }
            }
            if(!$page) {
              foreach($alternates as $nmsp) {
                  if(strrpos($nmsp,'*')) {
                    $nmsp = trim($nmsp,'* ');
                    if(preg_match("#^$nmsp#",$ns)) {
                         $wikiFile = wikiFN("$nmsp:overlay");
                         if(file_exists($wikiFile) && auth_quickaclcheck("$nmsp:overlay") >= AUTH_READ)  {  // check ACL for overlay page
                             $page = "$nmsp:overlay";    // if a parent namespace alternate exists put it in $page
                             break;
                        }                         
                    }
                }
          }
        }  
            
        if(!$page) $page = trim($this->getConf('page'));          
        if(!$page) return;
        $insert =  p_wiki_xhtml($page);  
        if(!$insert) return;
        $close = trim($this->getLang('close'));
        $detach = trim($this->getLang('detach'));
        $fixed = trim($this->getLang('fix_title'));
        $action .=' <a href="javascript:void(0);"  class="ovl_fix_toggle" title ="' .$fixed .'">' . $detach. '</a>';
$text = <<<TEXT
       <div id='overlay'><div  class = "close">$action
        <a href="javascript:jQuery('#overlay').toggle();void(0);"  rel="nofollow" title="$close">$close</a>
        </div> $insert</div>
        
TEXT;
          echo $text;
     
        }
        
     /**
      *  The three types of action links all use the same html
      *  They are treated differently according to which event is calling this callback
      *  $param holds the link type for the event to be processed; it has to match the menu type
      *  configured for the plugin  (page, site, user)
     */     
   function action_link(&$event, $param)
    {
       global $ID;
        if (auth_quickaclcheck($ID) < AUTH_READ) return;     //no access to page, suppress index link
        $type = $this->getConf('menutype');
        if($type !=$param[0]) return;
        $name = $this->getLang('toggle_name');         
        $edclass = 'ovl' . $type .'tool';
         $title = $name;
      
         $link = '<a href="javascript:jQuery(\'#overlay\').toggle();void(0);" class="' . $edclass . '"  rel="nofollow"   title="' .$title. '"><span>'. $name .'</span></a>';
        if($param[0] == 'page') {
            $link = '<li class = "dwedit">' . $link .'</li>';
        }
        else { 
            $link = '<span class = "ovltitle">' . $link  .'</span>';          
        }

    $event->data['items'] = array_slice($event->data['items'], 0, 1, true) +
            array('overlay' => $link) + array_slice($event->data['items'], 1, NULL, true);
    }    
    public function addsvgbutton(Doku_Event $event) {      
   
        if($event->data['view'] == 'any') {
           $this->overlay_tools($event, array('any'));  
          return;           
        }      
       $type = $this->getConf('menutype');    
       if($event->data['view'] != $type ) return;             
       $btn = $this->getLang('toggle_name');
    
       if(!$btn) $btn = 'Overlay';           
       array_splice($event->data['items'], -1, 0, [new \dokuwiki\plugin\overlay\MenuItem($btn)]);
}    

  function overlay_tools(&$event, $param) {    
    $name = $this->getLang('toggle_name');
    $type = $this->getConf('toggletype');
     if($type == 'link') {
         if($param[0] == 'any') {
             echo  '<a href="javascript:jQuery(\'#overlay\').toggle();void(0);"  class="overlaytools" rel="nofollow"   title="' .$name. '">'. $name.'</a>';
             return;
         }
          $event->data['items']['overlay'] = '<a href="javascript:jQuery(\'#overlay\').toggle();void(0);"  class="overlaytools" rel="nofollow"   title="' .$name. '">'. $name.'</a>';
        }
        elseif($type == 'button') {
            if($param[0] == 'any') {
                  echo '<button class="overlaytools" onclick="jQuery(\'#overlay\').toggle();void(0);">Index</button>';
                  return;
            }
          $event->data['items']['overlay'] = '<button class="overlaytools" onclick="jQuery(\'#overlay\').toggle();void(0);">Index</button>';
        }  
   }  
}

