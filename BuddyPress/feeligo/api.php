<?php

require_once(str_replace('//','/',dirname(__FILE__).'/').'common/lib/api.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/adapter/user.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/selector/users.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/selector/actions.php');
 
class FeeligoWordPressApi extends FeeligoApi{

   /* constructor
   * should store an instance of FeeligoWordpressCommunity
   * to be returned when the Community is requested
   */
  public function __construct() {
    global $bp;

    parent::__construct();
    // store the viewer
    $this->_adapter_viewer = new FeeligoWordpressUser(get_user_by('id',$bp->loggedin_user->userdata->ID));

    // store the subject
    $this->_adapter_subject = null;
    if ($bp->displayed_user->userdata->ID != null) {
      $u_subj =new FeeligoWordpressUser((get_user_by('id',$bp->displayed_user->userdata->ID))) ;
      // ensure the subject is not the same User as the viewer
      if ($this->_adapter_viewer->id()!=$u_subj->id()) { //attention changer isSelf
        $this->_adapter_subject = $u_subj;
      }
    }

  }

  public function community(){
    return $this->_community;
  }
 
  public function has_viewer(){
    global $bp;
    $adpt_viewer = $bp->loggedin_user->userdata->ID;
    if ($adpt_viewer != null){
      return true;
    }
    else{
      return false;
    }
  }

  public function has_subject(){
    global $bp;
    $adpt_viewer = $bp->loggedin_user->userdata->ID;
    $adpt_subject = $bp->displayed_user->userdata->ID;
    if ($adpt_subject != null && $adpt_viewer!=$adpt_subject ){
      return true;
    }
    else{
      return false;
    }
  }
  /* TODO: include this function as-is
   * (Singleton pattern)
   */
  public static function _() {
    if( is_null(self::$_instance) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  
  public function viewer() {
    return $this->_adapter_viewer;
  }
    public function subject() {
    error_log("SUJET : ".$this->_adapter_subject->name());
    return $this->_adapter_subject;
  }

  
  public function users() {
    if (!isset($this->_users)) {
      $this->_users = new Feeligo_Model_Selector_Users();
    }
    return $this->_users;
  }
  
   /*
   * Accessor to the Actions adapter
   */
  public function actions() {
    if (!isset($this->_actions)) {
      $this->_actions = new Feeligo_Model_Selector_Actions();
    }
    return $this->_actions;
  }
}
?>
