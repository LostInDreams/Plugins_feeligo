<?php
/**
 * Feeligo
 *
 * @category   Feeligo
 * @package    Feeligo_Api
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    Feeligo_Model_Adapter_User
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/interfaces/users_selector.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/lib/exceptions/not_found_exception.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../adapter/user.php'); 
 
class Feeligo_Model_Selector_UserFriends implements FeeligoUsersSelector {
 
  public function __construct($user_adapter) {
    $this->_user_adapter = $user_adapter;
   
  }
  private function _user(){
    return $this->_user_adapter->user;
  }
 
  public function find($id, $throw = true) {
    $usr=$this->_user();
    if (is_friends($usr['ID'] ,$id) && ($user = getProfileInfo($id)) != false && $user['ID'] == $id) {
      return new FeeligoDolUser($user);
    }
    if ($throw) throw new FeeligoEntityNotFoundException('type', 'could not find '.'user'.' with id='.$id);
    return null;
  }
  
  public function find_all($ids) {     
    $collection = new FeeligoEntityElementCollectionSet('user');
    $usr=$this->_user();
    $ids = array_instersect($ids,getFriendIds($usr['ID']));
    if (sizeof($ids) > 0) {
      foreach($ids as $id) {
        $collection->add(new FeeligoDolUser(getProfileInfo($id), null));
      }
    }
  }
 
  public function all($limit = null, $offset = 0) {    
    $user=$this->_user();    
    $x=  $this->_collect_users($this->get_friends($user['ID'],$limit,$offset));   	
     return $x;
  }
  
  public function search($query, $limit = null, $offset = 0) {    
    $usr=$this->_user();
    return $this->_collect_users($this->get_friends($usr['ID'],$limit,$offset,false, $query));

  }
  
  private function _collect_users($users) {
    $collection = array();
    $usr=$this->_user();
    if (sizeof($users) > 0) {
      foreach($users as $user) {
        if ($user['ID']!=strval($usr['ID'])){
          $collection[]=(new FeeligoDolUser($user, null));
        }
      }
    }
    return $collection;
  }
  public function get_friends($person_id,$limit=null,$offset=0,$include=false,$search=false) {
    $oDb = new BxDolDb();
    
    $person_id = intval($person_id);

  	$sqlQuery = "
  		SELECT p.*
  		FROM `Profiles` AS p
  		LEFT JOIN `sys_friend_list` AS f1 ON (f1.`ID` = p.`ID` AND f1.`Profile` ='{$person_id}' AND `f1`.`Check` = 1)
  		LEFT JOIN `sys_friend_list` AS f2 ON (f2.`Profile` = p.`ID` AND f2.`ID` ='{$person_id}' AND `f2`.`Check` = 1)
  		WHERE 1
  		AND (f1.`ID` IS NOT NULL OR f2.`ID` IS NOT NULL)
  	";
  	if ($include!=false) {
  	  $ids = implode( ',', (array) $include );
  	  $sqlQuery.= "AND f1.ID IN ($ids) OR f2.ID IN ($ids)";
  	}
  	if ($limit !=null){
  	  $sqlQuery.= "
  	  LIMIT {$limit}";
  	}
  	if ($offset !=0){
  	  $sqlQuery.= " OFFSET {$offset}";
  	}
  	if ($search!=false){
  	  $sqlQuery.= "
  	  'NickName' LIKE '%" .$search. "%'";
  	} 	
  	$vProfiles = $oDb->getAll($sqlQuery);    	    	
    return $vProfiles;
  }
 
}