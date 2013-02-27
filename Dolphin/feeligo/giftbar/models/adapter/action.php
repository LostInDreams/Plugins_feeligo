<?php

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../common/interfaces/action_adapter.php');

class Feeligo_Model_Adapter_Action implements FeeligoActionAdapter {
  public function __construct($id) {
    $this->id = $id;
  }
  
  function id(){
    return $this->id;
  }
}
?>
