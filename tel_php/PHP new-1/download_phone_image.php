<?php
// download.php - 이미지 다운로드 처리
date_default_timezone_set('Asia/Seoul');

// 파일명 받기
$filename = $_GET['file'] ?? '';

if (empty($filename)) {
    die('파일명이 지정되지 않았습니다.');
}

// 🔥 보안: 경로 조작 방지 (../ 등 제거)
$filename = basename($filename);

// 🔥 파일 경로 설정
$filepath = __DIR__ . '/data/profile/' . $filename;

// 파일 존재 확인
if (!file_exists($filepath)) {
    die('파일을 찾을 수 없습니다: ' . htmlspecialchars($filename));
}

// 🔥 MIME 타입 자동 감지
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filepath);
finfo_close($finfo);

// 기본값 (이미지가 아닐 경우 대비)
if (!$mimeType) {
    $mimeType = 'application/octet-stream';
}

// 🔥 다운로드용 파일명 생성 (한글 파일명 지원)
$downloadName = 'image_' . date('Ymd_His') . '_' . $filename;

// 🔥 헤더 설정 (모바일 최적화)
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . $downloadName . '"');
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');
header('Pragma: public');

// 🔥 파일 출력 (큰 파일도 처리 가능)
readfile($filepath);
exit;
?>