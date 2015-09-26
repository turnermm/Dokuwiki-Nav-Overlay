<?php
$meta['admin'] = array('onoff');
$meta['profile'] = array('onoff');
$meta['recent'] = array('onoff');
$meta['revisions'] = array('onoff');
$meta['backlink'] = array('onoff');
$meta['login'] = array('onoff');
$meta['index'] = array('onoff');
$meta['media'] = array('onoff');
$meta['register'] = array('onoff');
$meta['edit'] = array('onoff');
$meta['always'] = array('onoff');
$meta['page'] = array('string');
$meta['menutype']  = array('multichoice','_choices' => array('user','site','page'));
$meta['nsoverlays'] = array('string');
$meta['tools']  = array('multicheckbox','_choices' => array('login','admin','revisions','profile'));
$meta['width'] = array('string');
$meta['height'] = array('string');
$meta['actionlinktype']  = array('multichoice','_choices' => array('button','link','dokuwiki'));

