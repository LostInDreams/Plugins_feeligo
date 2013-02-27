<?php
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/interfaces/user_adapter.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../selector/userfriends.php');

class FeeligoWordpressUser implements FeeligoUserAdapter {


  /* constructor stores a reference to the adapted User
   */
  public function __construct($bp_user) {
    $this->user = $bp_user;
    // you can access the adapted $user by calling $this->user() from now on
  }
 public function id() {
    return $this->user->ID;
  }
  
  public function name() {
    return $this->user->user_nicename;
  }
  
  public function username() {
    return $this->user->user_login;
  }
  
  public function link() {
    $match=array();
    preg_match("/href=['|\"](.*?)['|\"]/",bp_core_get_userlink($this->user->ID),$match);
    return sizeof($match) > 0 ? html_entity_decode($match[1]) : null;
  }
  
  
  public function picture_url(){
    $match=array();
    preg_match("/src=['|\"](.*?)['|\"]/",get_avatar($this->user->userdata->ID,55),$match);
    return sizeof($match) > 0 ? html_entity_decode($match[1]) : null;
  }

  /* TODO: returns a FeeligoUserSelector which
   * allows to access the $user's friends
   * (for the moment, return null)
   */
  public function friends_selector(){
    return new Feeligo_Model_Selector_UserFriends($this);
  }

}

?>
