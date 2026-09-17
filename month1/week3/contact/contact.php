<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お問い合わせフォーム</title>
<style>
  /* ベージュ系の背景に小さなドット柄を重ねる（radial-gradientを敷き詰める） */
  body {
    margin: 0;
    padding: 24px;
    min-height: 100vh;
    box-sizing: border-box;
    background-color: #f3ead9;
    background-image: radial-gradient(#e0cdaf 1.5px, transparent 1.5px);
    background-size: 22px 22px;
    font-family: "Hiragino Maru Gothic ProN", "Yu Gothic", "Rounded Mplus 1c", "Segoe UI", sans-serif;
    color: #6b5942;
    display: flex;
    justify-content: center;
  }

  /* フォーム全体をカード風に見せる箱 */
  .card {
    width: 100%;
    max-width: 480px;
    background: #fffaf2;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(120, 96, 60, 0.18);
    padding: 32px 28px;
    box-sizing: border-box;
  }

  h1 {
    text-align: center;
    color: #8a6d4a;
    font-size: 22px;
    margin-top: 0;
    margin-bottom: 24px;
  }

  label { display: block; margin-bottom: 4px; font-weight: bold; color: #7a6248; }

  /* 入力欄：角丸＋フォーカス時に色が変わるようtransitionを付ける */
  input, textarea {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 16px;
    border: 1px solid #d9c6a8;
    border-radius: 10px;
    background: #fffdf8;
    font-size: 14px;
    font-family: inherit;
    color: #5b4a36;
    box-sizing: border-box;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  input:focus, textarea:focus {
    outline: none;
    border-color: #b8945f;
    box-shadow: 0 0 0 3px rgba(184, 148, 95, 0.2);
  }

  /* 送信ボタン：ブラウン系＋ホバーで少し濃く */
  button {
    display: block;
    width: 100%;
    padding: 10px 20px;
    font-size: 15px;
    font-family: inherit;
    font-weight: bold;
    color: #fff;
    background: #b3895c;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }
  button:hover { background: #97714a; }

  .result { background: #f4ecd8; padding: 16px; border-radius: 12px; margin-top: 8px; }
  .result h2 { margin-top: 0; color: #8a6d4a; font-size: 18px; }
  .errors { background: #fbeee4; color: #a85c3a; padding: 16px; border-radius: 12px; margin-bottom: 16px; }
  .errors ul { margin: 0; padding-left: 20px; }

  /* スマホ表示ではカードの余白を少し詰める */
  @media (max-width: 480px) {
    body { padding: 12px; }
    .card { padding: 24px 18px; }
  }
</style>
</head>
<body>

<div class="card">

<h1>お問い合わせフォーム</h1>

<?php
// 送信成功時にフォームを隠して確認画面だけ出すためのフラグ
$isSuccess = false;

// エラーメッセージを溜めておく配列
$errors = [];

// 送信内容を保持する変数（入力保持・確認画面表示の両方に使う）
$name    = "";
$email   = "";
$subject = "";
$message = "";

// $_SERVER["REQUEST_METHOD"] で POST 送信されたときだけバリデーション処理を行う
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // trim で前後の空白を除去してから受け取る
    $name    = trim($_POST["name"] ?? "");
    $email   = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // 必須チェック：すべての項目が空でないか確認する
    if ($name === "") {
        $errors[] = "名前を入力してください。";
    }
    if ($email === "") {
        $errors[] = "メールアドレスを入力してください。";
    }
    if ($subject === "") {
        $errors[] = "件名を入力してください。";
    }
    if ($message === "") {
        $errors[] = "メッセージを入力してください。";
    }

    // メールアドレスの形式チェック（未入力の場合は必須チェックのエラーのみ表示する）
    if ($email !== "" && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = "メールアドレスの形式が正しくありません。";
    }

    // エラーが1つも無ければ送信成功とみなす
    if (empty($errors)) {
        $isSuccess = true;
    }
}
?>

<?php if (!empty($errors)): ?>
  <!-- バリデーションエラーがある場合はまとめて表示する -->
  <div class="errors">
    <ul>
      <?php foreach ($errors as $error): ?>
        <!-- htmlspecialchars で XSS 対策（エラーメッセージ自体は固定文字列だが念のため統一） -->
        <li><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if ($isSuccess): ?>

  <!-- 送信成功時：入力内容の確認画面を表示する -->
  <div class="result">
    <h2>送信内容を確認しました</h2>
    <!-- htmlspecialchars でエスケープしてから出力することでXSS（スクリプト埋め込み）を防ぐ -->
    <p><strong>名前：</strong><?php echo htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?></p>
    <p><strong>メールアドレス：</strong><?php echo htmlspecialchars($email, ENT_QUOTES, "UTF-8"); ?></p>
    <p><strong>件名：</strong><?php echo htmlspecialchars($subject, ENT_QUOTES, "UTF-8"); ?></p>
    <p><strong>メッセージ：</strong><?php echo nl2br(htmlspecialchars($message, ENT_QUOTES, "UTF-8")); ?></p>
  </div>

<?php else: ?>

  <!-- 未送信、またはエラーがある場合はフォームを表示する -->
  <!-- action を省略すると自分自身（contact.php）に送信される -->
  <form method="POST">
    <label>名前</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?>" placeholder="山田太郎">

    <label>メールアドレス</label>
    <input type="text" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, "UTF-8"); ?>" placeholder="example@example.com">

    <label>件名</label>
    <input type="text" name="subject" value="<?php echo htmlspecialchars($subject, ENT_QUOTES, "UTF-8"); ?>" placeholder="お問い合わせの件名">

    <label>メッセージ</label>
    <textarea name="message" rows="5" placeholder="お問い合わせ内容をご記入ください"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></textarea>

    <button type="submit">送信</button>
  </form>

<?php endif; ?>

</div>

</body>
</html>
