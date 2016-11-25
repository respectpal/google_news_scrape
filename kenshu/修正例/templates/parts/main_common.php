	<h2>現在のキーワード：</h2>
	<br>
	<br>
	<?php foreach($result as $keylist): ?>
		<h3>
			<?php echo $keylist['keyword']; ?>
			<form action="erase.php" method="post">
				<input type="hidden" name="eraseid" value="<?php echo htmlspecialchars($keylist['id']); ?>">
				<input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
				<input type="hidden" name="userpass" value="<?php echo htmlspecialchars($userpass); ?>">
				<input type="submit" value="削除">
			</form>
		</h3>
	<?php endforeach; ?>

	<form action="keytouroku.php" method="post">
		<input type="text" name="keywords" id="keywords">
		<input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
		<input type="hidden" name="userpass" value="<?php echo htmlspecialchars($userpass); ?>">
		<input type="submit" value="新規登録">
	</form>

	<?php foreach ($calling as $titles): ?>
		<?php echo $titles['title']; ?><br>
	<?php endforeach; ?>
