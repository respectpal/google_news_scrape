<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>タイトル</title>
</head>
<body>
	<?php if ($result): ?>
		登録が完了しました<br>
		<h1>ログイン</h1>
		<form action="login.php" method="post">
			ユーザー名： <input type="text" name="liname" id="liname"><br>
			<br>
			パスワード：　<input type="text" name="lipass" id="lipass"><br>
			<input type="submit">
		</form>
	<?php else: ?>
		登録に失敗しました。<br>
		<br>
		<a href="index.php">戻る</a>
	<?php endif; ?>
</body>
</html>
