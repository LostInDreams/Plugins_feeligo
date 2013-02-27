<?php
elgg_register_event_handler('init', 'system', 'feeligo_init'); 
 
require_once(str_replace('//','/',dirname(__FILE__).'/').'classes/api.php'); 
function feeligo_init() { 
  //elgg_register_class('Feeligo', elgg_get_plugins_path() . 'feeligo/classes/Feeligo.php');
  elgg_register_class('Feeligo_Model_Gift', elgg_get_plugins_path() . 'feeligo/classes/models/gift.php');
  
  elgg_register_event_handler('pagesetup', 'system', 'load_bar');
	#add_to_river('example','create',elgg_get_logged_in_user_entity()->guid,51);
  elgg_register_entity_type ('object', 'gifts');
}
function load_bar(){
  if ( elgg_get_logged_in_user_entity() != NULL){
    elgg_extend_view('page/elements/head', 'feeligo/head');
    elgg_extend_view('page/elements/foot', 'feeligo/foot');
    
  }

}

    
?>
