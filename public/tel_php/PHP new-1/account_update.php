<!-- ./account_update.php 편집페이지 -->

<?php
require 'php/auth_check.php'; // ✅ 권한 체크!
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB 접속 오류: " . $e->getMessage());
}

// GET으로 id와 type 받기
$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null;

if (!$id || !$type) {
    die("잘못된 접근입니다.");
}

// 기존 데이터 조회
$table = ($type === '수입') ? 'income_table' : 'expense_table';
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id=?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("데이터를 찾을 수 없습니다.");
}

// POST로 수정 처리
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (!$date || !$category || !$amount) {
        $message = "❌ 필수 항목을 모두 입력해주세요.";
    } else {
        $stmt = $pdo->prepare("UPDATE $table SET date=?, category=?, description=?, amount=? WHERE id=?");
        $stmt->execute([$date, $category, $description, $amount, $id]);
        header("Location: account_edit.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>내역 수정</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family:'Noto Sans KR', sans-serif; background:#f2f2f2; padding:20px;}
.container { max-width:600px; margin:auto; }
.card { background:white; border-radius:15px; padding:25px; box-shadow:0 5px 20px rgba(0,0,0,0.2);}
.btn-submit { width:100%; padding:12px; font-weight:700; border:none; border-radius:10px; background:#667eea; color:white;}
.alert { margin-bottom:15px;}
</style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <h3 class="mb-4">✏️ <?=$type?> 내역 수정</h3>

        <?php if ($message): ?>
        <div class="alert alert-danger"><?=$message?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">일자</label>
                <input type="date" name="date" class="form-control" value="<?=$item['date']?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">항목</label>
                <input type="text" name="category" class="form-control" value="<?=$item['category']?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">비고</label>
                <input type="text" name="description" class="form-control" value="<?=$item['description']?>">
            </div>
            <div class="mb-3">
                <label class="form-label">금액</label>
                <input type="number" name="amount" class="form-control" value="<?=$item['amount']?>" required>
            </div>

            <button type="submit" class="btn-submit">수정 완료</button>
            <a href="account_edit.php" class="btn btn-secondary mt-2 w-100">취소</a>
        </form>
    </div>
</div>
</body>
</html>
