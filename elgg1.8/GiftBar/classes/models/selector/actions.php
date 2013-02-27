<?php

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../lib/gift.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../adapter/action.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../common/interfaces/actions_selector.php'); 

class Feeligo_Model_Selector_Actions implements FeeligoActionsSelector {
  function create($data){
    $args=$this->_arguments($data);
    $image=$data['media'][0]['sizes']['240x288'];    
    $id=save_gift($args[1],$args[0],$args[2],$data['message']["i18n"][$this->_language($data)],$image,$args[3]);
    $action_adapter= new Feeligo_Model_Adapter_Action($args[3]);
    return $action_adapter;
  }
  
  function _language($data){
    $language= get_current_language();
    if ($language != 'en'|| $language != 'fr'){
      $language='en';
    }
    return $language;
  }
  function _arguments($data){
    $args=array();
    $valency= intval($data['verb']['valency']);
    for ($i=0;$i<$valency; $i++) {
      switch($data['arguments'][$i]['properties']['function']){
        case "subject":
          $args[0]=$data['arguments'][$i]['properties']['id'];
          break;
        case "direct_object":
          $args[1]=$data['arguments'][$i]['properties']['name'];
          $args[3]=$data['arguments'][$i]['properties']['id'];
          break;
        case "indirect_object":
          $args[2]=$data['arguments'][$i]['properties']['id'];
          break;
      }
    }
    return $args;
  }
  
}
?>
