# magic layers

## これは何？

HTMLをレイヤー化して表示するPHPスクリプトです。

## どうやって使うの？

index.html
```html
<?php $layer[] = __FILE__; require_once "index_template.php" ?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>
<div magic-data="文章">この文章が下位レイヤーに流し込まれます。</div>
</body>
</html>
```

index_template.php
```html
<?php $layer[] = __FILE__; require_once 'mlayers.php' ?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>
<div>
  <div magic="文章"></div>
</div>
</body>
</html>
```

# 簡単な解説
- レイヤーは多段にできます。
- 一番上のレイヤーをコンテンツレイヤーと呼びます。
- コンテンツレイヤー以外をテンプレートレイヤーと呼びます。
- 上位レイヤーのノードのアトリビュートを下位レイヤーに流し込むこともできます。
- インクルードの機能もあります。
- CSVファイルをインクルードしてテーブルに整形する機能もあります。
- プログラムでレイヤーの値を生成して下位レイヤーに流し込むこともできます。
