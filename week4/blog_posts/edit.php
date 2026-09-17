<?php
require 'db.php';

$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author = trim($_POST['author'] ?? '');

    if ($title !== '' && $content !== '' && $author !== '') {
        $stmt = $pdo->prepare('UPDATE posts SET title = ?, content = ?, author = ? WHERE id = ?');
        $stmt->execute([$title, $content, $author, $id]);
        header('Location: show.php?id=' . $id);
        exit;
    } else {
        $error = 'すべての項目を入力してください。';
    }
}

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
    <title>記事の編集</title>
</head>
<body>
    <p><a href="show.php?id=<?= $post['id'] ?>">← 詳細に戻る</a></p>
    <h1>記事の編集</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="edit.php">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <p>
            タイトル<br>
            <input type="text" name="title" maxlength="200" required
                   value="<?= htmlspecialchars($_POST['title'] ?? $post['title']) ?>">
        </p>
        <p>
            著者<br>
            <input type="text" name="author" maxlength="50" required
                   value="<?= htmlspecialchars($_POST['author'] ?? $post['author']) ?>">
        </p>
        <p>
            本文<br>
            <textarea name="content" rows="10" cols="60" required><?= htmlspecialchars($_POST['content'] ?? $post['content']) ?></textarea>
        </p>
        <button type="submit">更新する</button>
    </form>
</body>
</html>