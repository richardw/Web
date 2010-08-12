<?php
// error reporting and levels
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');

// for reporting puroses
define('ZEST_START_TIME', microtime());
define('ZEST_INIT',true);
require 'zest/zest.php';
?>