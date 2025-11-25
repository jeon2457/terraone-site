<?php
// ⭐ 관리자 인증 — 로그인 + 레벨10 확인
require 'php/auth_check.php';
require 'php/db-connect.php';
date_default_timezone_set('Asia/Seoul');

// PDO 객체 생성
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결 오류: " . $e->getMessage());
}

// 현재 선택 월
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// DB에서 해당 월 이미지 가져오기 (최신순)
$stmt = $pdo->prepare("SELECT * FROM images WHERE MONTH(date) = ? ORDER BY date DESC");
$stmt->execute([$currentMonth]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="J.S.J" />
<title>영수증편집</title>
<link rel="stylesheet" href="css/images_edit.css" />
<link rel="manifest" href="manifest.json">
<meta name="msapplication-config" content="/browserconfig.xml">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-YvpCrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">

<!-- 파비콘 -->
<link rel="icon" href="/favicon.ico?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">

<style>
/* 기본 컨테이너 중앙 정렬 */
body {
    background-color: #f2f2f2;
    font-family: 'Noto Sans KR', sans-serif;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
}

/* 테이블 스타일 */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}
th {
    background-color: #007bff;
    color: white;
}
.thumbnail {
    width: 100%;
    max-width: 200px;
    height: auto;
    border-radius: 8px;
}

/* 버튼 스타일 */
.btn-summary, .btn-delete {
    margin: 3px;
}

/* textarea 스타일 */
.summary-input {
    width: 100%;
    resize: none;
}

/* 월 선택 버튼 */
.month-btns a {
    margin: 2px;
}
.month-btns a.active {
    background-color: #007bff;
    color: white;
}

/* 반응형 */
@media (max-width: 768px) {
    .thumbnail {
        max-width: 120px;
    }
}
</style>

</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">영수증 편집</h2>

    <!-- 월 선택 버튼 -->
    <div class="month-btns text-center mb-3">
    <?php
    for ($m = 1; $m <= 12; $m++) {
        $activeClass = ($m == $currentMonth) ? 'active' : '';
        echo "<a class='btn btn-outline-primary $activeClass' href='?month=$m'>{$m}월</a>";
    }
    ?>
    </div>

    <!-- 이미지 테이블 -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>번호</th>
                <th>날짜</th>
                <th>이미지</th>
                <th>요약</th>
                <th>전송</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $counter = 1;
        foreach ($images as $img):
            $imageId = $img['idx'];
            $imagePath = "./data/profile/" . basename($img['photo']);
            $formattedDate = date('Y-m-d H:i', strtotime($img['date']));
        ?>
            <tr>
                <td><?= $counter++; ?></td>
                <td><?= $formattedDate ?></td>
                <td><img src="<?= $imagePath ?>" class="thumbnail" alt="Image"></td>
                <td><textarea class="summary-input" data-idx="<?= $imageId ?>" style="height:140px;"><?= htmlspecialchars($img['notice']) ?></textarea></td>
                <td><button class="btn btn-primary btn-summary" data-idx="<?= $imageId ?>">전송</button></td>
                <td><button class="btn btn-danger btn-delete" data-idx="<?= $imageId ?>">삭제</button></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- AJAX 처리 -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const summaryInputs = document.querySelectorAll('.summary-input');
    summaryInputs.forEach(input => {
        input.addEventListener('change', function() {
            const imageId = this.dataset.idx;
            const summary = this.value;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_notice.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log("요약 업데이트 성공");
                }
            };
            xhr.send("imageId=" + imageId + "&summary=" + encodeURIComponent(summary));
        });
    });

    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const imageId = this.dataset.idx;
            if (confirm("이미지를 삭제하시겠습니까?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_image.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        btn.closest('tr').remove();
                    }
                };
                xhr.send("imageId=" + imageId);
            }
        });
    });
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
