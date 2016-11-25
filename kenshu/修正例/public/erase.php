<?php
require_once '../lib/scraping.php';

$eraseid  = $_POST['eraseid'];
$username = $_POST['username'];
$userpass = $_POST['userpass'];

$count = erase_keyword($username, $userpass, $eraseid);

require TEMPLATES_DIR.'erase.php';