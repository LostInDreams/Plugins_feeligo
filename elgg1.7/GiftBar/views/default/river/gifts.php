<?php
/**
 * New giftss river entry
 *
 * @package Gifts
 **/

$object = get_entity($vars['item']->object_guid);
$excerpt = elgg_get_excerpt($object->message);
$receiver = get_user($object-> receiver);
$sender = get_user($object-> sender);
$gift_name =$object-> name;
$gift_img_url = $object-> image_url;


/*$message= str_replace("\${subject}",'<a title="'.$sender->name.'" href="'.$sender->getURL().'">'.$sender->name.'</a>',$object->message);
$message= str_replace("\${direct_object}",$gift_name,$message);
$message= str_replace("\${indirect_object}",'<a title="'.$receiver->name.'" href="'.$receiver->getURL().'">'.$receiver->name.'</a>',$message);*/

$message= str_replace("\${subject}",'<a href ="'.$sender->getURL().'" data-flg-role="link" data-flg-type="user" data-flg-id="'.$sender->getGUID().'" data-flg-source="action"> '.$sender->name.'</a>',$object->message);
$message= str_replace("\${direct_object}",'<span data-flg-role="link" data-flg-type="gift" data-flg-id="'.$object->flg_id.'" data-flg-source="action">'.$gift_name.'</span>',$message);
$message= str_replace("\${indirect_object}",'<a href ="'.$receiver->getURL().' data-flg-role="link" data-flg-type="user" data-flg-id="'.$receiver->getGUID().'" data-flg-source="action""> '.$receiver->name.'</a>',$message);


$string = $message;
	#'message' => '<img title="'.$gift_name.'" src="'.$sender->getIcon('small').'"/>',
$string .= '<div class=\"river_content_display\">';
$string .= "<table><tr><td>" . elgg_view("profile/icon",array('entity' => $sender, 'size' => 'small')) . "</td>";
$string .= "<td><div class=\"following_icon\"></div></td><td> <img src='".$gift_img_url."' data-flg-role='link' data-flg-type='gift' data-flg-id='".$object->flg_id."' data-flg-source='action' />";
$string .= "<td><div class=\"following_icon\"></div></td><td>" . elgg_view("profile/icon",array('entity' => $receiver, 'size' => 'small')) . "</td></tr></table>";
$string .= "</div>";

echo $string;

?>
