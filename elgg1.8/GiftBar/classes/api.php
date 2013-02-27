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
 * @package    Feeligo_Api_Feeligo
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
require_once(str_replace('//','/',dirname(__FILE__).'/').'../common/lib/api.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/adapter/user.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/selector/users.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'models/selector/actions.php');

 
class Feeligo_Elgg18_Api extends FeeligoApi
{

  /**
   * The singleton Api object
   *
   * @var Feeligo_Api_Feeligo
   */
  protected static $_instance;
  
  /**
   * Get or create the current api instance
   * 
   * @return Engine_Api
   */
  public static function getInstance() {
    if( is_null(self::$_instance) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * Shorthand for getInstance
   *
   * @return Engine_Api
   */
  public static function _() {
    return self::getInstance();
  }
  
  /**
   * constructor (cannot be called from outside)
   * 
   */
  protected function __construct() {
    parent::__construct();
    // store the viewer
    $this->_adapter_viewer = new Feeligo_Model_Adapter_User (elgg_get_logged_in_user_entity(),false);
    // store the subject
    #var_dump(elgg_get_page_owner_entity()->guid);
    if (get_user(elgg_get_page_owner_guid()) !== false) {
      $u_subj =new Feeligo_Model_Adapter_User (elgg_get_page_owner_entity(),true) ;
      
      // ensure the subject is not the same User as the viewer
      if ($this->_adapter_viewer->id()!=$u_subj->id()) { //attention changer isSelf
        $this->_adapter_subject = $u_subj;
      }
      else{
        $this->adapter_subject = null;
      }
    }
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
  
  public function viewer() {
    return $this->_adapter_viewer;
  }
  
  public function subject() {
    return $this->_adapter_subject;
  }
  

  
  
  /**
   * Gift bar methods
   **/
  public function has_viewer(){
    return true;
    return elgg_get_logged_in_user_entity()->guid!=false;
  }
  public function has_subject(){
    return false;
    return elgg_get_page_owner_entity()->guid!=false;
  }
  
}
