<?php require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../classes/api.php'); 
// load FeeligoGiftbarApp
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../../common/apps/giftbar.php');

// instantiate FeeligoGiftbarApp with the singleton instance of Feeligo_Mysite_Api
$giftbar = new FeeligoGiftbarApp(Feeligo_Elgg17_Api::_());
?>

<!-- Feeligo GiftBar -->
<?php
  if ($giftbar->is_enabled()) {
    echo "<script type='text/javascript'>".$giftbar->initialization_js()."</script>";
    echo "<script type='text/javascript' src='".$giftbar->loader_js_url()."'></script>";
    
    echo "<div id='flg_giftbar_container'></div>";
  }
?>