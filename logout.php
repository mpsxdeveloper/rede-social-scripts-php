<?php

$_SESSION = array();
$home_url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php';
header('Location: '.$home_url);