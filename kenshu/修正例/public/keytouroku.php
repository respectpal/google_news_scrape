<?php
require_once '../lib/scraping.php';

$keywords = $_POST['keywords'];
$username = $_POST['username'];
$userpass = $_POST['userpass'];

$count = register_keyword($username, $userpass, $keywords);

require TEMPLATES_DIR.'keytouroku.php';