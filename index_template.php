<?php $layer[] = __FILE__; require_once 'global_template.php' ?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<title>インデックステンプレート</title>
</head>
<body>

<div magic-data="メインコンテンツ">
	<div class="blog-title">
		<h2 magic="ブログタイトル">
			ここにブログのタイトルが入ります
		</h2>
	</div>
	<div class="blog-body">
		<div magic="ブログ説明文">
			<p>ここにブログの説明文が入ります。</p>
		</div>
	</div>
	<div magic="リスト|記事タイトル">
		<div magic="記事日付">
			ここに記事の日時が入ります
		</div>
		<div class="article-title">
			<h3 magic="記事タイトル">
				ここに記事のタイトルが入ります
			</h3>
		</div>
		<div class="article-summary">
			<div magic="記事サマリー">
				<p>ここに記事の本文が入ります。</p>
			</div>
			<p class="continue" magic="記事リンク">
				<a href="entry1.php">続きを読む</a>
			</p>
		</div>
	</div>
</div>

</body>
</html>