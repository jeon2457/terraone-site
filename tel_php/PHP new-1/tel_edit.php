<?php
session_start();

// 현재 페이지 URL 저장(다이렉트로 이 페이지로 진입시 진입차단,
// 로그인 검증후에 다시 이 페이지로 진입허용!)
$_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

require './php/auth_check.php';   // 로그인 + 관리자 레벨 확인
require './php/db-connect-pdo.php'; // PDO 연결

// 관리자 확인
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 10) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='login.php';</script>";
    exit;
}

// DB 연결
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>회원편집</title>

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 20px 0;
    }

    .container {
      max-width: 1400px;
    }

    .page-header {
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      margin-bottom: 30px;
      backdrop-filter: blur(10px);
    }

    .page-header h3 {
      color: #667eea;
      font-weight: 700;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .member-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }

    .member-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      border-color: #667eea;
    }

    .member-card.selected {
      border-color: #667eea;
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }

    .member-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f0f0f0;
    }

    .radio-wrapper input[type="radio"] {
      width: 24px;
      height: 24px;
      cursor: pointer;
      accent-color: #667eea;
    }

    .member-name {
      font-size: 1.3rem;
      font-weight: 700;
      color: #333;
      flex: 1;
    }

    .member-id {
      font-size: 0.9rem;
      color: #666;
      background: #f8f9fa;
      padding: 5px 12px;
      border-radius: 20px;
    }

    /* 모바일 전용 아래 화살표 */
    .mobile-arrow {
      display: none;
      margin-left: auto;
      font-size: 1.7rem;
      color: #ff7b00;
      cursor: pointer;
    }



    @media (max-width: 768px) {
      .mobile-arrow {
        display: block;
      }
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 15px;
    }

    .info-item {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .info-label {
      font-size: 0.85rem;
      color: #888;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .info-value {
      font-size: 1rem;
      color: #333;
      padding: 10px 15px;
      background: #f8f9fa;
      border-radius: 8px;
      border-left: 3px solid #667eea;
    }

    .action-buttons {
      background: rgba(255, 255, 255, 0.95);
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .action-buttons button,
    .action-buttons a {
      padding: 12px 30px;
      font-size: 1.1rem;
      font-weight: 600;
      border: none;
      border-radius: 50px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      min-width: 140px;
    }
    /* 추가됨: 버튼 크기 축소 */
    .action-btn-small {
      padding: 8px 20px !important; 
      font-size: 1rem !important;
      min-width: 110px !important;
      margin: 5px;
    }

    /* 하단 "돌아가기" 버튼 옆 ( ▲ 버튼 스타일 )  */
    #goTopBtn {
      border-radius: 50px;
      border: none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }


  </style>
</head>

<body>
<div class="container mt-4 mb-5">
  <div class="page-header">
    <h3>
      <i class="bi bi-person-lines-fill"></i>
      회원 편집 / 삭제
    </h3>
  </div>

  <form action="tel_update.php" method="post" id="memberForm">
    <div id="memberList">
      <?php
      $stmt = $pdo->prepare("SELECT * FROM tel ORDER BY name ASC");
      $stmt->execute();
      $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (count($members) > 0) {
          foreach ($members as $row) {

              echo "<div class='member-card' data-id='{$row['idx']}'>";
              echo "  <div class='member-header'>";
              echo "    <div class='radio-wrapper'>";
              echo "      <input type='radio' name='edit_id' value='{$row['idx']}' id='member_{$row['idx']}'>";
              echo "    </div>";
              echo "    <label for='member_{$row['idx']}' class='member-name'>{$row['name']}</label>";
              echo "    <span class='member-id'><i class='bi bi-person-badge'></i> {$row['id']}</span>";
              echo "    <div class='mobile-arrow'><i class='bi bi-chevron-down'></i></div>";
              echo "  </div>";

              echo "  <div class='info-grid'>";
              echo "    <div class='info-item'><span class='info-label'>전화번호</span><div class='info-value'>{$row['tel']}</div></div>";
              echo "    <div class='info-item'><span class='info-label'>주소</span><div class='info-value'>{$row['addr']}</div></div>";
              echo "    <div class='info-item'><span class='info-label'>비고</span><div class='info-value'>{$row['remark']}</div></div>";
              echo "  </div>";

              echo "</div>";
          }
      }
      ?>
    </div>

    <div class="action-buttons mt-4">

      <button type="submit" formaction="tel_update.php" class="btn btn-warning action-btn-small">
        <i class="bi bi-pencil-square"></i> 수정하기
      </button>

      <button type="submit" formaction="tel_delete_1.php" class="btn btn-danger action-btn-small">
        <i class="bi bi-trash"></i> 삭제하기
      </button>

      <a href="admin_member.php" class="btn btn-secondary action-btn-small">
        <i class="bi bi-arrow-left-circle"></i> 돌아가기
      </a>

      <!-- 🔼 위로가기 버튼 추가됨 -->
      <button type="button" id="goTopBtn" class="btn btn-success action-btn-small" style="font-weight:700;">
        ▲
      </button>

    </div>

  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

  // 카드 클릭 시 라디오 체크 + 선택 표시
  const cards = document.querySelectorAll('.member-card');
  cards.forEach(card => {
    card.addEventListener('click', function(e) {
      if (e.target.closest('.mobile-arrow')) return;

      const radio = this.querySelector('input[type="radio"]');
      radio.checked = true;

      cards.forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
    });
  });

  // 모바일 화살표 클릭 → 페이지 아래로 이동
  const arrows = document.querySelectorAll('.mobile-arrow');
  arrows.forEach(arrow => {
    arrow.addEventListener("click", function(e) {
        e.stopPropagation();
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: "smooth"
        });
    });
  });

  /* 추가됨: 위로가기 버튼 */
  document.getElementById("goTopBtn").addEventListener("click", function () {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  });

});

</script>

</body>
</html>
