<?php

function anchor($txt,$targets,$class="") {
	
	// we lowercase everything
	foreach($targets as $target)
		$target = array_map("strtolower",$target);
	
	if(isset($class) && !empty($class))
		$class='class="'.$class.'"';
	
	return '<a '.$class.' onClick="request(\''.Router::makeUrl($targets).'\')" href="#">'.$txt.'</a>';
}

function image($name,$alt="",$class="",$dimension=array()) {
	
	if(!empty($alt))
		$alt = 'alt="'.$alt.'"';
		
	if(!empty($class))
		$class = 'class="'.$class.'"';
		
	if(isset($dimension['width']) && !is_null($dimension['width']))
		$width = 'width="'.$dimension['width'].'"';
	else
		$width='';

	if(isset($dimension['height']) && !is_null($dimension['height']))
		$height = 'height="'.$dimension['height'].'"';
	else
		$height='';
	
	$baseConf = GameManager::getInstance()->library('configuration')->get('base');
	
	return '<img '.$class.' src="'.$baseConf["image_folder"].$name.'" '.$height.' '.$width.' '.$alt.' />';
}