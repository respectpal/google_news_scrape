<?php
require_once '../lib/scraping.php';

$username = $_POST['username'];
$userpass = $_POST['userpass'];

$result  = update_news_list($username, $userpass);
$calling = get_news_titles($username, $userpass);

require TEMPLATES_DIR.'main.php';