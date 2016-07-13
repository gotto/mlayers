<?php

$layer[] = 'program_layer_page_id';

function program_layer_page_id(&$value)
	{
	$value['body'][0]['id'] = basename($_SERVER['SCRIPT_NAME'], '.php');
	}
