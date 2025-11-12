<?php
session_start();
require './php/db-connect-pdo.php'; // PDO 연결

// 관리자 확인
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 10) {
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

</head>

<body>
<div class="container mt-4 mb-2">
  <h3 class="text-center mb-4">📋 회원편집 / 삭제</h3>

  <form action="tel_update.php" method="post">
    <table class="table table-bordered table-hover text-center align-middle">
      <thead class="table-light">
        <tr>
          <th>선택</th>
          <th>아이디</th>
          <th>이름</th>
          <th>전화번호</th>
          <th>주소</th>
          <th>비고</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM tel ORDER BY name ASC");
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td><input type='radio' name='edit_id' value='{$row['idx']}'></td>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['tel']}</td>";
            echo "<td>{$row['addr']}</td>";
            echo "<td>{$row['remark']}</td>";
            echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <div class="text-center mt-4 mb-5">
      <button type="submit" formaction="tel_update.php" class="btn btn-warning">수정하기</button>
      <button type="submit" formaction="tel_delete.php" class="btn btn-danger">삭제하기</button>
      <a href="tel_select.php" class="btn btn-secondary">돌아가기</a>
    </div>
  </form>
</div>
</body>
</html>
