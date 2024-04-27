<!-- kadai11_PHP選手権_index.php (PHP・HTML) --> 
<?php
// 0.1 最初にSESSIONを開始！！ココ大事！！
session_start();

// 0.6 自作関数群の読み込み
include("funcs.php"); // まず、include関数でfuncs.phpを読み込む.

// 0.9. 自作関数sschk()でセッションチェック
sschk();// DB接続する前にセッションチェックを行う

// 1.0 自作関数db_conn()でDB接続
$pdo = db_conn();

// 2.0 データ取得SQL作成
// 2.1 SQL作成(データをDBから取得するSQL文を作成する＝準備)
$sql    =  "SELECT * FROM rest_table";
// 全データ表示
if (isset($_POST['all'])) {
  $sql    =  "SELECT * FROM rest_table";
  $conditionSet = true;
}
// 新着順(登録日降順)にソート
if (isset($_POST['sort_by_indate'])) {
  $sql .= " ORDER BY indate DESC";
  $conditionSet = true;
}
// イタリアンで絞り込み
if (isset($_POST['filter_italian'])) {
  $sql .= " WHERE genre = 'イタリアン'";
  $conditionSet = true;
}
// フレンチで絞り込み
if (isset($_POST['filter_french'])) {
  $sql .= " WHERE genre = 'フレンチ'";
  $conditionSet = true;
}
// 和食で絞り込み
if (isset($_POST['filter_washoku'])) {
  $sql .= " WHERE genre = '和食'";
  $conditionSet = true;
}
// 中華で絞り込み
if (isset($_POST['filter_chinese'])) {
  $sql .= " WHERE genre = '中華'";
  $conditionSet = true;
}
// 焼き鳥で絞り込み
if (isset($_POST['filter_yakitori'])) {
  $sql .= " WHERE genre = '焼き鳥'";
  $conditionSet = true;
}
// 居酒屋で絞り込み
if (isset($_POST['filter_izakaya'])) {
  $sql .= " WHERE genre = '居酒屋'";
  $conditionSet = true;
}
// ラーメンで絞り込み
if (isset($_POST['filter_raamen'])) {
  $sql .= " WHERE genre = 'ラーメン'";
  $conditionSet = true;
}
// その他で絞り込み
if (isset($_POST['filter_etc'])) {
  $sql .= " WHERE genre = 'その他'";
  $conditionSet = true;
}

$stmt   = $pdo->prepare($sql);
// ここまでが準備、次の行で実行！$statusにはTrueかFalseが返る
$status = $stmt->execute();

// 3.0 データ取得後処理
// 3.1. SQLエラー発生時処理
// $statusがFalseの場合、つまりSQL実行時にエラーがある場合、
// SQLエラー関数sql_error($stmt)でエラーオブジェクト取得して表示.
if($status==false) { // $statusがfalseの場合、つまりSQL実行時にエラーがある場合）
  sql_error($stmt); // SQLエラー関数：sql_error($stmt)
}
// 3.2 SQL成功時処理
// fetchAllを使ってデータを一度、全部、$valuesに入れる.
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
// JSONに値を渡す場合に使う.とってきたデータをまるっとJSONに渡す.scriptタグで扱う? 使わないときはコメントアウトしておく
// $json = json_encode($values,JSON_UNESCAPED_UNICODE);


// 2.0_news データ取得SQL作成
// 2.1_news SQL作成(データをDBから取得するSQL文を作成する＝準備)
$sql_news    =  "SELECT * FROM news_table";
// 全データ表示
if (isset($_POST['all'])) {
  $sql_news    =  "SELECT * FROM news_table";
  $conditionSet = true;
}
$stmt_news   = $pdo->prepare($sql_news);
// ここまでが準備、次の行で実行！$statusにはTrueかFalseが返る
$status_news = $stmt_news->execute();

// 3.0_news データ取得後処理
// 3.1_news. SQLエラー発生時処理
// $statusがFalseの場合、つまりSQL実行時にエラーがある場合、
// SQLエラー関数sql_error($stmt)でエラーオブジェクト取得して表示.
if($status_news==false) { // $statusがfalseの場合、つまりSQL実行時にエラーがある場合）
  sql_error($stmt_news); // SQLエラー関数：sql_error($stmt)
}
// 3.2_news SQL成功時処理
// fetchAllを使ってデータを一度、全部、$valuesに入れる.
$values_news =  $stmt_news->fetchAll(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
// JSONに値を渡す場合に使う.とってきたデータをまるっとJSONに渡す.scriptタグで扱う? 使わないときはコメントアウトしておく
// $json = json_encode($values,JSON_UNESCAPED_UNICODE);
?>

<!-- ここからHTML -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CA Moms</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" type="img/png" href="img/favicon.png">
</head>

<!-- Header部分 Logoとナビゲーションメニュー-->
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
  $('#page-link a[href*="#"]').click(function () {//全てのページ内リンクに適用させたい場合はa[href*="#"]のみでもOK
    var elmHash = $(this).attr('href'); //ページ内リンクのHTMLタグhrefから、リンクされているエリアidの値を取得
    var pos = $(elmHash).offset().top;	//idの上部の距離を取得
    $('body,html').animate({scrollTop: pos}, 200); //取得した位置にスクロール。500の数値が大きくなるほどゆっくりスクロール
    return false;
  });
</script>

<header>
  <div class="container">
    <div><img src="img/cheers.png" alt=""></div>
    <div><h1>CA Moms</h1></div>
    <div class="welcome_msg">
      <?=$_SESSION["name"]?>さん こんにちは！　
    </div>
    <div>
      <ul>
        <li>
          <?php if($_SESSION["kanri_flg"]=="1"){ ?>
          <a href="user.php">ユーザー登録</a>
          <a href="news.php">ニュース登録</a>
          <?php } ?>
          <a href="#area-1">What’s New</a>
          <a href="#area-2">Add</a>
          <a href="#area-3">List</a>
          <!-- <a href="#area-4">Chat</a> -->
          <a href="logout.php">ログアウト</a>
        </li>
      </ul>
    </div>
  </div>
</header>

<!-- What's new を表示するセクション -->
<section id="area-1">
  <h2 class="h2_title">What's New</h2>
  <div>
    <div>
        <!-- foreach()で $valuesからひとつずつ値を取り出して$valueに入れていく-->
        <?php foreach($values_news as $value_news){ ?>
          <tr>
            <!-- 取り出した値にJacaScriptやHTMLタグが入っていると実行されてしまう -->
            <!-- セキュリティ的に脆弱性がある状態になっているのでサイニタイジングして危ない文字列を無効化する必要がある -->
            <!-- 関数h()を使ってサニタイジングする -->
            <!-- 表示する場所では（生のPHPでechoするところでは）この処理を必ずやること -->
            <h3><?=h($value_news["title"])?> (<?=h($value_news["indate"])?>)</h3>
        <p><?=h($value_news["news"])?></p>
        <?php } ?>
    </div>
  </div>
</section>

<!-- My Recommended Restaurant を登録するセクション -->
<section id="area-2">
  <h2>Add to Our List</h2>
  <form action="insert.php" method="POST">
  <div id="rest_reg">
  <fieldset>
    <legend>Here's what I found...</legend>
    <div class="info_row">
      <div class="info_label">
        <label for="rest_name">店名：</label>
      </div>
      <div class="info_input">
        <input type="text" name="rest_name" class="text_space"><br>
      </div>
    </div>
    <div class="info_row">
      <div class="info_label">
        <label for="genre">ジャンル：</label>
      </div>
      <div class="info_input">
        <select name="genre"  class="select_area">
          <option value="イタリアン">イタリアン</option>
          <option value="フレンチ">フレンチ</option>
          <option value="和食">和食</option>
          <option value="中華">中華</option>
          <option value="焼き鳥">焼き鳥</option>
          <option value="居酒屋">居酒屋</option>
          <option value="ラーメン">ラーメン</option>
          <option value="その他" selected>その他</option>
        </select><br>
      </div>
    </div>
    <div class="info_row">
      <div class="info_label">
        <label for="url">URL：</label>
      </div>
      <div class="info_input">
        <input type="text" name = "url" class="text_space"><br>
      </div>
    </div>
    <div class="info_row">
      <div class="info_label">
        <label for="memo">おすすめポイント：</label>
      </div>
      <div class="info_input">
        <textArea name="memo" rows="4" cols="53"></textArea><br>
      </div>
    </div>
    <div class="info_row">
      <div class="info_label">
        <label for="name">推薦者：</label>
      </div>
      <div class="info_input">
        <select name="name" class="select_area">
          <option value="Mie">Mie</option>
          <option value="Mika">Mika</option>
          <option value="Rita">Rita</option>
        </select><br>
      </div>
    </div>
    <button type="submit" class="regist">新規登録</button>
    </fieldset>
  </div>
  </form>
</section>

<!-- Our Favorite Restaurants を表形式で表示するセクション -->
<section id="area-3">
  <h2 class="h2_title">Our Favorite Restaurants</h2>
  <form action="index.php" method="post">
    <button type="submit" name="all">全表示</button>
    <button type="submit" name="sort_by_indate">登録日降順</button>
    <button type="submit" name="filter_italian">イタリアン</button>
    <button type="submit" name="filter_french">フレンチ</button>
    <button type="submit" name="filter_washoku">和食</button>
    <button type="submit" name="filter_chinese">中華</button>
    <button type="submit" name="filter_yakitori">焼き鳥</button>
    <button type="submit" name="filter_izakaya">居酒屋</button>
    <button type="submit" name="filter_raamen">ラーメン</button>
    <button type="submit" name="filter_etc">その他</button>
  </form>
  <div>
    <div>
      <table>
        <th>No.</th>
        <th>店名</th>
        <th>ジャンル</th>
        <th>URL</th>
        <th>おすすめポイント</th>
        <th>推薦者</th>
        <th>登録日</th>
        <th>修正</th>
        <?php if($_SESSION["kanri_flg"]=="1"){ ?>
          <th>削除</th>
        <?php } ?>
        <!-- foreach()で $valuesからひとつずつ値を取り出して$valueに入れていく-->
        <?php foreach($values as $value){ ?>
          <tr>
            <!-- 取り出した値にJacaScriptやHTMLタグが入っていると実行されてしまう -->
            <!-- セキュリティ的に脆弱性がある状態になっているのでサイニタイジングして危ない文字列を無効化する必要がある -->
            <!-- 関数h()を使ってサニタイジングする -->
            <!-- 表示する場所では（生のPHPでechoするところでは）この処理を必ずやること -->
            <td><?=h($value["id"])?></td>
            <td><?=h($value["rest_name"])?></td>
            <td><?=h($value["genre"])?></td>
            <td><a href= "<?=h($value["url"])?>" target="_blank" rel="noopener noreferrer">webサイト</a></td>
            <td><?=h($value["memo"])?></td>
            <td><?=h($value["name"])?></td>
            <td><?=h($value["indate"])?></td>
            <!-- 更新リンクと削除リンクをつける -->
            <td><a class="update" href="detail.php?id=<?=h($value["id"])?>" >修正</a></td>
            <?php if($_SESSION["kanri_flg"]=="1"){ ?>
              <td><a href="delete.php?id=<?=h($value["id"])?>">削除</a></td>
            <?php } ?>
            </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</section>
</body>
</html>