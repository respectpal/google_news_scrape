<?php
require_once dirname(__FILE__).'/db.php';

if( !defined( "CHAR_SET" ) ){
	define("CHAR_SET", "UTF-8");
}
mb_language('Japanese');
mb_internal_encoding('UTF-8');

define ('TEMPLATES_DIR', dirname(__FILE__).'/../templates/');
