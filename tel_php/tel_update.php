<?php
session_start();
require './php/db-connect-pdo.php';

if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 10) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='login.php';</script>";
    exit;
}

if (!isset($_POST['edit_id'])) {
    echo "<script>alert('수정할 회원을 선택하세요.'); history.back();</script>";
    exit;
}

$idx = $_POST['edit_id'];

$stmt = $pdo->prepare("SELECT * FROM tel WHERE idx = ?");
$stmt->execute([$idx]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<script>alert('회원정보를 찾을 수 없습니다.'); history.back();</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>회원정보 수정</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">
  <h3 class="text-center mb-4">회원정보 수정</h3>
  <form action="tel_update_action.php" method="post">
    <input type="hidden" name="idx" value="<?= $row['idx'] ?>">

    <div class="mb-3">
      <label class="form-label">이름</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">전화번호</label>
      <input type="text" name="tel" class="form-control" value="<?= htmlspecialchars($row['tel']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">주소</label>
      <input type="text" name="addr" class="form-control" value="<?= htmlspecialchars($row['addr']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">비고</label>
      <input type="text" name="remark" class="form-control" value="<?= htmlspecialchars($row['remark']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">SMS</label>
      <input type="text" name="sms" class="form-control" value="<?= htmlspecialchars($row['sms']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">SMS_2</label>
      <input type="text" name="sms_2" class="form-control" value="<?= htmlspecialchars($row['sms_2']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">level</label>
      <input type="text" name="level" class="form-control" value="<?= htmlspecialchars($row['level']) ?>">
    </div>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary">저장</button>
      <a href="tel_edit.php" class="btn btn-secondary">취소</a>
    </div>
  </form>
</div>
</body>
</html>
