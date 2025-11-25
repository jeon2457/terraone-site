<?php
session_start();

// 로그인 폼 제출 시 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $password = $_POST['password'] ?? '';

    // 여기서 DB 또는 Firebase 인증 연동 가능
    // 예시: 간단한 하드코딩 (실제 운영 시 DB 확인 필요)
    if ($id === 'admin' && $password === '1234') {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = '관리자';
        header('Location: dashboard.php'); // 로그인 성공 시 이동 페이지
        exit;
    } else {
        $errorMessage = '아이디 또는 비밀번호가 올바르지 않습니다.';
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>로그인</title>

  <!-- 파비콘 & 매니페스트 -->
  <link rel="icon" href="./favicon.ico?v=2" />
  <link rel="manifest" href="./manifest.json" />
  <meta name="theme-color" content="#ffffff" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- 사용자 정의 CSS -->
  <link rel="stylesheet" href="./css_firebase/login.css">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg p-4">
          <h2 class="card-title text-center mb-4">로그인</h2>

          <!-- 로그인 오류 메시지 -->
          <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($errorMessage) ?></div>
          <?php endif; ?>

          <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="id" class="form-label">아이디</label>
              <input type="text" class="form-control" id="id" name="id" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">비밀번호</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">로그인</button>
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
