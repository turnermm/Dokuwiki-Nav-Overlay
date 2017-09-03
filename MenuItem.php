<?php

namespace dokuwiki\plugin\overlay;

use dokuwiki\Menu\Item\AbstractItem;

/**
 * Class MenuItem
 *
 * Implements the PDF export button for DokuWiki's menu system
 *
 * @package dokuwiki\plugin\overlay
 */
class MenuItem extends AbstractItem {

    /** @var string do action for this plugin */
    protected $type = '';
    private  $btn_name;

    /** @var string icon file */  
     protected $svg = __DIR__ . '/screen-frame.svg';   //Icon source : https://www.flaticon.com/ license: http://creativecommons.org/licenses/by/3.0
     
    /**
     * MenuItem constructor.
     * @param string $btn_name (can be passed in from the  event handler)
     */
    public function __construct($btn_name = "") {
         parent::__construct();        
         $this->params['do']=""; 
         if($btn_name)  {
            $this->btn_name = $btn_name;     
         }               
         
    }

    /**
     * Get label from plugin language file
     *
     * @return string
     */
    public function getLabel() {        
        if($this->btn_name) return $this->btn_name;
    /* 
        if the button name has not been set up  in the constructor    
        you can get it now.
        Note:    In the current case the name is guaranteed by
        having been hard-coded in the event of a name not having been found        
     */
         $hlp = plugin_load('action', 'overlay');   
        return $hlp->getLang('btn_dw_edit');
       
        
    }
    
     public function getLink() {
         return 'javascript:jQuery("#overlay").toggle();void(0)';
     }
}
