<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>タイトル</title>
</head>
<body>

<?php

try{
	$dbh = new PDO('mysql:host=localhost; dbname=kadai','kadai_user','kandai_pass');
}catch (PDOException $e){
	var_dump ($e->getMessage());
	exit;
}


$eraseid = $_POST['eraseid'];
$username = $_POST['username'];
$userpass = $_POST['userpass'];

echo "ERASEID:" . $eraseid . "のキーワードを削除します。";
echo "<br>";

$stmt = $dbh->query("delete from " . $username . $userpass . " where id = " . $eraseid);

echo $stmt->rowCount() . "個のキーワードを削除しました。";


$dbh = null;
?>

<form action="main.php" method="post">
<input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
<input type="hidden" name="userpass" value="<?php echo htmlspecialchars($userpass); ?>">
<input type="submit" value="戻る"></form>
</body>
</html>