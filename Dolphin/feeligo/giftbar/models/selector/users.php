<?php
/**
 * Feeligo
 *
 * @category   Feeligo
 * @package    Feeligo_Api
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @author     Marieke Gueye <tech@feeligo.com>
 */


require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/interfaces/users_selector.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/lib/exceptions/not_found_exception.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../adapter/user.php');
bx_import('BxDolDb');
 
class Feeligo_Model_Selector_Users implements FeeligoUsersSelector {
 
  public function __construct() {
   
  }
  
  
  public function find($id, $throw = true) {
    // look for Elgg user with id = $id
    if (($user = getProfileInfo($id)) != false && $user['ID'] == $id) {
      // return an Adapter adapting the Elgg User
      return new FeeligoDolUser($user);
    }
    if ($throw) throw new FeeligoEntityNotFoundException('type', 'could not find '.'user'.' with id='.$id);
    return null;
  }
  
  public function find_all($ids) { 
    $collection = new FeeligoEntityElementCollectionSet('user');
    if (sizeof($ids) > 0) {
      foreach($ids as $id) {
        $collection->add(new FeeligoDolUser(getProfileInfo($id), null));
      }
    }
    return $collection;
  }
 
  public function all($limit = null, $offset = 0) {
     return $this->_collect_users($this->get_users($limit,$offset));
  }
  
  public function search($query, $limit = null, $offset = 0) {
    return $this->_collect_users(get_users($limit,$offset,false,$query));

  }
  
  private function _collect_users($users) {
    $collection = new FeeligoEntityElementCollectionSet('user');
    if (sizeof($users) > 0) {
      foreach($users as $user) {
          $collection[]=new FeeligoDolUser($user, null);
      }
    }
    return $collection;
  }
  
  public function get_users($limit=null,$offset=0,$include=false,$search=false) {
    $oDb = new BxDolDb();
  	$sqlQuery = "
  		SELECT p.*
  		FROM `Profiles` AS p
  		WHERE 1
  	";
  	if ($include!=false) {
  	  $ids = implode( ',', (array) $include );
  	  $sqlQuery.= "AND p.ID IN ($ids)";
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
