<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>タイトル</title>
</head>
<body>
	キーワード:<?php echo $keywords; ?>を登録します。<br>
	<?php echo $username . $userpass; ?><br>
	<?php echo $count; ?>個のキーワードを登録しました。

	<form action="main.php" method="post">
		<input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
		<input type="hidden" name="userpass" value="<?php echo htmlspecialchars($userpass); ?>">
		<input type="submit" value="戻る">
	</form>
</body>
</html>