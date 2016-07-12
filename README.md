# magic layers

## これは何？

HTMLをレイヤー化して表示するPHPスクリプトです。

## どうやって使うの？

```html
<?php $layer[] = __FILE__; require_once "index_template.php" ?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>
<div magic-data="文章"></div>\
</body>
</html>
```
