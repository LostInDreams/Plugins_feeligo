<?php

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/interfaces/actions_selector.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../adapter/action.php');
class Feeligo_Model_Selector_Actions implements FeeligoActionsSelector {
  function create($data){
    
    global $bp;
    $args=$this->_arguments($data);
    $message= $data['message']["i18n"][$this->_language($data)];
    $action= $this->_get_action($args,$message); 
    $image=$data['media'][0]['sizes']['240x288'];
    var_dump($image);
    $this-> remove_filters();
    $act= bp_activity_add( array( 
      'id' => false,
      'user_id' =>$args[0],
      'action' => $action,
      'content' => "<img alt='flg_feed' src='".$image."' data-flg-role='link' data-flg-type='gift' data-flg-id='".$args[3]."' data-flg-source='action' /> ",
      'component' => 'all', 
      'type' => 'activity_update', 

      'hide_sitewide' => false
    ) );
    $this-> remove_filters();
    $action_adapter= new Feeligo_Model_Adapter_Action($args[3]);
    return $action_adapter;
  }
  
  function _language($data){
    $language= bloginfo('language');
    if (strpos($language,'fr')==false){
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
  
  function _get_action ($args,$message){
    $subject = get_user_by('id', $args[0]);
    $indirect_object= get_user_by('id', $args[2]);
    preg_match("/href=['|\"](.*?)['|\"]/",bp_core_get_userlink($args[0]),$match);
    $subject_domain = sizeof($match) > 0 ? html_entity_decode($match[1]) : null;
    preg_match("/href=['|\"](.*?)['|\"]/",bp_core_get_userlink($args[2]),$match2);
    $indirect_object_domain = sizeof($match) > 0 ? html_entity_decode($match[2]) : null;
    
    $message= str_replace("\${subject}",'<a href ="'.$subject_domain.'" data-flg-role="link" data-flg-type="user" data-flg-id="'.$args[0].'" data-flg-source="action"> '.$subject->user_login.'</a>',$message);
    $message= str_replace("\${direct_object}",'<span data-flg-role="link" data-flg-type="gift" data-flg-id="'.$args[3].'" data-flg-source="action">'.$args[1].'</span>',$message);
    $message= str_replace("\${indirect_object}",'<a href ="'.$indirect_object_domain.' data-flg-role="link" data-flg-type="user" data-flg-id="'.$args[2].'" data-flg-source="action""> '.$indirect_object->user_login.'</a>',$message);
    return $message;
  }
  
  function remove_filters(){
    remove_filter( 'bp_get_activity_action',                'bp_activity_filter_kses');
    remove_filter( 'bp_get_activity_content_body',          'bp_activity_filter_kses');
    remove_filter( 'bp_get_activity_content',               'bp_activity_filter_kses');
    remove_filter( 'bp_get_activity_parent_content',        'bp_activity_filter_kses');
    remove_filter( 'bp_get_activity_latest_update',         'bp_activity_filter_kses');
    remove_filter( 'bp_get_activity_latest_update_excerpt', 'bp_activity_filter_kses');
    remove_filter( 'bp_get_activity_feed_item_description', 'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_activity_content_before_save',       'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_activity_action_before_save',        'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_activity_latest_update_content',     'wp_filter_kses', 1 );

    remove_filter( 'bp_get_activity_action',                'force_balance_tags' );
    remove_filter( 'bp_get_activity_content_body',          'force_balance_tags' );
    remove_filter( 'bp_get_activity_content',               'force_balance_tags' );
    remove_filter( 'bp_get_activity_latest_update',         'force_balance_tags' );
    remove_filter( 'bp_get_activity_latest_update_excerpt', 'force_balance_tags' );
    remove_filter( 'bp_get_activity_feed_item_description', 'force_balance_tags' );
    remove_filter( 'bp_activity_content_before_save',       'force_balance_tags' );
    remove_filter( 'bp_activity_action_before_save',        'force_balance_tags' );
    
  }

}

?>
