<?php

// error_reporting(0);
// ini_set('display_errors', 0);

// member/member_delete.php
require '../php/db-connect.php'; // DB 연결 (mysqli 방식)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids']; // 배열 형태로 넘어옴

    if (count($delete_ids) === 0) {
        echo "<script>alert('삭제할 회원을 선택하세요.'); history.back();</script>";
        exit;
    }

    // 안전하게 삭제하기 위해 Prepared Statement 사용
    // 여러 개의 ID를 한 번에 처리
    $placeholders = implode(',', array_fill(0, count($delete_ids), '?'));
    $types = str_repeat('s', count($delete_ids)); // id는 문자열이므로 s

    $sql = "DELETE FROM tel WHERE id IN ($placeholders)";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$delete_ids);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('선택한 회원이 성공적으로 삭제되었습니다.'); location.href='member_delete.php';</script>";
    } else {
        echo "<script>alert('삭제 중 오류가 발생했습니다: " . addslashes(mysqli_error($connect)) . "'); history.back();</script>";
    }

    mysqli_stmt_close($stmt);
    exit;
}

// ✅ 회원 목록 불러오기
$sql = "SELECT id, name FROM tel ORDER BY name ASC";
$result = mysqli_query($connect, $sql);

$members = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $members[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>회원 삭제</title>
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
}
.container {
  max-width: 700px;
  margin-top: 60px;
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
h3 {
  text-align: center;
  color: #dc3545;
  font-weight: bold;
  margin-bottom: 25px;
}
.table-hover tbody tr:hover {
  background-color: #f8d7da;
}
</style>
</head>

<body>
<div class="container">
  <h3>회원 삭제</h3>

  <?php if (empty($members)): ?>
    <div class="alert alert-warning text-center">등록된 회원이 없습니다.</div>
  <?php else: ?>
  <form method="POST" action="">
    <table class="table table-bordered table-hover align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th style="width:50px;">
            <input type="checkbox" id="check_all" onclick="toggleAll(this)">
          </th>
          <th>아이디</th>
          <th>이름</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($members as $m): ?>
        <tr>
          <td><input type="checkbox" name="delete_ids[]" value="<?= htmlspecialchars($m['id']) ?>"></td>
          <td><?= htmlspecialchars($m['id']) ?></td>
          <td><?= htmlspecialchars($m['name']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="text-center mt-3">
      <button type="submit" class="btn btn-danger">선택 회원삭제</button>
      <a href="../tel_view.php" class="btn btn-secondary">← 목록으로</a>
    </div>
  </form>
  <?php endif; ?>
</div>

<script>
// 전체 선택 / 해제
function toggleAll(master) {
  const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
  checkboxes.forEach(cb => cb.checked = master.checked);
}
</script>
</body>
</html>
