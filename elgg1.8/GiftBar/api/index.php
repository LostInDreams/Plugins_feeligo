<?php

require_once("../../../engine/start.php");

require_once(str_replace('//','/',dirname(__FILE__).'/').'../classes/api.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../common/lib/controllers/controller.php'); 

$flg_ctrl = new FeeligoController(Feeligo_Elgg18_Api::_());
$flg_response = $flg_ctrl->run();

header("HTTP/1.0 ".$flg_response->code());

foreach($flg_response->headers() as $k => $v) {
  header($k.": ".$v);
}

echo $flg_response->body();

?>
