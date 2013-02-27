<?php


    bx_import('BxDolModuleDb');
    bx_import('BxDolModule');

    require_once(str_replace('//','/',dirname(__FILE__).'/').'../api.php');
    require_once(str_replace('//','/',dirname(__FILE__).'/').'../common/lib/controllers/controller.php'); 
    require_once(str_replace('//','/',dirname(__FILE__).'/').'../common/apps/giftbar.php');
    require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );

    /**
     * Giftbar module by Feeligo
     *
     * This module allow user to send and receive gifts.
     *
     *
     *
     *
     *
     *
     *
     *
     *
     * Memberships/ACL:
     * use giftbar - BX_USE_GIFTBAR
     *
     *
     *
     * Service methods:
     *
     * Generate giftbar window. 
     * @see FlgGiftBarModule::serviceGetGiftBar();
     * BxDolService::call('giftbarheader', 'get_giftbarheader');
     *
     * Alerts:
     *
     * no alerts here;
     *
     */
    class FlgGiftBarModule extends BxDolModule 
    {
        // contain some module information ;
        var $aModuleInfo;

        // contain path for current module;
        var $sPathToModule;

        // contain logged member's Id;
        var $iMemberId;

        // contain all used templates
        var $aUsedTemplates = array();

        /**
    	 * Class constructor ;
         *
         * @param   : $aModule (array) - contain some information about this module;
         *                  [ id ]           - (integer) module's  id ;
         *                  [ title ]        - (string)  module's  title ;
         *                  [ vendor ]       - (string)  module's  vendor ;
         *                  [ path ]         - (string)  path to this module ;
         *                  [ uri ]          - (string)  this module's URI ;
         *                  [ class_prefix ] - (string)  this module's php classes file prefix ;
         *                  [ db_prefix ]    - (string)  this module's Db tables prefix ;
         *                  [ date ]         - (string)  this module's date installation ;
    	 */
    	function FlgGiftBarModule(&$aModule) 
        {
            parent::BxDolModule($aModule);

            // prepare the location link ;
            $this -> sPathToModule  = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri();
            $this -> aModuleInfo    = $aModule;
            $this -> iMemberId 		= getLoggedId();
        }


    

        /**
         * Generate the giftbar header;
         */
        function serviceGetGiftBarHeader()
        {
       }

        /**
          * Generate the giftbar footer;
          */
      function serviceGetGiftBarFooter(){
        $giftbar = new FeeligoGiftbarApp(FeeligoDolApi::_());
        ?>
       <!-- Feeligo GiftBar -->
       <?php
         if ($giftbar->is_enabled()) {
           echo "<script type='text/javascript'>".$giftbar->initialization_js()."</script>";
           echo "<script type='text/javascript' src='".$giftbar->loader_js_url()."'></script>";

           echo "<div id='flg_giftbar_container'></div>";
         }
          
        
        }
      
      function serviceGetGiftBarApi()
       {
         $uri_parts = explode('?', $_SERVER['REQUEST_URI']);
         $uri_parts = explode('/', $uri_parts[0]);

         if (($n=sizeof($uri_parts)) >= 1 && $uri_parts[$n-1]== "feeligo-api") {
           
           
           $ctrl = new FeeligoController(FeeligoDolApi::_());
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
      
      
    }
