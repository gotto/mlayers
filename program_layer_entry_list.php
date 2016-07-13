<?php

$layer[] = 'plugin_recent_entry';

function plugin_recent_entry(&$value)
	{
	$list = array();
	$d = opendir('.');
	while (($name = readdir($d)) !== false)
		{
		if (preg_match('/^entry\d+\.php/', $name))
			{
			$values = Pickup_Values(file_get_contents($name));
			$date = $values['記事日付'][0];
			$title = $values['記事タイトル'][0];
			$date_part = explode('/', $date);
			$date = $date_part[1].'/'.$date_part[2];
			$key = sprintf('%04d%02d%02d', $date_part[0], $date_part[1], $date_part[2]);
			$list[$key] = '<a href="./'.$name.'">'.$date.' '.$title.'</a>';
			}
		}
	closedir($d);
	krsort($list);
	$value['記事'] = array_values($list);
	$value['新着記事'] = array_slice(array_values($list), 0, 4);
	}
