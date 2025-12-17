<?php
// download_url.php
// 외부 URL 이미지를 다운로드 처리하는 파일

if (!isset($_GET['url']) || empty($_GET['url'])) {
    die('URL이 지정되지 않았습니다.');
}

$imageUrl = $_GET['url'];

// URL 유효성 검사
if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
    die('유효하지 않은 URL입니다.');
}

// 파일명 생성 (URL에서 추출하거나 기본값 사용)
$filename = basename(parse_url($imageUrl, PHP_URL_PATH));
if (empty($filename) || strpos($filename, '.') === false) {
    $filename = 'image_' . date('Ymd_His') . '.jpg';
}

// cURL로 이미지 가져오기
$ch = curl_init($imageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

$imageData = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

// 오류 처리
if ($httpCode !== 200 || $imageData === false) {
    die('이미지를 가져올 수 없습니다. (HTTP ' . $httpCode . ')');
}

// Content-Type이 이미지인지 확인
if (!preg_match('/^image\//i', $contentType)) {
    die('이미지 파일이 아닙니다. (Content-Type: ' . $contentType . ')');
}

// 다운로드 헤더 설정
header('Content-Type: ' . $contentType);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($imageData));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: public');

// 이미지 출력
echo $imageData;
exit;