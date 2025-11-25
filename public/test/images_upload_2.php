<?php
require 'php/auth_check.php';
require 'php/db-connect.php';

date_default_timezone_set('Asia/Seoul');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결에 실패했습니다: " . $e->getMessage());
}

$uploadDir = 'data/profile/';
$showSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['photo']['name']) && !empty($_POST['selected_date']) && isset($_POST['notice'])) {
        $filename = $_FILES['photo']['name'];
        $tmpFilePath = $_FILES['photo']['tmp_name'];
        $newFilePath = $uploadDir . $filename;

        if (move_uploaded_file($tmpFilePath, $newFilePath)) {
            $selectedDate = $_POST['selected_date'];
            $selectedTime = $_POST['selected_time'];
            $fullDateTime = $selectedDate . ' ' . $selectedTime;
            $notice = $_POST['notice'];

            $stmt = $pdo->prepare("INSERT INTO images (photo, date, notice) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $newFilePath);
            $stmt->bindParam(2, $fullDateTime);
            $stmt->bindParam(3, $notice);

            if ($stmt->execute()) {
                $showSuccess = true;
            } else {
                $errorMessage = "이미지 업로드 중 오류가 발생했습니다.";
            }
        } else {
            $errorMessage = "이미지 업로드 중 오류가 발생했습니다.";
        }
    } else {
        $errorMessage = "이미지, 날짜, 시간, 비고를 모두 입력해주세요.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="J.S.J" />
<title>영수증 업로드</title>
<link rel="stylesheet" href="css/images_upload.css" />
<link rel="manifest" href="manifest.json">
<meta name="msapplication-config" content="/browserconfig.xml">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f2f2f2;
    font-family: 'Noto Sans KR', sans-serif;
}
.upload-container {
    max-width: 600px;
    margin: 50px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
}
.upload-container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #343a40;
}
.btn-success {
    width: 100%;
}
.success-message, .error-message {
    max-width: 600px;
    margin: 20px auto;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
}
.success-message {
    background-color: #d4edda;
    color: #155724;
}
.error-message {
    background-color: #f8d7da;
    color: #721c24;
}
</style>

</head>
<body>

<div class="upload-container">
    <h2>영수증 입력</h2>
    <?php if (!empty($errorMessage)): ?>
        <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form class="upload-form" action="" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="photo" class="form-label">이미지 선택:</label>
        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
      </div>
      <div class="mb-3">
        <label for="selected_date" class="form-label">날짜 선택:</label>
        <input type="date" class="form-control" id="selected_date" name="selected_date" required>
      </div>
      <div class="mb-3">
        <label for="selected_time" class="form-label">시간 선택:</label>
        <input type="time" class="form-control" id="selected_time" name="selected_time" required>
      </div>
      <div class="mb-3">
        <label for="notice" class="form-label">비고:</label>
        <textarea class="form-control" id="notice" name="notice" rows="3" placeholder="비고를 입력하세요"></textarea>
      </div>
      <input type="submit" class="btn btn-success" value="업로드">
    </form>
</div>

<?php if ($showSuccess): ?>
    <div class="success-message">
        업로드 완료!
        <button class="btn btn-success mt-2" onclick="window.location.href='images_upload.php'">확인</button>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
