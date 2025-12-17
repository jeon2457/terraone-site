<?php
// tel_input.php (PDO 버전)
session_start();

// 현재 페이지 URL 저장(다이렉트로 이 페이지로 진입시 진입차단, 
// 로그인 검증후에 다시 이 페이지로 진입허용!)
$_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

require './php/auth_check.php';   // 로그인 + 관리자 레벨 확인
require './php/db-connect-pdo.php';  // PDO 연결

// update_leaders_sms2.php 파일이 존재하는 경우에만 포함
if (file_exists('./php/update_leaders_sms2.php')) {
    require './php/update_leaders_sms2.php'; // 📌 회장/총무 자동 수집 함수 포함
}

date_default_timezone_set('Asia/Seoul');

// 관리자 여부 재확인
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] < 10) {
    echo "<script>
            alert('관리자만 접근할 수 있습니다.');
            location.href='./login.php';
          </script>";
    exit;
}

// DB에서 memo 불러오기
$memo = '';
$stmt = $pdo->prepare("SELECT memo FROM tel WHERE id = ? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $memo = $row['memo'] ?? '';
}

// POST 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password2 = trim($_POST['password2'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $tel = trim($_POST['tel'] ?? '');
    $addr = trim($_POST['addr'] ?? '');
    $remark = trim($_POST['remark'] ?? '');
    $sms = trim($_POST['sms'] ?? '');
    $sms_2 = trim($_POST['sms_2'] ?? '');
    $user_level = $_POST['user_level'] ?? 1;
    
    // 필수 입력 체크
    if ($id === '' || $password === '' || $name === '' || $tel === '') {
        echo "<script>alert('필수 입력 항목을 모두 입력해주세요.'); history.back();</script>";
        exit;
    }
    
    // 비밀번호 확인
    if ($password !== $password2) {
        echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
        exit;
    }
    
    // 비밀번호 해싱
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // 비고에 "회장" 또는 "총무"가 포함되어 있는지 확인
    $is_leader = (stripos($remark, '회장') !== false || stripos($remark, '총무') !== false);
    
    if ($is_leader) {
        // 본인 전화번호를 제외한 모든 회원의 전화번호를 수집
        $stmt_collect = $pdo->prepare("SELECT tel FROM tel WHERE tel != ? AND tel IS NOT NULL AND tel != '' ORDER BY name ASC");
        $stmt_collect->execute([$tel]);
        $all_tels = $stmt_collect->fetchAll(PDO::FETCH_COLUMN);
        
        // 콤마로 구분하여 SMS_2 생성
        $sms_2 = implode(',', $all_tels);
    }
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO tel (id, password, name, tel, addr, remark, sms, sms_2, user_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $hashedPassword, $name, $tel, $addr, $remark, $sms, $sms_2, $user_level]);
        
        // 회장/총무 등록 시 기존 회장/총무들의 SMS_2 업데이트
        if ($is_leader && function_exists('updateLeadersSms2')) {
            updateLeadersSms2($pdo);
        }
        
        $pdo->commit();
        
        echo "<script>alert('등록 완료'); location.href='admin_member.php';</script>";
        exit;
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("데이터 삽입 오류: " . $e->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>☏회원등록</title>

  <link rel="manifest" href="manifest.json">
  <meta name="msapplication-config" content="/browserconfig.xml">

  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="/favicon.png?v=2" />
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
  hr {
    margin: 25px 0;
    border-color: #ccc;
  }
  .info-badge {
    font-size: 0.85rem;
    margin-left: 8px;
  }

</style>

</head>
<body>

<div class="container">
  <h2 class="jikji35">회원 신규등록</h2>

  <!-- 🔥 PDO 방식: 자체 처리 -->
  <form id="member_tel" method="POST" onsubmit="return checkForm(this);">

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
    <input type="text" id="addr" name="addr" class="form-control"  placeholder="간단히 도시명만 기입"required>
  </div>

  <div class="mb-3">
    <label for="remark" class="form-label">비고(직책)<span class="badge bg-info info-badge">  회장/총무 입력 시 SMS_2 자동생성</span></label>
    <input type="text" id="remark" name="remark" class="form-control"
        placeholder="예) 임시, 회원, 총무, 회장 등등...">
  </div>

  <hr>

  <!-- SMS 정보 -->
  <div class="mb-3">
    <label for="sms" class="form-label asterisk">SMS(Tel)</label>
    <input type="text" id="sms" name="sms" class="form-control" maxlength="13" placeholder="'-' 없이 입력" required oninput="autoHyphen(this)">
  </div>

  <div class="mb-3">
    <label for="sms_2" class="form-label">SMS-2 단체</label>
    <input type="text" id="sms_2" name="sms_2" class="form-control"
       placeholder="예) 다중 문자메시지를 입력 (회장, 총무만 해당)">

  <!-- (SMS-2 강력한 자동입력 기능) 다중 전화번호 입력시 하이폰(-)이 자동입력되고 콤마(,)도 자동으로 찍힌다. -->
    <script>
    const sms2 = document.getElementById('sms_2');

    sms2.addEventListener('input', () => {
        let input = sms2.value.replace(/[^0-9]/g, ''); // 숫자만 추출
        let result = "";
        let i = 0;

        while (i < input.length) {
            let phone = input.substring(i, i + 11); // 11자리씩 자르기

            if (phone.length >= 10) {
                // 010-1234-5678 형태로 변환
                if (phone.length === 10) { 
                    phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
                } else {
                    phone = phone.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
                }

                result += phone + ","; // 콤마 자동 추가
            } else {
                result += phone; // 아직 불완전한 번호는 그냥 추가
            }

            i += 11; // 다음 11자리로 이동
        }

        // 마지막에 붙은 콤마 자동 제거
        result = result.replace(/,$/, "");

        sms2.value = result;
    });
    </script>

  </div>

  <hr>

  <!-- 회원 레벨 -->
  <div class="row mb-4">
    <div class="col-md-6">
      <label for="f_level" class="form-label asterisk">회원 레벨</label>
      <select name="user_level" id="f_level" class="form-select" required>
        <option value="">레벨 선택</option>
        <option value="1">게스트 (1)</option>
        <option value="2">정회원 (2)</option>
        <option value="10">관리자 (10)</option>
      </select>
    </div>
    <div class="col-md-6 d-flex align-items-end justify-content-end">
      <a href="./tel_edit.php" class="btn btn-danger mt-3 w-100">회원 삭제</a>
    </div>
  </div>

  <div class="text-center d-flex justify-content-center gap-3">
    <input type="submit" class="btn btn-success px-4" value="입력하기">
    <a href="admin_member.php" class="btn btn-secondary px-4">돌아가기</a>
  </div>

</form>

<script>
// 제출 확인
function checkForm(form) {
    return confirm("회원 정보를 등록하시겠습니까?");
}

// 숫자만 입력 허용 + 11자리 되면 자동 하이픈
function autoHyphen(el) {
    let num = el.value.replace(/[^0-9]/g, ""); // 숫자만
    let result = "";

    if (num.length < 4) {
        result = num;
    } else if (num.length < 8) {
        result = num.substring(0, 3) + "-" + num.substring(3);
    } else {
        result = num.substring(0, 3) + "-" + num.substring(3, 7) + "-" + num.substring(7, 11);
    }

    el.value = result;

    // 전화번호 입력될 때 SMS(Tel)에 자동 복사
    if (el.id === "f_tel") {
        document.getElementById("sms").value = result;
    }
}

// 페이지 로딩 후 전화번호 입력창과 SMS(Tel) 자동 연결
document.addEventListener("DOMContentLoaded", function () {

    const tel = document.getElementById("f_tel");
    const sms = document.getElementById("sms");

    // 입력될 때마다 SMS(Tel) 자동 복사
    tel.addEventListener("input", function () {
        sms.value = tel.value;
    });
});
</script>

</div>

  </body>
</html>




<!-- 
👉 간편한 이모지 아이콘 모음들

✅ 일반 강조 / 안내용
• 	👉 : 포인트 강조
• 	✅ : 완료, 승인
• 	📌 : 고정, 중요
• 	🔍 : 검색, 확인
• 	📝 : 작성, 기록
• 	📎 : 첨부, 연결

⚠️ 주의 / 경고 / 위험
• 	⚠️ : 일반적인 주의
• 	❗ : 강한 경고
• 	🚫 : 금지
• 	🔒 : 보안, 잠금
• 	🛑 : 정지
• 	🔥 : 긴급, 이슈

🌟 중요 / 추천 / 핵심
- ⭐ : 추천
- 📣 : 알림
- 💡 : 아이디어
- 🎯 : 목표
- 🏆 : 우수, 성과
- 🧭 : 방향, 가이드

🙂 친근함 / 감정 표현
- 🙂 : 기본 미소
- 😄 : 활짝 웃음
- 🤝 : 협력, 약속
- 🙌 : 환영, 축하
- 👋 : 인사
- 💬 : 대화, 코멘트

🎨 디자인 / 창의 / 작업
- 🎨 : 디자인
- 🧑‍💻 : 개발자
- 🛠️ : 설정, 수정
- 🧠 : 아이디어
- 📐 : 설계
- 🖌️ : 꾸미기

필요하신 테마나 상황에 맞춰 더 확장해드릴 수도 있어요.
예를 들어 "모임 공지용", "앱 알림용", "관리자 패널용" 등으로 맞춤 세트도 가능해요.

 -->
