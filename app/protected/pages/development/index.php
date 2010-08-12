<?php
//print_r($_params);
$data = array();
$page = array();
$page['section'] = 2;
require_once($_SERVER['DOCUMENT_ROOT'].'/app/protected/classes/cms_frontend.php');

$data['submenu'] = <<<EOD
<li><a href="#">Printers</a></li>
    <li><a href="#">Cameras</a></li>
    <li class="exp"><a href="#"><span class="bold">RFID</span></a>
        <ul>
        <li><a href="#">Passive LF</a></li>
        <li><a href="#">Passive HF Mode1 (ISO 15693)</a></li>
        <li><a href="#">Passive HF Mode 2 (Magellan PJM)</a></li>
        <li><a href="#">Passive UHF</a></li>
        <li class="exp"><a href="#"><span class="bold">Active</span></a>
            <ul>
            <li><a href="#">Identec Readers</a></li>
            <li class="curi"><a href="#">Identec Antennas</a></li>
            <li><a href="#">Identec Tags Inlets and Labels</a></li>
            <li><a href="#">Identec Accessories</a></li>
            </ul>
        </li>
        <li><a href="#">Handheld readers</a></li>
        <li><a href="#">Printers</a></li>
        <li><a href="#">Tags</a></li>
        <li><a href="#">Antennas</a></li>
        </ul>
    </li>
    <li><a href="#">Fixed position scanners</a></li>
    <li><a href="#">OEM barcode scan machines</a></li>
    <li><a href="#">Communications</a></li>
EOD;

view('header',$page);
view('page',$data,false,0); //cache time in minutes
view('footer');
?>