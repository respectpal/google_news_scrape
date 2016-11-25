<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>タイトル</title>
</head>
<body>

<?php

try{
	$dbh = new PDO('mysql:host=localhost; dbname=kadai','kadai_user','kadai_pass');
}catch (PDOException $e){
	var_dump ($e->getMessage());
	exit;
}


$toname = $_POST['toname'];
$topass = $_POST['topass'];
$toadd = $_POST['toadd'];


$stmt = $dbh->query("insert into users (name,pass,email) values ('" . $toname . "','" . $topass . "','" . $toadd . "');");

if ($stmt = true){
echo "登録が完了しました<br>";
$stmt2 = $dbh->query("create table " . $toname . $topass . " (id int not null auto_increment, keyword varchar(30) unique, primary key(id));");

$stmt3 = $dbh->query("create table " . $toname . $topass . "titles (id int not null auto_increment, title varchar(150) unique, date varchar(30), primary key(id));");

?>
<h1>ログイン</h1>
<form action="login.php" method="post">
ユーザー名： <input type="text" name="liname" id="liname"><br><br>
パスワード：　<input type="text" name="lipass" id="lipass"><br>
<input type="submit">
</form>
</body>
</html>
<?php

$dbh = null;

} else {
	echo "登録に失敗しました。<br><br>";
	?><a href="index.html">戻る</a><?php
	$dbh = null;

}



?>
