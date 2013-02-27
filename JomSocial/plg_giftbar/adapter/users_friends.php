<?php
/**
 * Feeligo_Mysite_User_Friends_Selector
 *
 * this class implements methods to find friends of a given user in
 * the database and pass them as Adapters to the Feeligo API.
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../common/interfaces/users_selector.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'user.php');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');

/**
 * this file provides a skeleton implementation, modify to your needs.
 */
 
class Feeligo_Jomsocial_User_Friends_Selector implements FeeligoUsersSelector {

  /**
   * constructor
   *
   * expects an instance of FeeligoUserAdapter wrapping the user
   * whose friends this class gives access to
   *
   * @param FeeligoUserAdapter $user_adapter
   */
  function __construct($user_adapter) {
    $this->_user  = $user_adapter->_adaptee;    
    $this->_friends_ids =explode(',',$this->_user->_friends);
  }  
 
  /**
   * returns an array containing all the user's friends
   *
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return FeeligoUserAdapter[] array
   */
  public function all($limit = null, $offset = 0) {
    $users = $this->get_users($limit,$offset,$this->_friends_ids);
    return $this->_collect_users($users);
  }
 
  /**
   * finds a specific friend by its id
   *
   * @param mixed $id argument for the SQL id='$id' condition
   * @return FeeligoUserAdapter
   */
  public function find($id, $throw = true) {
    if ($this->_user->isFriendWith( $id )){
      $user	= JFactory::getUser($id);
      return new FeeligoUserAdapter($user,null);
    }
    if ($throw) throw new FeeligoEntityNotFoundException('type', 'could not find '.'user'.' with id='.$id);
    return null;
  }
 
  /**
   * finds a list of friends by their id's
   *
   * @param mixed array $ids
   * @return FeeligoUserAdapter[] array
   */
  public function find_all($ids) {
    $friendids = array_intersect($ids, $this->_friends_ids);
    $users = $this->get_users(null,0,$this->_friends_ids);
    return $this->_collect_users($users); 
  }
    
  /**
   * returns an array containing all the friends whose name matches the query
   *
   * @param string $query the search query, argument to a SQL LIKE '%$query%' clause
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return FeeligoUserAdapter[] array
   */  
  public function search($query, $limit = null, $offset = 0) {
    //TODO: implement this
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
    $db		=& JFactory::getDBO();
		
		$query	= "SELECT u.userid FROM" . $db->nameQuote( '#__community_users' )." AS u
		WHERE 1 ";
		
  	if ($include!=false) {
  	  //if (sizeof($include)==1) {
  	    #$query.= "AND u.userid = $include[0]";
  	  #}
  	  #else{
  	    $ids = implode( ',', (array) $include );
    	  $query.= "AND u.userid IN ($ids)";
  	  #}
  	  
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
		$results	= $db->loadAssocList();
		$collection = array();
		foreach($results as $result) {			
      $collection[]=CFactory::getUser($result["userid"]);
    }		
        
		return $collection;
		
  }
  
}