<?php
require_once '../lib/scraping.php';

$username = $_POST['toname'];
$userpass = $_POST['topass'];
$useremail = $_POST['toadd'];

$result = create_user($username, $userpass, $useremail);

require TEMPLATES_DIR.'touroku.php';