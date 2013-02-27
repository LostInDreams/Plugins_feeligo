<?php
require_once(str_replace('//','/',dirname(__FILE__).'/').'classes/api.php'); 
function feeligo_init() { 

//  elgg_register_class('Feeligo', elgg_get_plugins_path() . 'feeligo/classes/Feeligo.php');
  #var_dump(get_loggedin_user()->getIcon('small'));
  if ( get_loggedin_user()!= NULL){
    extend_view('metatags', 'feeligo/head');
    extend_view('footer/analytics', 'feeligo/js');

  }

    
  
}

register_elgg_event_handler('init', 'system', 'feeligo_init');  
    
?>
