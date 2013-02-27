<?php 
require_once(str_replace('//','/',dirname(__FILE__).'/').'api.php'); 
// load FeeligoGiftbarApp
require_once(str_replace('//','/',dirname(__FILE__).'/').'common/apps/giftbar.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'common/lib/controllers/controller.php'); 


// instantiate FeeligoGiftbarApp with the singleton instance of Feeligo_Mysite_Api

  
class action{
  
  
  function footer_script() {
    ?> <!-- Feeligo GiftBar -->
    <?php
    $giftbar = new FeeligoGiftbarApp(FeeligoWordPressApi::_());
    if ($giftbar->is_enabled()) {
      echo "<script type='text/javascript'>".$giftbar->initialization_js()."</script>";
      echo "<script type='text/javascript' src='".$giftbar->loader_js_url()."'></script>";

      echo "<div id='flg_giftbar_container'></div>";
    }
  }
  
  function header_css(){
    remove_filter( 'bp_get_activity_action',                'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_get_activity_content_body',          'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_get_activity_content',               'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_get_activity_parent_content',        'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_get_activity_latest_update',         'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_get_activity_latest_update_excerpt', 'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_get_activity_feed_item_description', 'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_activity_content_before_save',       'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_activity_action_before_save',        'bp_activity_filter_kses', 1 );
    remove_filter( 'bp_activity_latest_update_content',     'wp_filter_kses', 1 );

  }
  
  
  function header_script(){

  }
  
  function feeligo_router(){
    global $route,$wp_query,$window_title;
    
    $uri_parts = explode('?', $_SERVER['REQUEST_URI']);
    $uri_parts = explode('/', $uri_parts[0]);

    if (($n=sizeof($uri_parts)) >= 2 && $uri_parts[$n-2] == 'feeligo' && substr($uri_parts[$n-1],0,3) == 'api') {
      $ctrl = new FeeligoController(FeeligoWordpressApi::_());
      $resp = $ctrl->run();
      
      echo $resp->body();
      
      exit();
    }
  }
}
    

?>
