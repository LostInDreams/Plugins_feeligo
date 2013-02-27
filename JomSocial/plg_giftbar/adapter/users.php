<?php
/**
 * Feeligo_Mysite_Users_Selector
 *
 * this class implements methods to find users in the
 * database and pass them as Adapters to the Feeligo API.
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../common/interfaces/users_selector.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'user.php');

/**
 * this file provides a skeleton implementation, modify to your needs.
 */
 
class Feeligo_Jomsocial_Users_Selector implements FeeligoUsersSelector {
    
  /**
   * returns an array containing all the Users
   *
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return FeeligoUserAdapter[] array
   */
  public function all($limit = null, $offset = 0) {
    $users =get_users($limit,$offset);
    return _collect_users($users);
  }
 
  /**
   * finds a specific User by its id
   *
   * @param mixed $id argument for the SQL id='$id' condition
   * @return FeeligoUserAdapter
   */
  public function find($id, $throw = true) {
    $user	= CFactory::getUser($id);
    if ($user->username !=null) {
      return new Feeligo_Jomsocial_User_Adapter($user);
    }
    if ($throw) throw new FeeligoEntityNotFoundException('type', 'could not find '.'user'.' with id='.$id);
    return null;
    
    
  }
 
  /**
   * finds a list of Users by their id's
   *
   * @param mixed array $ids
   * @return FeeligoUserAdapter[] array
   */
  public function find_all($ids) {
    $users = get_users(null,0,$ids);
    return _collect_users($users);
  }
    
  /**
   * returns an array containing all the Users whose name matches the query
   *
   * @param string $query the search query, argument to a SQL LIKE '%$query%' clause
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return FeeligoUserAdapter[] array
   */  
  public function search($query, $limit = null, $offset = 0) {
    $users =get_users($limit,$offset,false,$query);
    return _collect_users($users);
  }
  
  private function _collect_users($users) {
    $collection = array();
    if (sizeof($users) > 0) {
      foreach($users as $user) {
        $collection[]=new Feeligo_Jomsocial_User_Adapter($user, null);
      }
    }
    return $collection;
  }
  
  public function get_users($limit=null,$offset=0,$include=false,$search=false) {
    $db		= JFactory::getDBO();
		
		$query	= "SELECT u.* FROM " . $db->nameQuote( '#__users')." AS u
		WHERE 1 ";
		
  	if ($include!=false) {
  	  $ids = implode( ',', (array) $include );
  	  $query.= "AND userid IN ($ids)";
  	}
  	if ($limit !=null){
  	  $query.= "
  	  LIMIT {$limit}";
  	}
  	if ($offset !=0){
  	  $query.= " OFFSET {$offset}";
  	}
  	if ($search!=false){
  	  $query.= "
  	  'username' LIKE '%" .$search. "%'";
  	}
  	$db->setQuery( $query );
		$result	= $db->loadObjectList();
  }
  
}