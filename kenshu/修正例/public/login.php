<?php
require_once '../lib/scraping.php';

$username = $_POST['liname'];
$userpass = $_POST['lipass'];

$logchk = check_login($username, $userpass);

if ($logchk) {
	$result  = update_news_list($username, $userpass);
	$calling = get_news_titles($username, $userpass);
	require TEMPLATES_DIR.'login.php';
} else {
	require TEMPLATES_DIR.'login_failed.php';
}