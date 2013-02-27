<?php
/**
 * New giftss river entry
 *
 * @package Gifts
 */

$object = $vars['item']->getObjectEntity();
$excerpt = elgg_get_excerpt($object->message);
$receiver = get_user($object-> receiver);
$sender = get_user($object-> sender);
$gift_name =$object-> name;
$gift_img_url = $object-> image_url;


/*$message= str_replace("\${subject}",elgg_view('output/url', array('href' => $sender->getURL(),'text' => $sender->name,'class' => 'elgg-river-subject','is_trusted' => true)),$object->message);
$message= str_replace("\${direct_object}",$gift_name,$message);
$message= str_replace("\${indirect_object}",elgg_view('output/url', array('href' => $receiver->getURL(),'text' => $receiver->name,'class' => 'elgg-river-subject','is_trusted' => true)),$message);*/


$message= str_replace("\${subject}",'<a href ="'.$sender->getURL().'" data-flg-role="link" data-flg-type="user" data-flg-id="'.$sender->getGUID().'" data-flg-source="action"> '.$sender->name.'</a>',$object->message);
$message= str_replace("\${direct_object}",'<span data-flg-role="link" data-flg-type="gift" data-flg-id="'.$object->flg_id.'" data-flg-source="action">'.$gift_name.'</span>',$message);
$message= str_replace("\${indirect_object}",'<a href ="'.$receiver->getURL().' data-flg-role="link" data-flg-type="user" data-flg-id="'.$receiver->getGUID().'" data-flg-source="action""> '.$receiver->name.'</a>',$message);

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'summary' => $message,
	'message' => '<img src = "'.$gift_img_url.'" data-flg-role="link" data-flg-type="gift" data-flg-id="'.$object->flg_id.'" data-flg-source="action" >',
	#'attachments' => ,
)); 

$item = $vars['item'];

?> 
