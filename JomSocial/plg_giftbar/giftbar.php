<?php
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'/common/lib/controllers/controller.php'); 

$my = CFactory::getUser();
$db =& JFactory::getDBO();
get_feeligo_api(); 
function get_feeligo_api(){
  $uri_parts = explode('?', $_SERVER['REQUEST_URI']);
 $uri_parts = explode('/', $uri_parts[0]);
 if (($n=sizeof($uri_parts)) >= 1 && $uri_parts[$n-1]== "feeligo-api") {

   require_once(str_replace('//','/',dirname(__FILE__).'/').'adapter/api.php');

   $ctrl = new FeeligoController(Feeligo_Jomsocial_Api::_());
   $response = $ctrl->run();

   /**
    * set response headers and output body
    */
  header("HTTP/1.1 ".$response->code());
   foreach($response->headers() as $k => $v) {
     header("$k: $v");
   }
   echo $response->body();
   exit();
 }
}
// load Feeligo_Mysite_Api
require_once(str_replace('//','/',dirname(__FILE__).'/').'adapter/api.php');

// load FeeligoGiftbarApp
require_once(str_replace('//','/',dirname(__FILE__).'/').'common/apps/giftbar.php');




class plgSystemGiftbar extends JPlugin
{
  public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
  public function onAfterRender()
	{
	  
	  $html	= JResponse::getBody();
		
		$html	= JString::str_ireplace( '</body>' , $this->put_in_footer().'</body>' , $html );
		
		JResponse::setBody( $html );
   
  }
 
  function put_in_footer(){
    //TO DO : get the output
    $giftbar = new FeeligoGiftbarApp(Feeligo_Jomsocial_Api::_());
    $res = "";
    if ($giftbar->is_enabled()) {
       $res= "<script type='text/javascript'>".$giftbar->initialization_js()."</script> <script type='text/javascript' src='".$giftbar->loader_js_url()."'></script> <div id='flg_giftbar_container'></div>";
     }
    return $res;
  }
}
?>