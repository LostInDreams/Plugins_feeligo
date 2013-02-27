<?php
/**
 * gifts helper functions
 *
 * @package gifts
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $gift A gift object.
 * @return array
 */
function save_gift($name,$sender,$receiver,$message, $image_url, $flg_id) {

	$access_id =2;
  $container_guid = $receiver;
  $guid = 0;
  $new = true;  
  if ($guid == 0) {
	  $gift = new ElggObject;
	  $gift->subtype = "gifts";
	  $gift->container_guid = get_loggedin_user()->guid;
	  system_message('new');
  } else {
	  $gift = get_entity($guid);
	  $new = false;
	  if (!$gift->canEdit()) {
		  system_message('gift save failed');
		  forward(REFERRER);
	  }
  }
  $gift->name = $name;
  $gift->sender = intval($sender);
  $gift->receiver = intval($receiver);
  $gift->message = $message;
  $gift->flg_id = $flg_id;
  $gift->image_url = $image_url;
  $gift->access_id = $access_id;  
  $gift->save();
  // @todo  
  if (is_array($shares) && sizeof($shares) > 0) {
	  foreach($shares as $share) {
		  $share = (int) $share;
		  add_entity_relationship($gift->getGUID(), 'share', $share);
	  }
  }
  system_message('success');

  //add to river only if new
  if ($new) {
	  $r=add_to_river('river/gifts','create',$gift->sender , $gift->getGUID());
	}
  
  return $gift->getGUID();
}
