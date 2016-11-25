<?php

try{
	$dbh = new PDO('mysql:host=localhost; dbname=kadai','kadai_user','kadai_pass');
}catch (PDOException $e){
	var_dump ($e->getMessage());
	exit;
}

//今日の日付の取得
$yesterday = time() - (24 * 60 *60);





//ユーザー代入
$stmt = $dbh->query("select * from users;");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $senduser){

	$stmt2 = $dbh->query("select * from " . $senduser['name'] . $senduser['pass']);
	foreach($stmt2->fetchAll(PDO::FETCH_ASSOC) as $seeking){
	
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
	$stmt = $dbh->prepare("insert into " . $senduser['name'] . $senduser['pass'] . "titles (title, date) values (?,?)");
	$stmt->execute(array($titledata->plaintext,time()));
	}
	}

	//
	$subjkey = '';
	$subjtitles = '';
 
	$stmt2 = $dbh->query("select * from " . $senduser['name'] . $senduser['pass'] . ";");
	foreach($stmt2->fetchAll(PDO::FETCH_ASSOC) as $sendkey){
		 $subjkey = $subjkey . $sendkey['keyword'] . "\n";
	}

	$stmt3 = $dbh->query("select * from " . $senduser['name'] . $senduser['pass'] . "titles where date >= " . $yesterday . ";");
	foreach($stmt3->fetchAll(PDO::FETCH_ASSOC) as $sendtitles){
		 $subjtitles = $subjtitles . $sendtitles['title'] . "\n" ;
	}


	//初期化
	$sTo = '';
	$sSubject = '';
	$sMessage = '';

	//送信先
	$sTo = $senduser['email'];

	//メールタイトル
//	$sSubjct = "課題メール" ；

	//本文
	$sMessage =
	$senduser['name'] . "さん。\n\nあなたの現在のキーワード：\n" . 
	$subjkey . "\n\n\n"
	 . "本日のニュースは以下の通りです。\n\n" . 
	$subjtitles;

	//ヘッダー
	$sHeaders = "From: Yamada Taro\r\n";

	//送信処理
	mb_language('ja');
	mb_internal_encoding('UTF-8');
	if(mb_send_mail($sTo, $sSubject, $sMessage, $sHeaders)){
		echo 'メール送信に成功致しました。<br/>';
	}else{
		echo 'メール送信に失敗致しました。<br/>';
	}

}

echo $sTo;
echo $sMessage;
echo $subjkey;
echo $subjtitles;

$dbh = null;
?>