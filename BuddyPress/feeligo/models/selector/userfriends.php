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
    // look for Buddypress user with id = $id
    if ( friends_check_friendship($this->_user()->ID,$id) && ($user = get_user_by('id',$id)) != false && $user->ID == $id) {
      // return an Adapter adapting the Buddypress User
      return new Feeligo_Model_Adapter_User($user);
    }
    if ($throw) throw new FeeligoEntityNotFoundException('type', 'could not find '.'user'.' with id='.$id);
    return null;
  }
  
  public function find_all($ids) { 
    return $this->_collect_users( get_users(array(
       'include' => array_instersect($ids,friends_get_friend_user_ids($this->_user()->ID))
    )));
  }
 
  public function all($limit = null, $offset = 0) {
    $x=  $this->_collect_users(get_users(array(
      'include' => friends_get_friend_user_ids($this->_user()->ID), 
      'offset' => $offset, 
      'number' =>  $limit
     ))); 
     return $x;
  }
  
  public function search($query, $limit = null, $offset = 0) {
    
    return $this->_collect_users(get_users(array(
      'search' => '*'.$query.'*',
      'include' => friends_get_friend_user_ids($this->_user()->ID), 
      'offset' => $offset, 
      'number' =>  $limit
    )));

  }
  
  private function _collect_users($users) {
    $collection = array();
    if (sizeof($users) > 0) {
      foreach($users as $user) {
        if ($user->ID!=strval($this->_user()->ID)){
          $collection[]=new FeeligoWordpressUser($user, null);
        }
      }
    }
    return $collection;
  }
 
}
