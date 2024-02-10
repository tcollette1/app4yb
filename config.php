<?php
$infotxt = file_get_contents('configtree.txt'); 
$info = json_decode($infotxt, true);
$host = $info['host'];
$db = $info['database'];
$user = $info['user'];
$pass = $info['password'];
$year = $info['year'];
$timezone = $info['timezone'];
$admin = $info['admin'];
$domain = $info['domain'];
$admin_email = $info['admin_email'];
$min_words = $info['min_words'];
$cap_min_words = $info['cap_min_words'];
date_default_timezone_set($timezone);
?>