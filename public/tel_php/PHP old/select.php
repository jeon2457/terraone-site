<?php
// tel_select.php
session_start();
require './php/auth_check.php';
require './php/db-connect-pdo.php';
// 🔥[중요!] 인증(세션) 관련 코드는 반드시 HTML 출력보다 먼저 실행해야 합니다. <?php 코드는 무조건 1행에 공백없이 제일앞에 와야함!

// 관리자 정보
$admin_id = htmlspecialchars($_SESSION['user_id']);
$admin_level = htmlspecialchars($_SESSION['user_level']);

?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>회원관리선택</title>

  <link rel="manifest" href="manifest.json">
  <meta name="msapplication-config" content="/browserconfig.xml">

<!-- 파비콘 아이콘들 -->
<link rel="icon" href="/favicon.png?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="./favicons/2/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="./favicons/2/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="./favicons/2/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="./favicons/2/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="./favicons/2/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="./favicons/2/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="./favicons/2/apple-icon-72x72.png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    background-color: #f4f6f9;
    font-size: 16px;
}

.container {
    max-width: 650px;
    margin: 50px auto;
    padding: 35px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.1);
}

/* 🔹 공통 제목 스타일 (상단 '회원관리 선택'과 동일 디자인) */
.section-title {
    text-align:center; 
    color:#007bff; 
    font-weight:700; 
    margin-bottom:30px; 
    padding:10px; 
    background:#e9f3ff; 
    border-radius:10px;
    border:1px solid #c9e3ff;
}


.admin-info {
    text-align: right;
    font-size: 15px;
    color: #6c757d;
    margin-bottom: 20px;
}

.option-box {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* 🔹 공통 카드 스타일 */
.select-card {
    display: flex;
    align-items: center;
    gap: 15px;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 18px;
    transition: all 0.2s ease-in-out;
    cursor: pointer;

    /* 은은한 기본 배경색 */
    background-color: #fafbfc;
}

/* 🔹 선택 / hover 시 강조 */
.select-card:hover {
    border-color: #007bff;
    box-shadow: 0 6px 16px rgba(13, 110, 253, 0.1);
    transform: translateY(-3px);
}

.select-card.active {
    border-color: #0d6efd;
    box-shadow: 0 8px 20px rgba(13, 110, 253, 0.15);
    background-color: #f1f5ff;
}

/* 🔹 체크박스 */
.select-card input[type="checkbox"] {
    width: 22px;
    height: 22px;
}

/* 🔹 마지막 항목: 회원 전화연락망 관리만 다른 배경 적용 */
.select-card.tel-section {
    background-color: #e7f5ff;   /* 은은한 하늘색 */
    border-color: #b5e1ff;
}

.select-card.tel-section:hover {
    background-color: #d7efff;
    border-color: #58b7ff;
}

/* 텍스트 스타일 */
.select-card h5 {
    font-size: 18px;
    margin-bottom: 4px;
}

.select-card p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

/* 버튼 영역 */
.btn-area {
    margin-top: 30px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
}

.btn-same {
    width: 100%;
    max-width: 300px;
    padding: 14px 0 !important;
    font-size: 1.25rem !important;
}

@media (max-width: 480px) {
    .container {
        padding: 25px;
    }
}

</style>
</head>

<body>
<div class="container">
  <h2 class="section-title">회원관리 선택</h2>

  <div class="admin-info">
    👤 관리자: <strong><?= $admin_id ?></strong> (Level <?= $admin_level ?>)
  </div>

  <form id="selectForm" onsubmit="return false;">
    <div class="option-box">

      <label class="select-card" for="opt_input">
        <input type="checkbox" id="opt_input" name="pageSelect" value="account_input.php">
        <div>
          <h5>사용내역서 입력</h5>
          <p>모임 회계 사용내역서를 입력하고 저장합니다.</p>
        </div>
      </label>

      <label class="select-card" for="opt_edit">
        <input type="checkbox" id="opt_edit" name="pageSelect" value="account_edit.php">
        <div>
          <h5>사용내역서 편집</h5>
          <p>모임 회계 사용내역서를 편집할수 있습니다.</p>
        </div>
      </label>


      <label class="select-card" for="opt_edit1">
        <input type="checkbox" id="opt_edit1" name="pageSelect" value="account_view.php">
        <div>
          <h5>사용내역서 열람</h5>
          <p>모임회 회계장부를 볼수있습니다.</p>
        </div>
      </label>


      <label class="select-card" for="opt_view">
        <input type="checkbox" id="opt_view" name="pageSelect" value="images_upload.php">
        <div>
          <h5>영수증사진 업로드</h5>
          <p>회원 영수증처리를 서버에 전송할수 있습니다.</p>
        </div>
      </label>


      <label class="select-card" for="opt_account">
        <input type="checkbox" id="opt_account" name="pageSelect" value="images_edit.php">
        <div>
          <h5>영수증사진 편집</h5>
          <p>회원 영수증처리를 관리할수 있습니다.</p>
        </div>
      </label>


      <label class="select-card" for="opt_images">
        <input type="checkbox" id="opt_images" name="pageSelect" value="images_view.php">
        <div>
          <h5>영수증사진 열람</h5>
          <p>회원 영수증처리를 사진으로 볼수 있습니다.</p>
        </div>
      </label>

      <label class="select-card tel-section" for="opt_tel">
        <input type="checkbox" id="opt_tel" name="pageSelect" value="./tel_select_1.php">
        <div>
          <h5>회원 전화연락망 관리</h5>
          <p>회원정보를 열람/관리 할수 있습니다.</p>
        </div>
      </label>


    </div>

    <div class="btn-area text-center mt-5">
      <button type="button" class="btn btn-primary btn-lg btn-same" onclick="goNext()">선택한 페이지로 이동</button>
      <a href="./logout.php" class="btn btn-outline-secondary btn-lg btn-same mt-3">로그아웃</a>
    </div>
  </form>
</div>

<script>
const boxes = document.querySelectorAll('input[name="pageSelect"]');
const cards = document.querySelectorAll('.select-card');

// 체크박스는 단일선택만 허용 (라디오처럼)
boxes.forEach((box, idx) => {
  box.addEventListener('change', () => {
    boxes.forEach((other, j) => {
      if (j !== idx) other.checked = false;
    });
    updateActive();
  });
});

// 카드 클릭 시 시각 강조
function updateActive() {
  cards.forEach((card, idx) => {
    card.classList.toggle('active', boxes[idx].checked);
  });
}

// 선택된 페이지로 이동
function goNext() {
  const selected = document.querySelector('input[name="pageSelect"]:checked');
  if (!selected) {
    alert("이동할 페이지를 선택해주세요.");
    return;
  }
  location.href = selected.value;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
