<?php
// tel_select.php
session_start();

require './php/auth_check.php';
require './php/db-connect.php';

// ✅ 관리자 인증 (로그인 + 레벨10)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['level']) || $_SESSION['level'] != 10) {
    echo "<script>
            alert('관리자만 접근할 수 있습니다.');
            location.href = './login.php';
          </script>";
    exit;
}

// 관리자 정보 표시용
$admin_id = htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8');
$admin_level = htmlspecialchars($_SESSION['level'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>☏ 직지35 회원관리 선택</title>

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


  <!-- 부트스트랩 5.3.3  -->
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

  h2.title {
    text-align: center;
    color: #007bff;
    font-weight: 700;
    margin-bottom: 30px;
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

  .select-card {
    display: flex;
    align-items: center;
    gap: 15px;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 18px;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
  }

  .select-card:hover {
    border-color: #007bff;
    box-shadow: 0 6px 16px rgba(13, 110, 253, 0.1);
    transform: translateY(-3px);
  }

  .select-card input[type="checkbox"] {
    width: 22px;
    height: 22px;
  }

  .select-card.active {
    border-color: #007bff;
    box-shadow: 0 8px 20px rgba(13, 110, 253, 0.15);
    background-color: #f8f9ff;
  }

  .select-card h5 {
    font-size: 18px;
    margin-bottom: 4px;
  }

  .select-card p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
  }

  .btn-area {
    margin-top: 30px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
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
  <h2 class="title">회원 관리 선택</h2>

  <div class="admin-info">
    👤 관리자: <strong><?= $admin_id ?></strong> (Level <?= $admin_level ?>)
  </div>

  <form id="selectForm" onsubmit="return false;">
    <div class="option-box">

      <label class="select-card" for="opt_input">
        <input type="checkbox" id="opt_input" name="pageSelect" value="tel_input.php">
        <div>
          <h5>회원 등록</h5>
          <p>새로운 회원 정보를 입력하고 저장합니다.</p>
        </div>
      </label>

      <label class="select-card" for="opt_edit">
        <input type="checkbox" id="opt_edit" name="pageSelect" value="tel_edit.php">
        <div>
          <h5>회원 편집</h5>
          <p>기존 회원 정보를 검색하고 수정합니다.</p>
        </div>
      </label>

    </div>

    <div class="btn-area">
      <button type="button" class="btn btn-primary btn-lg" onclick="goNext()">선택한 페이지로 이동</button>
      <a href="./logout.php" class="btn btn-outline-secondary btn-lg">로그아웃</a>
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
