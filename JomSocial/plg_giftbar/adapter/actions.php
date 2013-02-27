<?php

require_once(str_replace('//','/',dirname(__FILE__).'/').'../common/interfaces/actions_selector.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'action.php');
class Feeligo_Model_Selector_Actions implements FeeligoActionsSelector {
  function create($data){
    #var_dump($data);
    $args=$this->_arguments($data);
    $message= $data['message']["i18n"][$this->_language($data)];
    $action= $this->_get_action($args,$message); 
    $image=$data['media'][0]['sizes']['120x144'];
    $message = $data['text'];
    $description = "";
    $this->_write_wall($args[0], $args[2], $action, $image,$args[3]);
   
    return $args[3];
  }
  
  function _language($data){
    $language='en';
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
  
  function _get_action ($args,$message){
    $subject = CFactory::getUser($args[0]);
    $indirect_object= CFactory::getUser($args[2]);
    
    $message= str_replace("\${subject}",'<a href ="'.CRoute::_('index.php?option=com_community&view=profile&userid=' .($args[0])).'" data-flg-role="link" data-flg-type="user" data-flg-id="'.$args[0].'" data-flg-source="action"> '.$subject->username.'</a>',$message);
    $message= str_replace("\${direct_object}",'<span data-flg-role="link" data-flg-type="gift" data-flg-id="'.$args[3].'" data-flg-source="action">'.$args[1].'</span>',$message);
    $message= str_replace("\${indirect_object}",'<a href ="'.CRoute::_('index.php?option=com_community&view=profile&userid='.($args[2])).' data-flg-role="link" data-flg-type="user" data-flg-id="'.$args[2].'" data-flg-source="action""> '.$indirect_object->username.'</a>',$message);
    return $message;
  }
  
   function _write_wall($subject_id, $indirect_object_id, $title, $image,$gift_id) {
    CFactory::load ( 'libraries', 'activities' );
    $act = new stdClass();
 		$act->cmd 		= 'gift.add';
 		$act->actor 	= $subject_id;
 		$act->target 	= $indirect_object_id;
 		$act->title		= $title;
 		$act->content	= "<img alt='flg_feed' src='".$image."' data-flg-role='link' data-flg-type='gift' data-flg-id='".$gift_id."' data-flg-source='action' /> ";
 		$act->app		= 'gift';
 		$act->cid		= 0;


 		CActivityStream::add($act);

  }
  

}
?>
