<?php
require 'db.php';

$stmt = $pdo->query('SELECT * FROM posts ORDER BY created_at DESC');
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>記事一覧</title>
</head>
<body>
    <h1>記事一覧</h1>
    <p><a href="create.php">＋ 新規投稿</a></p>

    <?php if (empty($posts)): ?>
        <p>記事がありません。</p>
    <?php else: ?>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li>
                    <a href="show.php?id=<?= $post['id'] ?>">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                    （<?= htmlspecialchars($post['author']) ?> /
                    <?= $post['created_at'] ?>）
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
