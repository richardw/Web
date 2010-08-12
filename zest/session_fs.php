<?php
if(isset($config['session']['filepath']))
  session_save_path($config['session']['filepath']);

session_name($config['session']['name']);
session_start();
setcookie($config['session']['name'],$_COOKIE[$config['session']['name'],time()+($config['session']['expire']*60));
?>