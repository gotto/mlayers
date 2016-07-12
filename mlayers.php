<?php
# *****************************************************************************
# Magic Layers
# 有限会社フレンドリーラボ 後藤裕文 <gotcha@friendlylab.co.jp>
# Version 19 2015-12-03,2016-01-22,2016-02-18,2016-02-29
# *****************************************************************************

error_reporting(32767 - 8); # E_ALL - E_NOTICE
mb_language('japanese');
mb_internal_encoding('UTF-8');
date_default_timezone_set('Asia/Tokyo');

# *****************************************************************************
# Magic Layers
# *****************************************************************************

# HTMLのレイヤーを処理する
function Extend_HTML($layer)
	{
	$checker = dirname(__FILE__).'/mlayers-check.php';
	if (file_exists($checker))
		{
		require $checker;
		$check = CheckMode();
		if ($check)
			{
			return Check_Template($check);
			}
		}
	if (!isset($layer) || !is_array($layer))
		{
		error(__LINE__);
		}
	$values = array();
	while (count($layer) >= 1)
		{
		$superlayer = array_shift($layer);
		if (file_exists($superlayer))
			{
			$source = Make_Source(trim(file_get_contents($superlayer)), $values);
			$source = Apply_Values($source, $values);
			if (count($layer) >= 1)
				{
				$values = array_merge(Pickup_Values($source), $values);
				}
			}
		elseif (count($layer) >= 1 && function_exists($superlayer))
			{
			$superlayer($values);
			}
		else
			{
			error(__LINE__);
			}
		}
	return $source;
	}

# magic-dataを抽出する
function Pickup_Values($source)
	{
	return Scan_Source($source, null, 'magic-data', 1);
	}

# リストの繰り返し部分を展開する
function Make_Source($source, $replace)
	{
	if (!is_array($replace))
		{
		error(__LINE__);
		}
	return Scan_Source($source, $replace, 'magic', 2);
	}

# テンプレートに値を埋め込む
function Apply_Values($source, $replace)
	{
	if (!is_array($replace))
		{
		error(__LINE__);
		}
	return Scan_Source($source, $replace, 'magic', 3);
	}

# テンプレートを解析して処理する
function Scan_Source($source, $replace, $attr, $phase)
	{
	if ($phase < 1 || $phase >= 4 || strpos($source, "\r") !== false)
		{
		error(__LINE__);
		}
	$html = '';
	$values = array();
	$current_tag = '';
	$current_tag_name = '';
	$name = '';
	$value = '';
	$level = 0;
	#
	# ソースがなくなるまで処理する
	#
	while ($source != '')
		{
		list($text, $source) = Skip_TextPart($source, $attr, $phase);
		list($tag_raw, $source) = explode('>', $source, 2);
		if (strpos($tag_raw, $attr) === false)
			{
			$tag = $tag_raw;
			$pos = 0;
			list($tag_name) = explode(' ', $tag, 2);
			$tag_name = strtolower($tag_name);
			}
		else
			{
			$tag_element = Split_TagElement($tag_raw);
			$tag_name = $tag_element[0];
			$tag_name = strtolower($tag_name);
			$tag = implode(' ', $tag_element);
			list($pos, $pos_name) = Find_Attribute($attr, $tag_element, $phase);
			}
		#
		# magicアトリビュートあり
		#
		if ($pos >= 1)
			{
			$name = $pos_name;
			unset($tag_element[$pos]);
			$tag = implode(' ', $tag_element);
			if ($phase != 2)
				{
				$prefix = 'アトリビュート|';
				$len = strlen($prefix);
				if (substr($name, 0, $len) == $prefix)
					{
					$name2 = trim(substr($name, $len));
					if ($phase == 1)
						{
						$values[$name2][] = Make_AttributeValue($tag_element);
						}
					elseif ($phase == 3 && extra_array_key_exists($name2, $replace))
						{
						$value = Get_RingListValue($replace[$name2]);
						$tag_element = Apply_AttributeValue($tag_element, $value);
						}
					$tag = implode(' ', $tag_element);
					$html .= $text.'<'.$tag.'>';
					continue;
					}
				}
			if ($current_tag_name != '')
				{
				print($tag);
				error(__LINE__);
				}
			$current_tag = $tag;
			$current_tag_name = $tag_name;
			if (!array_key_exists($name, $values))
				{
				$values[$name] = array();
				}
			$value = '';
			$level = 1;
			$html .= $text;
			}
		#
		# magicブロックの外
		#
		elseif ($current_tag_name == '')
			{
			$html .= $text.'<'.$tag.'>';
			}
		#
		# magicブロックと同じタグ
		#
		elseif ($tag_name == $current_tag_name)
			{
			$level++;
			$value .= $text.'<'.$tag.'>';
			}
		#
		# magicブロックの終了タグではない
		#
		elseif ($tag_name != '/'.$current_tag_name)
			{
			$value .= $text.'<'.$tag.'>';
			}
		#
		# magicブロックの終了タグだが階層の中
		#
		elseif (--$level >= 1)
			{
			$value .= $text.'<'.$tag.'>';
			}
		#
		# magicブロックの終了タグ
		#
		else
			{
			$value .= $text;
			$values[$name][] = $value;
			if ($phase == 2)
				{
				if (array_key_exists($name, $replace))
					{
					$count = count($replace[$name]);
					if ($count >= 2)
						{
						$html .= str_repeat('<'.$current_tag.'>'.$value.'<'.$tag.'>', $count - 1);
						}
					}
				}
			elseif ($phase == 3)
				{
				if (extra_array_key_exists($name, $replace))
					{
					$replace_value = Get_RingListValue($replace[$name]);
					$value = str_replace('&magic;', $value, $replace_value);
					}
				}
			$html .= '<'.$current_tag.'>'.$value.'<'.$tag.'>';
			$current_tag_name = '';
			}
		}
	#
	# フェーズ1なら値を返し、それ以外ならHTMLを返す
	#
	if ($phase == 1)
		{
		return $values;
		}
	return $html;
	}

# PHPタグ、コメントタグ、styleタグ、scriptタグを読み飛ばす
function Skip_TextPart($source, $attr)
	{
	$skips = array('style', 'script');
	$text = '';
	while (true)
		{
		list($text2, $source) = explode('<', $source, 2);
		$text .= $text2;
		if (substr($source, 0, 1) == '?')
			{
			list($text2, $source) = explode('?>', $source, 2);
			$source = ltrim($source);
			}
		elseif (substr($source, 0, 3) == '!--')
			{
			list($text2, $source) = explode('-->', $source, 2);
			$text .= '<'.$text2.'-->';
			}
		else
			{
			foreach ($skips as $skip)
				{
				$x = substr($source, 0, strlen($skip));
				if (strtolower($x) != $skip)
					{
					continue;
					}
				$y = substr($source, strlen($skip), 1);
				if ($y != '>' && $y != ' ' && $y != "\t" && $y != "\n")
					{
					continue;
					}
				list($tag) = explode('>', $source, 2);
				if (strpos($tag, $attr) !== false)
					{
					$tag_element = Split_TagElement($tag);
					list($pos, $pos_name) = Find_Attribute($attr, $tag_element, $phase);
					if ($pos >= 1)
						{
						continue;
						}
					}
				list($text2, $delim, $source) =
					preg_split(
					'/(<\/'.$x.'>)/i', $source, 2, PREG_SPLIT_DELIM_CAPTURE
					);
				if (is_null($source))
					{
					error(__LINE__);
					}
				$text .= '<'.$text2.$delim;
				continue 2;
				}
			break;
			}
		}
		return array($text, $source);
	}

# タグを要素ごとに分離する
function Split_TagElement($tag_raw)
	{
	$seg = explode('"', $tag_raw);
	$x = 0;
	for ($i = 0; $i < count($seg); $i++)
		{
		if ($x == 0)
			{
			$seg[$i] = preg_replace('/\s+/', "\r", $seg[$i]);
			}
		$x = 1 - $x;
		}
	$tag = implode('"', $seg);
	$tag = str_replace("\r=", '=', $tag);
	$tag = str_replace("=\r", '=', $tag);
	$tag_element = explode("\r", $tag);
	return $tag_element;
	}

# magicアトリビュートの位置を見つける
function Find_Attribute($attr, $tag_element, $phase)
	{
	$attr .= '="';
	if ($phase == 2)
		{
		$attr .= 'リスト|';
		}
	$len = strlen($attr);
	$pos = 0;
	foreach ($tag_element as $e)
		{
		if (substr($e, 0, $len) == $attr)
			{
			if (substr($e, -1, 1) != '"')
				{
				error(__LINE__);
				}
			$name = substr($e, $len, -1);
			return array($pos, $name);
			}
		$pos++;
		}
	return array(0, '');
	}

# アトリビュートのハッシュを作る
function Make_AttributeValue($tag_element)
	{
	$value = array();
	array_shift($tag_element);
	foreach ($tag_element as $e)
		{
		list($key, $val) = explode('=', $e, 2);
		if (substr($val, 0, 1) == '"')
			{
			$val = substr($val, 1);
			if (substr($val, -1, 1) == '"')
				{
				$val = substr($val, 0, strlen($val) - 1);
				}
			}
		$value[$key] = $val;
		}
	return $value;
	}

# タグのアトリビュートを埋め込む
function Apply_AttributeValue($tag_element, $value)
	{
	if (!is_array($value))
		{
		error(__LINE__);
		}
	for ($i = 1; $i < count($tag_element); $i++)
		{
		list($key, $val) = explode('=', $tag_element[$i], 2);
		if (isset($value[$key]))
			{
			$tag_element[$i] = $key.'="'.htmlspecialchars($value[$key]).'"';
			unset($value[$key]);
			}
		}
	foreach ($value as $key => $val)
		{
		$tag_element[] = $key.'="'.htmlspecialchars($value[$key]).'"';
		}
	return $tag_element;
	}

# キーの値に応じてインクルードを実行する
function extra_array_key_exists($name, &$replace)
	{
	$prefix = 'インクルード|';
	$len = strlen($prefix);
	if (substr($name, 0, $len) == $prefix)
		{
		list($type, $file) = explode(' ', ltrim(substr($name, $len)), 2);
		$file = trim($file);
		if (file_exists($file))
			{
			$value = file_get_contents($file);
			}
		else
			{
			$value = '';
			}
		if ($type == 'text')
			{
			$value = nl2br(htmlspecialchars($value));
			}
		elseif ($type == 'pre')
			{
			$value = htmlspecialchars($value);
			}
		elseif ($type == 'csv')
			{
			$value = mb_convert_encoding($value, 'UTF-8', 'CP932');
			$value = Make_Table($value);
			}
		elseif ($type == 'utf8csv')
			{
			$value = Make_Table($value);
			}
		elseif ($type != 'html')
			{
			error(__LINE__);
			}
		$replace[$name] = array($value);
		}
	return array_key_exists($name, $replace);
	}

# CSVデータからテーブルタグを生成する
function Make_Table($value)
	{
	$value = str_replace("\r", '', $value);
	$value = Set_RowColDelimiter($value, "\rr", "\rf");
	$table = array();
	$table[] = '<table>';
	$rows = explode("\rr", $value);
	$r = 1;
	foreach ($rows as $row)
		{
		$table[] = '<tr class="row'.$r.' '.($r % 2 ? 'odd': 'even').'">';
		$cols = explode("\rf", $row);
		$c = 1;
		foreach ($cols as $col)
			{
			$col = trim($col);
			if (substr($col, 0, 1) == '"')
				{
				$col = substr($col, 1, strlen($col) - 2);
				}
			$col = str_replace('""', '"', $col);
			$table[] = '<td class="col'.$c.' '.($c % 2 ? 'odd': 'even').'">';
			$table[] = nl2br(htmlspecialchars($col));
			$table[] = '</td>';
			$c++;
			}
		$table[] = '</tr>';
		$r++;
		}
	$table[] = '</table>';
	return implode('', $table);
	}

# CSVデータの行列デリミターを変更する
function Set_RowColDelimiter($value, $r, $f)
	{
	$seg = explode('"', rtrim($value));
	$x = 0;
	for ($i = 0; $i < count($seg); $i++)
		{
		if ($x == 0)
			{
			$seg[$i] = str_replace(
				array("\n", ','), array($r, $f), $seg[$i]
				);
			}
		$x = 1 - $x;
		}
	return implode('"', $seg);
	}

# リングリストから値を取り出す
function Get_RingListValue(&$list)
	{
	$value = current($list);
	if ($value === false)
		{
		$value = reset($list);
		}
	if ($value === false)
		{
		error(__LINE__);
		}
	next($list);
	return $value;
	}

# デバッグ用プリント
function printv($var)
	{
	header('Content-type: text/html; charset=UTF-8');
	print('<pre>'.htmlspecialchars(print_r($var, true)).'</pre>');
	exit;
	}

# エラー終了
function error($line)
	{
	print($line);
	die;
	}

# *****************************************************************************
# リクエスト情報の取得
# *****************************************************************************

# GETパラメータを取得する
function GetValue($name)
	{
	list($name, $index) = Explode_NameIndex($name);
	if (!array_key_exists($name, $_GET))
		{
		return null;
		}
	elseif (isset($index))
		{
		if (is_array($_GET[$name]) && array_key_exists($index, $_GET[$name]))
			{
			return Strip_MagicQuotes($_GET[$name][$index]);
			}
		return null;
		}
	return Strip_MagicQuotes($_GET[$name]);
	}

# POSTパラメータを取得する
function PostValue($name)
	{
	list($name, $index) = Explode_NameIndex($name);
	if (!array_key_exists($name, $_POST))
		{
		return null;
		}
	elseif (isset($index))
		{
		if (is_array($_POST[$name]) && array_key_exists($index, $_POST[$name]))
			{
			return Strip_MagicQuotes($_POST[$name][$index]);
			}
		return null;
		}
	return Strip_MagicQuotes($_POST[$name]);
	}

# フィールド名[インデックス]を分解する
function Explode_NameIndex($name_index)
	{
	list($name, $index) = explode('[', $name_index);
	if (!isset($index))
		{
		return array($name, null);
		}
	if (substr($index, -1) != ']')
		{
		die;
		}
	$index = substr($index, 0, -1);
	if ($index == '' || preg_match('/\D/', $index))
		{
		die;
		}
	return array($name, $index);
	}

# magic_quotes_gpcが有効であったらquotesを除去する
function Strip_MagicQuotes($value)
	{
	if (get_magic_quotes_gpc())
		{
		if (is_array($value))
			{
			foreach ($value as &$val)
				{
				$val = Strip_MagicQuotes($val);
				}
			unset($val);
			}
		elseif (isset($value))
			{
			$value = stripslashes($value);
			}
		}
	return $value;
	}

# *****************************************************************************
# スタートポイント
# *****************************************************************************

if (!isset($dont_autorun))
	{
	print(Extend_HTML($layer));
	exit;
	}
