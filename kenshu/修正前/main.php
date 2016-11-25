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

$username = $_POST['username'];
$userpass = $_POST['userpass'];



echo "<h2>現在のキーワード：</h2><br><br>";
$stmt = $dbh->query("select * from " . $_POST['username'] . $_POST['userpass']);
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $keylist){
	echo "<h3>";
	echo $keylist['keyword'];
?>
<form action="erase.php" method="post">
<input type="hidden" name="eraseid" value="<?php echo htmlspecialchars($keylist['id']); ?>">
<input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
<input type="hidden" name="userpass" value="<?php echo htmlspecialchars($userpass); ?>">
<input type="submit" value="削除"></form>
<?php
	echo "</h3>";
}
?>
<form action="keytouroku.php" method="post">
<input type="text" name="keywords" id="keywords">
<input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
<input type="hidden" name="userpass" value="<?php echo htmlspecialchars($userpass); ?>">
<input type="submit" value="新規登録"></form>
<?php

$stmt = $dbh->query("select * from " . $username . $userpass);
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $seeking){
	
$url = urlencode($seeking['keyword']);

require_once "simple_html_dom.php";

//define("CHAR_SET","UTF-8");
if( !defined( "CHAR_SET" ) ){
define("CHAR_SET", "UTF-8");
}
mb_language('Japanese');

$html = file_get_contents('https://www.google.com/search?hl=ja&gl=jp&tbm=nws&q=' . $url);
$html = mb_convert_encoding($html, CHAR_SET, "auto");
$gHtml = str_get_html($html);

/*foreach($gHtml->find('li[class="g"] a') as $urldata){
echo $urldata->href;
echo '<br>';		
}*/


//記事タイトルのインサート
foreach($gHtml->find('h3') as $titledata){
$stmt = $dbh->prepare("insert into " . $username . $userpass . "titles (title, date) values (?,?)");
$stmt->execute(array($titledata->plaintext,time()));
}
}

//mySQLからタイトルデータの表示
$call = "select * from " . $username . $userpass . "titles";
$calling = $dbh->query($call);
foreach ($calling->fetchAll(PDO::FETCH_ASSOC) as $titles){
	echo $titles['title'] ;

	echo "<br>";
}


$dbh = null;
?>
</body>
</html>