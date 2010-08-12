<?php

$_dbconn = @new mysqli($config['db'][$config["status"].'_location'], $config['db'][$config["status"].'_user'], $config['db'][$config["status"].'_password'], $config['db'][$config["status"].'_name']);

if ($_dbconn->connect_errno) {
	die('Connect Error: ' . $_dbconn->connect_errno);
}	

function db_query($q,$returnid=false) {
	global $_dbconn;
	$result = mysqli_query($_dbconn,$q) or die(mysqli_error($_dbconn));
	if (!$result)
		die(mysqli_error($dbc));
	if($returnid==true)
		return mysqli_insert_id($this->conn);
	return $result;
}
?>