<?php

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/interfaces/actions_selector.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../adapter/action.php');
class Feeligo_Model_Selector_Actions implements FeeligoActionsSelector {
  function create($data){
    #var_dump($data);
    $args=$this->_arguments($data);
    $message= $data['message']["i18n"][$this->_language($data)];
    $action= $this->_get_action($args,$message); 
    $image=$data['media'][0]['sizes']['120x144'];
    $message = $data['text'];
    
    $content = '<div class="wall-post-media">';
    $content .= '<div class="wall-pm-thumbnail" style="width:120px; height:144px;">';
    $content .= '<img class="wall-pm-thumbnail" src="http://dolphin.feeligo.com/templates/tmpl_uni/images/spacer.gif" style="width:120px; background-image:url('.$image.'); height:144px; " title="'.$args[1].'" alt="'.$args[1].'" data-flg-role="link" data-flg-type="gift" data-flg-id="'.$args[3].'" data-flg-source="action" />
        </div>
        <i>"'.$message.'"</i></br></br></br></br>
        '.$action.'
        <div class="wall-clear">&nbsp;</div>
    </div>';
    if (BxDolRequest::serviceExists('wall', 'update_handlers')){
      $this->_write_wall($args[2],$args[0], $content, $action, $args[1]);
    }
    
    $action_adapter= new Feeligo_Model_Adapter_Action($args[3]);
    return $action_adapter;
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
    $subject = getProfileInfo($args[0]);
    $indirect_object= getProfileInfo($args[2]);
    
    $message= str_replace("\${subject}",'<a href ="'.getProfileLink($args[0]).'" data-flg-role="link" data-flg-type="user" data-flg-id="'.$args[0].'" data-flg-source="action"> '.$subject['NickName'].'</a>',$message);
    $message= str_replace("\${direct_object}",'<span data-flg-role="link" data-flg-type="gift" data-flg-id="'.$args[3].'" data-flg-source="action">'.$args[1].'</span>',$message);
    $message= str_replace("\${indirect_object}",'<a href ="'.getProfileLink($args[2]).' data-flg-role="link" data-flg-type="user" data-flg-id="'.$args[2].'" data-flg-source="action""> '.$indirect_object["NickName"].'</a>',$message);
    return $message;
  }
   function _write_wall($owner_id, $object_id, $content, $title, $description) {
    $oDb = new BxDolDb();
    //$query = "INSERT INTO 'bx_wall_events' ('owner_id', 'object_id', 'type', 'content', 'title', 'description', 'date') VALUES ('$owner_id', '$object_id', 'wall_common_photos', 'content', '$title', '$description', '".time()."');";
     $query = "INSERT INTO `dolphin`.`bx_wall_events` (`id`, `owner_id`, `object_id`, `type`, `action`, `content`, `title`, `description`, `date`) VALUES (NULL, '$owner_id', '$object_id', 'wall_common_link', '', '$content', '$title', '$title', '".time()."');";
    $res = $oDb->query($query);
    
    //var_dump($res);
    return "";
  }
  

}
?>
