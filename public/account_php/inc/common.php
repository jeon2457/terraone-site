<?php
session_start();


// error_reporting(E_ALL);
// ini_set('display_errors', 1);


// ligin_process.php에서 $ses_id 정의!(로그인 유무확인!)
$ses_id = (isset($_SESSION['ses_id']) && $_SESSION['ses_id'] != '') ? $_SESSION['ses_id'] : '';
$ses_level = (isset($_SESSION['ses_level']) && $_SESSION['ses_level'] != '') ? $_SESSION['ses_level'] : '';









