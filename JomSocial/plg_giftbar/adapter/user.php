<?php
/**
 * Feeligo_Mysite_User_Adapter
 *
 * this class implements the Adapter pattern to adapt the interface
 * of the local User model as expected by the Feeligo API
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../common/interfaces/user_adapter.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'users_friends.php');


/**
 * this file provides a skeleton implementation, modify to your needs.
 */
 
class Feeligo_Jomsocial_User_Adapter implements FeeligoUserAdapter {
  
  /**
   * constructor
   * expects an instance of your existing User model (if you have one)
   * or a database row, as the adaptee
   *
   * @param mixed $adaptee
   */
  public function __construct($adaptee) {
    $this->_adaptee = $adaptee;
    
  }
    
  /**
   * returns the unique identifier of the user
   *
   * @return string
   */
  public function id() {
    return $this->_adaptee->id;
  }
  /**
   * the user's display name
   *
   * human-readable name which is shown to other users
   *
   * @return string
   */
  public function name() {
    return $this->_adaptee->username;
  }
    
  /**
   * the URL of the user's profile page (full URL, not only the path)
   *
   * @return string
   */
  public function link() {
    return CRoute::_('index.php?option=com_community&view=profile&userid=' . $this->_adaptee->id);
  }
  
  /**
   * the URL of the user's profile picture
   *
   * @return string
   */
  public function picture_url() {
    return JURI::root().$this->_adaptee->_thumb;
  }

  /**
   * returns a FeeligoUsersSelector to select friends of this user
   *
   * @return FeeligoUsersSelector
   */
  public function friends_selector() {
    return new Feeligo_Jomsocial_User_Friends_Selector($this);
    
  }

}