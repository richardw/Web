<?php
//print_r($_params);
$data = array();
$page = array();
$page['section'] = 70;
require_once($_SERVER['DOCUMENT_ROOT'].'/app/protected/classes/cms_frontend.php');

view('header',$page);
view('page',$data,false,0); //cache time in minutes
view('footer');
?>