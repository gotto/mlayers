<?php require_once 'program_layer_entry_list.php' ?>
<?php require_once 'program_layer_page_id.php' ?>
<?php $layer[] = __FILE__; require_once 'mlayers.php' ?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="scripts/jquery-2.1.4.min.js"></script>
<script src="scripts/script.js"></script>
<link rel="stylesheet" href="styles/style.css">
<title magic="ページタイトル">
	|mlayers紹介
</title>
</head>
<body magic="アトリビュート|body">

<!-- 共通ヘッダー -->
<div class="header">
	<h1 class="header-title">mlayers紹介</h1>
	<div class="page-banner"></div>
	<div class="menu-button">メニュー</div>
	<ul class="menu">
		<li class="menu-item"><a class="menu-link" href="./">トップページ</a></li>
		<li class="menu-item"><a class="menu-link" href="./archive.php">記事一覧</a></li>
		<li class="menu-item"><a class="menu-link" href="./download.php">ダウンロード</a></li>
		<li class="menu-item"><a class="menu-link" href="./contact.php">お問い合わせ</a></li>
		<li class="menu-item"><a class="menu-link" href="./about.php">mlayersについて</a></li>
	</ul>
</div>
<!-- /共通ヘッダー -->

<!-- メインエリア -->
<div class="main">
	<div class="article" magic="メインコンテンツ">
		<p>ここにメインコンテンツが入ります。</p>
	</div>
	<div class="backnumber">
		<h2>新着記事</h2>
		<ul class="backnumber-list">
			<li magic="リスト|新着記事">
				<span magic="新着記事">
					<a href="#">ここに新着記事が入ります</a>
				</span>
			</li>
		</ul>
	</div>
</div>
<!-- /メインエリア -->

<!-- 共通フッター -->
<div class="footer">
	<address>&copy; 2015 有限会社フレンドリーラボ</address>
</div>
<!-- /共通フッター -->

</body>
</html>
