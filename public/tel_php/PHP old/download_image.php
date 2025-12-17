<?php
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');

header("Content-Type: application/octet-stream");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}

/* ---------------------------
   1) BLOB 다운로드 (id 존재)
---------------------------- */
if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $stmt = $pdo->prepare("SELECT photo, date FROM images WHERE idx=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || empty($row['photo'])) {
        die("이미지 데이터가 없습니다.");
    }

    $filename = "image_" . $id . ".jpg";

    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: image/jpeg");
    echo $row['photo'];
    exit;
}

/* ---------------------------
   2) URL 다운로드 (url 존재)
---------------------------- */
if (isset($_GET['url'])) {

    $imgUrl = urldecode($_GET['url']);

    $imgData = @file_get_contents($imgUrl);

    if ($imgData === false) {
        die("이미지를 불러올 수 없습니다.");
    }

    $filename = "downloaded_image.jpg";

    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: image/jpeg");
    echo $imgData;
    exit;
}

echo "잘못된 요청입니다.";
exit;
?>
