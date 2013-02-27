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
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../common/interfaces/user_adapter.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../selector/user_friends.php');
 
class Feeligo_Model_Adapter_User implements FeeligoUserAdapter {
 
  public function __construct($elgg_user, $crgfv = null) {
    $this->user = $elgg_user;
  }
  
  
  /**
   * Whether the adaptee actually exists in the community (not a new object and not an invalid ID)
   */
  public function user_exists() {
    return ($this->user !== null );
  }
  
  /**
   * Accessors for the adaptee's properties
   */
  public function id() {
    return $this->user->guid;
  }
  
  public function name() {
    return $this->user->name;
  }
  
  public function username() {
    return $this->user->username;
  }
  
  public function link() {
    return $this->user->getURL();
  }
  
  public function crgfv() {
    return $this->crgfv;
  }

  public function picture_url() {
    return $this->user->getIcon('small');
  }
  
  
  //TODO en dernier

  public function friends_selector() {
    return new Feeligo_Model_Selector_UserFriends($this);
  }
  


}
