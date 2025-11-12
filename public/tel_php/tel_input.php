<?php

session_start();

require './php/auth_check.php';
require './php/db-connect.php'; // mysqli 방식

// 로그인 여부와 관리자 레벨 확인
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 10) {
    echo "<script>
            alert('관리자만 접근할 수 있습니다.');
            location.href = './login.php';
          </script>";
    exit;
}


// ⭐ 맨 위에 반드시 이 인증절차 통과 코드가 있어야 합니다!
// ⭐ 관리자 인증 — 로그인 + 레벨10 확인 
// 해당 연결DB(데이타베이스) member테이블에 회원등록이 되어있어야한다.
// level(레벨위치)이 10으로 지정한 관리자만 페이지접속권한이 있다.



// DB 연결 확인
if (!$connect) {
    die("DB 연결 실패: " . mysqli_connect_error());
}

// 데이터 입력 처리 예시
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $tel = $_POST['tel'];
    $addr = $_POST['addr'];
    $remark = $_POST['remark'];
    $sms = $_POST['sms'];
    $sms_2 = $_POST['sms_2'];

    $sql = "INSERT INTO tel (id, name, tel, addr, remark, sms, sms_2, level)
            VALUES ('$id', '$name', '$tel', '$addr', '$remark', '$sms', '$sms_2', 10)";

    $result = mysqli_query($connect, $sql);

    if ($result) {
        echo "<script>alert('데이터가 저장되었습니다.'); location.href='tel_list.php';</script>";
    } else {
        echo "쿼리 오류: " . mysqli_error($connect);
    }
}

?>




<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>☏ 직지35 회원등록</title>
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-config" content="/browserconfig.xml">
    
    <!-- 파비콘 아이콘들 -->
    <link rel="icon" href="/favicon.ico?v=2" />

    <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
    <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
    <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />

    <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">
      
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
  body {
    background-color: #f4f6f9;
    font-size: 16px;
  }
  .container {
    max-width: 650px;
    margin: 40px auto;
    padding: 30px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  }
  h2.jikji35 {
    text-align: center;
    color: #007bff;
    font-weight: 700;
    margin-bottom: 40px;
  }
  label {
    font-weight: bold;
  }
  .asterisk::after {
    content: " *";
    color: red;
  }
  input[type="submit"] {
    width: 150px;
    font-size: 18px;
  }
  hr {
    margin: 25px 0;
    border-color: #ccc;
  }
</style>
</head>
<body>

<div class="container">
  <h2 class="jikji35">황악회원 신규 등록</h2>
  <form id="member_tel" method="POST" action="tel_submit.php" onsubmit="return checkForm(this);">

    <!-- 아이디 -->
    <div class="mb-3 row align-items-end">
      <div class="col-8">
        <label for="f_id" class="form-label asterisk">아이디</label>
        <input type="text" name="id" id="f_id" class="form-control" required placeholder="아이디를 입력하세요.">
      </div>
    </div>

    <!-- 비밀번호 -->
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="f_password" class="form-label asterisk">비밀번호</label>
        <input type="password" name="password" id="f_password" class="form-control" required placeholder="비밀번호">
      </div>
      <div class="col-md-6">
        <label for="f_password2" class="form-label asterisk">비밀번호 확인</label>
        <input type="password" name="password2" id="f_password2" class="form-control" required placeholder="비밀번호 확인">
      </div>
    </div>

    <hr>

    <!-- 이름 / 전화 / 주소 -->
    <div class="mb-3">
      <label for="name" class="form-label asterisk">이름</label>
      <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="f_tel" class="form-label asterisk">전화번호</label>
      <input type="text" name="tel" id="f_tel" class="form-control" maxlength="13" placeholder="'-' 없이 입력" required oninput="autoHyphen(this)">
    </div>

    <div class="mb-3">
      <label for="addr" class="form-label asterisk">거주지</label>
      <input type="text" id="addr" name="addr" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="remark" class="form-label">비고(직책)</label>
      <input type="text" id="remark" name="remark" class="form-control">
    </div>

    <hr>

    <!-- SMS 정보 -->
    <div class="mb-3">
      <label for="sms" class="form-label asterisk">SMS(Tel)</label>
      <input type="text" id="sms" name="sms" class="form-control" maxlength="13" placeholder="'-' 없이 입력" required oninput="autoHyphen(this)">
    </div>

    <div class="mb-3">
      <label for="sms_2" class="form-label">SMS-2 단체</label>
      <input type="text" id="sms_2" name="sms_2" class="form-control">
    </div>

    <hr>

    <!-- 회원 레벨 및 삭제 -->
    <div class="row mb-4">
      <div class="col-md-6">
        <label for="f_level" class="form-label asterisk">회원 레벨</label>
        <select name="level" id="f_level" class="form-select" required>
          <option value="">레벨 선택</option>
          <option value="1">게스트 (1)</option>
          <option value="2">일반회원 (2)</option>
          <option value="10">관리자 (10)</option>
        </select>
      </div>
      <div class="col-md-6 d-flex align-items-end justify-content-end">
        <a href="./member/member_delete.php" class="btn btn-danger w-100">회원 삭제</a>
      </div>
    </div>

    <div class="text-center d-flex justify-content-center gap-3">
      <input type="submit" class="btn btn-success px-4" value="입력하기">
      <a href="tel_select.php" class="btn btn-secondary px-4">돌아가기</a>
    </div>

  </form>
</div>

<script>
function checkForm(form) {
  // 비밀번호 확인
  const pw1 = document.getElementById("f_password").value;
  const pw2 = document.getElementById("f_password2").value;
  if (pw1 !== pw2) {
    alert("비밀번호가 일치하지 않습니다!");
    return false;
  }

  if (form.submitted) {
    alert("이미 전송된 데이터입니다.");
    return false;
  }
  form.submitted = true;
  return true;
}

function autoHyphen(target) {
  target.value = target.value
    .replace(/[^0-9]/g, '')
    .replace(/^(\d{2,3})(\d{3,4})(\d{4})$/, `$1-$2-$3`);
}

// 전화번호 입력 시 SMS 자동 동기화
const telInput = document.getElementById('f_tel');
const smsInput = document.getElementById('sms');
telInput.addEventListener('input', () => {
  const telValue = telInput.value.replace(/[^0-9]/g, '');
  smsInput.value = telValue.replace(/^(\d{3})(\d{3,4})(\d{4})$/, '$1-$2-$3');
});
</script>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
