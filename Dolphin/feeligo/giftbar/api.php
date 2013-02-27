<?php

require_once(str_replace('//','/',dirname(__FILE__).'/').'common/lib/api.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/adapter/user.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/selector/users.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/selector/actions.php');
 
class FeeligoDolApi extends FeeligoApi{

   /* constructor
   * should store an instance of FeeligoWordpressCommunity
   * to be returned when the Community is requested
   */
  public function __construct() {
    parent::__construct();
    // store the viewer
    $this->_adapter_viewer = new FeeligoDolUser (getProfileInfo(getLoggedid()),false);
    // store the subject
    $profileID = getID( $_GET['ID'] );

    if ($_GET['ID']!= NULL) {
      $u_subj =new FeeligoDolUser (getProfileInfo(getID( $_GET['ID'] )),true) ;

      // ensure the subject is not the same User as the viewer
      if ($this->_adapter_viewer->id()!=$u_subj->id()) { //attention changer isSelf
        $this->_adapter_subject = $u_subj;
      }
      else{
        $this->adapter_subject = null;
      }
    }
  }

 
  public function has_viewer(){
  return isLogged();
  }

  public function has_subject(){
    if ($_GET['ID'] !=null && $_GET['ID']!="feeligo-api"){
      if (getId($_GET['ID']) != getLoggedId()){
        return true;
      }
      else{
        return false;
      }
    }
    else {
      return false;
    }
   
  }
  public function viewer() {
    return $this->_adapter_viewer;
  }
  
  public function subject() {
    return $this->_adapter_subject;
  }


  /* TODO: returns an instance of FeeligoRiriSelectorUsers
   * which will allow to access the users of the Community
   * (for the moment, return null)
   */
  public function users(){
    if (!isset($this->_users)) {
      $this->_users = new Feeligo_Model_Selector_Users();
    }
    return $this->_users;
  }
  public function actions() {
     if (!isset($this->_actions)) {
       $this->_actions = new Feeligo_Model_Selector_Actions();
     }
     return $this->_actions;
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
}
?>
