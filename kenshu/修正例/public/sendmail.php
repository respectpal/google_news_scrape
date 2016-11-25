<?php
ob_start();
require_once '../batch/sendmail.php';
$batch_output = ob_get_clean();

require TEMPLATES_DIR.'sendmail.php';