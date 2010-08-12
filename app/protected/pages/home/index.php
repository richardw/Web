<?php
$data = array();
$page = array();
$page['section'] = 1;
require_once($_SERVER['DOCUMENT_ROOT'].'/app/protected/classes/cms_frontend.php');

$data['news'] = <<<EOD
<li><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
<li><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
<li><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
EOD;

view('header',$page);
view('home',$data,false,0); //cache time in minutes
view('footer',$page);
?>