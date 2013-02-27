<?php
/**
 * Feeligo_Mysite_Api
 *
 * this file provides a skeleton implementation of the Api adaptation class.
 */

/**
 * Feeligo configuration settings
 */


 
/**
 * require the SDK's built-in FeeligoApi class
 *
 * don't forget to modify the path according to your directory structure
 */
require_once(str_replace('//','/',dirname(__FILE__).'/').'../common/lib/api.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'user.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'users.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'actions.php');


class Feeligo_Jomsocial_Api extends FeeligoApi {
  
  /**
   * tells whether a viewer is available in the current context
   * the viewer is the User which is currently logged in, when applicable
   *
   * @return bool
   */
  public function has_viewer() {
    $username		= JFactory::getUser()->name;
    return $username !=null;
  }

  /**
   * Accessor for the viewer
   *
   * @return bool
   */    
  public function viewer() {
    $user	= CFactory::getUser();
    return new Feeligo_Jomsocial_User_Adapter ($user,false) ;
  }
  
  /**
   * tells whether a subject is available in the current context
   * the subject is the user which is currently being viewed, when applicable
   *
   * @return bool
   */
  public function has_subject() {
    $displayed_user= CFactory::getRequestUser();
    $usrname=$displayed_user->name;
    $username		= JFactory::getUser()->name;
    return $username!=$usrname;
  }

  /**
   * Accessor for the subject
   *
   * @return FeeligoUserAdapter
   */
  public function subject() {
    $displayed_user = CFactory::getRequestUser();
    return new Feeligo_Jomsocial_User_Adapter ($displayed_user,false) ;
    
  }

  /**
   * Accessor for the website users
   *
   * @return FeeligoUsersSelector
   */
  public function users() {
    return new Feeligo_Jomsocial_Users_Selector();
  }

  /**
   * Accessor for user Actions
   *
   * @return FeeligoActionsSelector
   */
  public function actions() {
    if (!isset($this->_actions)) {
      $this->_actions = new Feeligo_Model_Selector_Actions();
    }
    return $this->_actions;
  }
  
  /**
   * Singleton pattern: gets or creates a single instance of this class
   * 
   * @return Feeligo_Mysite_Api
   */
  public static function getInstance() {
    if( is_null(self::$_instance) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * Shorthand for getInstance, allows to call Feeligo_Mysite_Api::_()
   *
   * @return Feeligo_Mysite_Api
   */
  public static function _() {
    return self::getInstance();
  }
  
}