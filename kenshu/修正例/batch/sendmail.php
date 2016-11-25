<?php
require_once dirname(__FILE__).'/../lib/scraping.php';

//ユーザー代入
$users = get_all_users();

$results = array();
foreach ($users as $senduser) {
	$results[] = send_report_mail_to_user($senduser['name'], $senduser['pass'], $senduser['email']);
}

foreach ($results as $result) {
	if ($result['success']) {
		echo 'メール送信に成功致しました。'."\n";
	} else {
		echo 'メール送信に失敗致しました。'."\n";
	}
	echo 'To:'.$result['to']."\n";
	echo 'Username:'.$result['username']."\n";
	echo 'Message:'."\n".$result['message']."\n";
}