<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author = trim($_POST['author'] ?? '');

    if ($title !== '' && $content !== '' && $author !== '') {
        $stmt = $pdo->prepare('INSERT INTO posts (title, content, author) VALUES (?, ?, ?)');
        $stmt->execute([$title, $content, $author]);
        header('Location: index.php');
        exit;
    } else {
        $error = 'すべての項目を入力してください。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規投稿</title>
</head>
<body>
    <p><a href="index.php">← 一覧に戻る</a></p>
    <h1>新規投稿</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="create.php">
        <p>
            タイトル<br>
            <input type="text" name="title" maxlength="200" required
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </p>
        <p>
            著者<br>
            <input type="text" name="author" maxlength="50" required
                   value="<?= htmlspecialchars($_POST['author'] ?? '') ?>">
        </p>
        <p>
            本文<br>
            <textarea name="content" rows="10" cols="60" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
        </p>
        <button type="submit">投稿する</button>
    </form>
</body>
</html>
