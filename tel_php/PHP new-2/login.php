<?php
session_start(); // 로그인 페이지는 세션 직접 시작
require_once './php/db-connect-pdo.php'; // DB 연결

$errorMessage = "";

// 로그인 폼 제출 시 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // 🔥 DB에서 사용자 정보 조회
        $stmt = $pdo->prepare("SELECT * FROM tel WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // 🔥 비밀번호 검증
            $passwordMatch = false;

            if (strlen($user['password']) >= 60 && strpos($user['password'], '$2y$') === 0) {
                $passwordMatch = password_verify($password, $user['password']);
            } else {
                $passwordMatch = ($password === $user['password']);
            }

            if ($passwordMatch) {

                // 🔥 관리자 권한 확인
                if ($user['user_level'] < 10) {
                    $errorMessage = '관리자만 로그인할 수 있습니다.';
                } else {
                    // ▼ ▼ ▼ 절대 echo 하면 안 됨! ▼ ▼ ▼
                    // echo "로그인 성공: ...";  ← 삭제 완료

                    // 세션 저장
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_level'] = $user['user_level'];
                    $_SESSION['user_key'] = $user['id'];

                    // 🔥 리다이렉트 처리
                    if (isset($_SESSION['redirect_url'])) {
                        $redirectUrl = $_SESSION['redirect_url'];
                        unset($_SESSION['redirect_url']);
                        header("Location: $redirectUrl");
                    } else {
                        header("Location: tel_member.php");
                    }
                    exit;
                }
            } else {
                $errorMessage = '아이디 또는 비밀번호가 올바르지 않습니다.';
            }
        } else {
            $errorMessage = '아이디 또는 비밀번호가 올바르지 않습니다.';
        }
    } catch (PDOException $e) {
        $errorMessage = "DB 오류: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>관리자 로그인</title>

  <link rel="manifest" href="./manifest.json" />
  <meta name="theme-color" content="#ffffff" />

  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="/favicon.png?v=2" />
  <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">


  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- 사용자 정의 CSS -->
  <link rel="stylesheet" href="./css/login.css">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">

      <div class="col-md-6 col-lg-5">

        <!-- 상단 안내 메시지 -->
        <div class="text-left mb-3 py-4" 
             style="color: #fff; background-color: #333; border-radius: 12px; padding-left: 20px; padding-right: 20px;">
          <strong>🔒 관리자 전용 로그인</strong><br>
          관리자 권한(user_level 10)이 있는 계정만 로그인할 수 있습니다.<br>
          <small>admin : te****** / 7****</small>
        </div>

        <!-- 로그인 카드 -->
        <div class="card shadow-lg p-4">
          <h2 class="card-title text-center mb-4">관리자 로그인</h2>

          <!-- 로그인 오류 메시지 -->
          <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($errorMessage) ?></div>
          <?php endif; ?>

          <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="id" class="form-label">아이디</label>
              <input type="text" class="form-control" id="id" name="id" required autofocus>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">비밀번호</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>


            <div class="text-start mb-3" style="font-size:12px;">
                <span style="color:red;">[알림] </span>
                http://localhost/terraone_php/ <br>에서 운영 가동 중인 테스트용 서버입니다.<br>
                DB명: terraone<br>
                모든 회비 사용내역서, 영수증사진, 회원연락망 편집/열람 가능-전송방식 PDO사용<br>
                전화/사용내역서/영수증 관련 테이블: <span style="background-color:black;color:orange;">expense_table, income_table, images, tel 사용중</span>
            </div>


            
            <button type="submit" class="btn btn-primary w-100 mt-4">로그인</button>
          </form>

          <div id="message" class="mt-3 text-center"></div>
        </div>

      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

