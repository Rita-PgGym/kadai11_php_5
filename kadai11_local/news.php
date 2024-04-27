<!-- kadai11_PHP選手権_news.php (PHP・HTML) --> 
<?php
//0. SESSION開始！！
session_start();

//1.0. 自作した関数群の読み込み
include("funcs.php"); //include関数でfuncs.phpを読み込む

//1.5. LOGINチェック 
sschk();
?>

<!-- ここからHTML -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CA Moms Admin.</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" type="img/png" href="img/favicon.png">
</head>

<!-- Header部分 Logoとナビゲーションメニュー-->
<body>
<header>
  <div class="container">
    <div><img src="img/cheers.png" alt=""></div>
    <div><h1>CA Mom 管理メニュー</h1></div>
    <?=$_SESSION["name"]?>さん こんにちは！　
    <?php include("menu_news.php"); ?>
  </div>
</header>

<!-- Main[Start] -->
<form method="POST" action="news_insert.php">
  <div id="news_reg">
    <fieldset>
      <legend>ニュース登録</legend>
        <div class="info_row">
          <div class="info_label">
            <label>タイトル：</label>
          </div>
          <div class="info_input">
            <input type="text" name="title"><br>
          </div>
        </div>
        <div class="info_row">
          <div class="info_label">
            <label>内容：</label>
          </div>
          <div class="info_input">
            <input type="text" name="news"><br>
          </div>
        </div>
        <div class="info_row">
          <div class="info_label">
            <label>掲載開始年月日：</label>
          </div>
          <div class="info_input">
            <input type="date" name="start_date"><br>
          </div>
        </div>
        <div class="info_row">
          <div class="info_label">
            <label>掲載終了年月日：</label>
          </div>
          <div class="info_input">
            <input type="date" name="end_date"><br>
          </div>
        </div>

     <button type="submit" class="regist">送信</button>
    </fieldset>
  </div>
</form>
<!-- Main[End] -->
</body>
</html>