<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>タイトル</title>
</head>
<body>
	ERASEID:<?php echo $eraseid; ?>のキーワードを削除します。<br>
	<?php echo $count; ?>個のキーワードを削除しました。

	<form action="main.php" method="post">
		<input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
		<input type="hidden" name="userpass" value="<?php echo htmlspecialchars($userpass); ?>">
		<input type="submit" value="戻る">
	</form>
</body>
</html>