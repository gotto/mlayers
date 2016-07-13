<?php

# チェックモードを判定する
function CheckMode()
	{
	return $_GET['check'];
	}

# テンプレートを表示する
function Check_Template($check)
	{
	if ($check == 'layer')
		{
		return file_get_contents($_SERVER['SCRIPT_FILENAME']);
		}
	elseif ($check == 'source')
		{
		$html = file_get_contents($_SERVER['SCRIPT_FILENAME']);
		return Make_SourcePage($html);
		}
	error(__LINE__);
	}

# テンプレート表示ページを組み立てる
function Make_SourcePage($html)
	{
	$html = htmlspecialchars($html);
	$html = str_replace("\t", '     ', $html);
	$html = str_replace(' ', '&nbsp;', $html);
	$pattern = array(
		'/(&nbsp;magic=&quot;)(.+?)(&quot;)/',
		'/(&nbsp;magic-data=&quot;)(.+?)(&quot;)/',
		'/(&nbsp;magic-list=&quot;)(.+?)(&quot;)/',
		'/(&lt;!--&nbsp;[^\/].+?&nbsp;--&gt;)/',
		'/(&lt;!--&nbsp;\/.+?&nbsp;--&gt;)\n?/',
		);
	$replacement = array(
		'$1<em class="magic">$2</em>$3',
		'$1<em class="data">$2</em>$3',
		'$1<em class="list">$2</em>$3',
		'<div class="comment">$1',
		'$1</div>',
		);
	$html = preg_replace($pattern, $replacement, $html);				
	$html = nl2br($html);
	$html = 
		'<!DOCTYPE html><html lang="ja">'.
		'<head>'.
		'<meta charset="UTF-8">'.
		'<meta name="viewport" content="width=device-width, initial-scale=1.0">'.
		'<style>'.
		'* {font-weight: normal; font-style: normal; text-decoration: none;}'.
		'.magic {border: 3px solid #ff0; background-color: #ff0;}'.
		'.data {border: 3px solid #9ef; background-color: #9ef;}'.
		'.list {border: 3px solid #9f9; background-color: #9f9;}'.
		'.comment {border: 2px dotted #ccc; background-color: #fafafa;}'.
		'</style>'.
		'</head>'.
		'<body style="font-family: monospace; word-break: break-all;">'.$html.'</body>'.
		'</html>';
	return $html;
	}