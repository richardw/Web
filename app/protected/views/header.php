<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Blackroc Technology - <?php echo $title; ?></title>
<link rel="stylesheet" href="/app/public/css/screen.css?v=1" type="text/css" />
<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="/app/public/css/screen_ie.css?v=1" /><![endif]-->
<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="/app/public/css/screen_ie67.css?v=1" /><![endif]-->
<link rel="shortcut icon" href="/app/public/img/favicon.ico" />
<?php
if($description!='')
    echo '<meta name="Description" content="'.$description.'">';
if($keywords!='')
    echo '<meta name="Keywords" content="'.$keywords.'">';
?>
</head>
<body>
<div id="cont">
<div id="logo" class="spr"><span>Blackroc Technology - Engineered solutions in data capture</span></div>
<div id="ph">+44 (0)1785 218500</div>
<nav>
<ul id="m">
<li id="m_h"<?php if($section==1) echo ' class="firstcur"'; else echo ' class="first"'; ?>><a href="/">Home</a></li>
<li id="m_prod"<?php if($section==3) echo ' class="cur"'; ?>><a href="/products">Products</a></li>
<li id="m_prodd"<?php if($section==2) echo ' class="cur"'; ?>><a href="/development">Product Development</a></li>
<li id="m_prof"<?php if($section==66) echo ' class="cur"'; ?>><a href="/services">Professional Services</a></li>
<li id="m_t"<?php if($section==67) echo ' class="lastcur"'; else echo ' class="last"'; ?>><a href="/technologies">Technologies</a></li>
</ul>
</nav>

<div id="main" class="rt">
    <div class="tl"></div><div class="tr"></div>