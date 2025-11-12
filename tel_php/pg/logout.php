<?php
include "../php/db-connect.php";  // DB접속 연결
include "../php/member.php"; // 로그인 입력정보와 DB 데이타와 비교 유효성검사

$mem = new Member($db);
$mem->logout(); // ../inc/member.php
