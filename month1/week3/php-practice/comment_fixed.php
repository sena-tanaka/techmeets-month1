<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>コメント投稿</title>
</head>
<body>

<h1>コメント投稿フォーム</h1>

<form method="POST">
  <label>名前:</label>
  <input type="text" name="name"><br>
  <label>コメント:</label>
  <textarea name="comment"></textarea><br>
  <button type="submit">投稿する</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // trim() で前後の余分な空白（スペースだけ入力された場合など）も取り除く
    $name    = trim($_POST["name"]);
    $comment = trim($_POST["comment"]);

    // 修正点1: 代入(=)ではなく比較(===)を使う
    // 修正点3: 名前だけでなく、コメントも空チェックする
    if ($name === "" || $comment === "") {
        echo "名前とコメントの両方を入力してください。";
    } else {
        // 修正点2: htmlspecialchars() でエスケープしてXSSを防ぐ
        echo "<p>" . htmlspecialchars($name) . "さんのコメント:</p>";
        echo "<p>" . htmlspecialchars($comment) . "</p>";
    }
}
?>

</body>
</html>
