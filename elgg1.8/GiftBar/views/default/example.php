<?php
 
	$performed_by = get_user($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_user($vars['item']->object_guid);
	//var_dump($vars['item']);
	$url = $object->getURL();
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf($url." sent a gift \"{$vars['item']->annotation_id}\" to ");
	$string .= "<a href=\"{$object->getURL()}\">{$object->name}</a>";
 
	echo $string;
	
 
?>

