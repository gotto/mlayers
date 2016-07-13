<?php require_once 'program_layer_contact.php' ?>
<?php $layer[] = __FILE__; require_once 'global_template.php' ?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<title magic-data="ページタイトル">
	お問い合わせ&magic;
</title>
</head>
<body>

<div magic-data="メインコンテンツ">
	<h2>お問い合わせ</h2>
	<p>〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇。</p>
	<p></p>
	<form class="contact-form">
	<ul>
	<li>
	<label class="contact-label">お名前</label>
	<input type="text" class="contact-text" magic="アトリビュート|お問い合わせお名前">
	</li>
	<li>
	<label class="contact-label">お問い合わせ内容</label>
	<textarea class="contact-textarea" magic="お問い合わせ内容"></textarea>
	</li>
	</ul>
	<input type="submit" value="送信" class="contact-submit">
	</form>
</div>

</body>
</html>