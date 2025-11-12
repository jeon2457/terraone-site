<?php
// 로그인 절차과정을 처리하는 페이지!
// 로그인 폼에서는 비밀번호를 'pw'로 간략하게 설정했다.

$id = (isset($_POST['id']) && $_POST['id'] != '') ? $_POST['id'] : ''; // id 세팅이 되어있으면서  id 값이 비어 있지 않은 경우에는 해당 id 값을 사용하고, 그렇지 않은 경우에는 빈 문자열을 id 변수에 할당합니다.

$pw = (isset($_POST['pw']) && $_POST['pw'] != '') ? $_POST['pw'] : ''; 



if($id == '') { // 만약에 id가 비어있다면
  $arr = ['result' => 'empty_id'];
  die(json_encode($arr));  // {"result" : "empty_id"} json방식으로 변경하고 빠져나간다
}

if($pw == '') {
  $arr = ['result' => 'empty_pw'];
  die(json_encode($arr));  // {"result" : "empty_pw"} json방식으로 변경하고 빠져나간다
}

include "../php/db-connect.php";  // DB접속 연결
include "../php/member.php"; // 로그인 입력정보와 DB 데이타와 비교 유효성검사



$mem = new Member($db);  // ../inc/member.php

if ($mem->login($id, $pw)) {  //$id와 $pw 2개 인자를 같이 받는 방식으로 진행(../inc/member.php 파일과 연계)

  $arr = ['result' => 'login_success']; //로그인 성공

  $memArr = $mem->getInfo($id);


  session_start(); // 로그인하면 세션을 저장시킨다
  $_SESSION['ses_id'] = $id;
  $_SESSION['ses_level'] = $memArr['level']; // 일반하고 관리자하고 구분을 두기위해서 사용


} else {
  $arr = ['result' => 'login_fail']; //로그인 실패
}

die(json_encode($arr));