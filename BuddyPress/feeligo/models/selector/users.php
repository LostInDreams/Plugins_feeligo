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
 * @package    FeeligoWordpressUser
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/interfaces/users_selector.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/lib/exceptions/not_found_exception.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../adapter/user.php');
 
class Feeligo_Model_Selector_Users implements FeeligoUsersSelector {
 
  public function __construct() {
   
  }
  
  
  public function find($id, $throw = true) {
    // look for Elgg user with id = $id
    if (($user = get_user_by('id',$id)) != false && $user->id == $id) {
      // return an Adapter adapting the Elgg User
      return new FeeligoWordpressUser($user);
    }
    if ($throw) throw new FeeligoEntityNotFoundException('type', 'could not find '.'user'.' with id='.$id);
    return null;
  }
  
  public function find_all($ids) { 
    return $this->_collect_users(get_users(array('include' => $ids)));
  }
 
  public function all($limit = null, $offset = 0) {
     return $this->_collect_users(get_users(array(
      'offset' => $offset,
      'number' =>  $limit
     )));
  }
  
  public function search($query, $limit = null, $offset = 0) {
    global $CONFIG;
    return $this->_collect_users(get_users(array(
      'search' => '*'.$query.'*',
      'offset' => $offset, 
      'number' =>  $limit
    )));

  }
  
  private function _collect_users($users) {
    $collection = new FeeligoEntityElementCollectionSet('user');
    if (sizeof($users) > 0) {
      foreach($users as $user) {
        $collection->add(new FeeligoWordpressUser($user, null));
      }
    }
    return $collection;
  }
 
}
