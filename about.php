<?php $layer[] = __FILE__; require_once 'global_template.php' ?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<title magic-data="ページタイトル">
	mlayersについて&magic;
</title>
</head>
<body>

<div magic-data="メインコンテンツ">
	<h2>mlayersについて</h2>
	<p>mlayersは静的なページにHTML継承の機能を追加するプログラムです。〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇。</p>
	<h3>問い合わせ先</h3>
	<div>（HTMLファイルをインクルードする例）</div>
	<div magic="インクルード|html files/test.html">
		ここにfiles/test.htmlが読み込まれます。
	</div>
	<h3>バージョン履歴</h3>
	<div>（テキストファイルをインクルードする例）</div>
	<div class="log" magic="インクルード|pre files/version_log.txt">
		ここにfiles/version_log.txtが読み込まれます。
	</div>
	<h3>バージョン履歴</h3>
	<div>（CSVファイルをインクルードする例）</div>
	<div class="table" magic="インクルード|csv files/test.csv">
		ここにfiles/test.csvが読み込まれます。CSVファイルはtableに変換されます。
	</div>
</div>

</body>
</html>