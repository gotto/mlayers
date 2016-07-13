<?php $layer[] = __FILE__; require_once 'global_template.php' ?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<title>エントリーテンプレート</title>
</head>
<body>

<div magic-data="メインコンテンツ">
	<div class="article-title">
		<h2 magic="記事タイトル">
			ここにタイトルが入ります
		</h2>
	</div>
	<div class="article-date" magic="記事日付">
		ここに日時が入ります
	</div>
	<div class="article-body">
		<div magic="記事本文">
			<p>ここに本文が入ります。</p>
		</div>
		<p class="continue">
			<a href="./">トップページに戻る</a>
		</p>
	</div>
</div>

</body>
</html>