<?php
require 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '記事が見つかりません。';
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
</head>
<body>
    <p><a href="index.php">← 一覧に戻る</a></p>

    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <p>著者: <?= htmlspecialchars($post['author']) ?></p>
    <p>投稿日時: <?= $post['created_at'] ?> / 更新日時: <?= $post['updated_at'] ?></p>
    <hr>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <hr>

    <p>
        <a href="edit.php?id=<?= $post['id'] ?>">編集</a> |
        <a href="delete.php?id=<?= $post['id'] ?>"
           onclick="return confirm('本当に削除しますか？');">削除</a>
    </p>
</body>
</html>
