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


$keywords = $_POST['keywords'];
$username = $_POST['username'];
$userpass = $_POST['userpass'];

echo "キーワード:" . $keywords . "を登録します。";
echo "<br>";
echo $username . $userpass;

$stmt = $dbh->query("insert into " . $username . $userpass . " (keyword) value ('" . $keywords . "');");

echo "<br>";
echo $stmt->rowCount() . "個のキーワードを登録しました。";


$dbh = null;
?>

<form action="main.php" method="post">
<input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
<input type="hidden" name="userpass" value="<?php echo htmlspecialchars($userpass); ?>">
<input type="submit" value="戻る"></form>
</body>
</html>