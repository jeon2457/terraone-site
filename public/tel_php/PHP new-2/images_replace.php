<?php
require 'php/auth_check.php'; // ✅ 권한 체크!
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');

$idx = isset($_GET['idx']) ? intval($_GET['idx']) : 0;

if ($idx === 0) {
    die('잘못된 접근입니다.');
}

// 기존 이미지 정보 조회
$stmt = $pdo->prepare("SELECT * FROM images WHERE idx = ?");
$stmt->execute([$idx]);
$image = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$image) {
    die('이미지를 찾을 수 없습니다.');
}

$successMessage = '';
$errorMessage = '';

// 이미지 교체 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image'])) {
    
    $uploadFile = $_FILES['new_image'];
    
    if ($uploadFile['error'] === UPLOAD_ERR_OK) {
        
        // 파일 검증
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($uploadFile['type'], $allowedTypes)) {
            $errorMessage = '이미지 파일만 업로드 가능합니다 (JPG, PNG, GIF, WEBP).';
        } elseif ($uploadFile['size'] > $maxSize) {
            $errorMessage = '파일 크기는 5MB 이하여야 합니다.';
        } else {
            
            $uploadDir = __DIR__ . '/data/profile/';
            
            // 디렉토리 생성
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // 새 파일명 생성
            $ext = pathinfo($uploadFile['name'], PATHINFO_EXTENSION);
            $newFileName = 'img_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
            $newFilePath = $uploadDir . $newFileName;
            
            // 파일 업로드
            if (move_uploaded_file($uploadFile['tmp_name'], $newFilePath)) {
                
                // 기존 이미지 삭제
                if (!empty($image['photo']) && file_exists($image['photo'])) {
                    @unlink($image['photo']);
                }
                
                // DB 업데이트
                $relPath = 'data/profile/' . $newFileName;
                $updateStmt = $pdo->prepare("UPDATE images SET photo = ? WHERE idx = ?");
                
                if ($updateStmt->execute([$relPath, $idx])) {
                    $successMessage = '✅ 이미지가 성공적으로 교체되었습니다!';
                    
                    // 이미지 정보 새로고침
                    $stmt = $pdo->prepare("SELECT * FROM images WHERE idx = ?");
                    $stmt->execute([$idx]);
                    $image = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                } else {
                    $errorMessage = 'DB 업데이트 중 오류가 발생했습니다.';
                    @unlink($newFilePath); // 업로드 파일 삭제
                }
                
            } else {
                $errorMessage = '파일 업로드 중 오류가 발생했습니다.';
            }
        }
        
    } else {
        $errorMessage = '파일 업로드 오류: ' . $uploadFile['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>이미지 교체</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
    font-family: 'Noto Sans KR', sans-serif;
}

.container { max-width: 800px; }

.card {
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
    background: white;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    text-align: center;
}

.card-body { padding: 30px; }

.current-image {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9ff;
    border-radius: 12px;
}

.current-image img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.upload-area {
    border: 3px dashed #e0e0e0;
    border-radius: 15px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #f8f9ff;
    margin-bottom: 20px;
}

.upload-area:hover {
    border-color: #667eea;
    background: #f0f2ff;
}

.upload-icon {
    font-size: 3rem;
    margin-bottom: 10px;
    color: #667eea;
}

.preview-image {
    max-width: 100%;
    max-height: 300px;
    border-radius: 12px;
    margin-top: 15px;
    display: none;
}

.btn-upload {
    width: 100%;
    padding: 15px;
    border-radius: 12px;
    font-size: 1.2rem;
    font-weight: 700;
    border: none;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: all 0.3s;
}

.btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

.btn-back {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 2px solid #667eea;
    background: white;
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    margin-top: 15px;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #667eea;
    color: white;
}

.message { 
    padding: 15px; 
    border-radius: 12px; 
    font-weight: 600; 
    text-align: center; 
    margin-bottom: 20px;
}
.message.success { background: #d4edda; color: #155724; }
.message.error { background: #f8d7da; color: #721c24; }

.info-box {
    background: #fff3cd;
    border: 2px solid #ffc107;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
}

.info-box h5 {
    margin: 0 0 10px 0;
    color: #856404;
}

.info-box p {
    margin: 5px 0;
    color: #856404;
}
</style>
</head>

<body>
<div class="container">

<div class="card">
    <div class="card-header">
        <h2>🔄 이미지 교체</h2>
    </div>

    <div class="card-body">

        <?php if ($successMessage): ?>
            <div class="message success"><?= htmlspecialchars($successMessage) ?></div>
        <?php elseif ($errorMessage): ?>
            <div class="message error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <div class="info-box">
            <h5>📌 현재 이미지 정보</h5>
            <p><strong>날짜:</strong> <?= htmlspecialchars($image['date']) ?></p>
            <p><strong>요약:</strong> <?= htmlspecialchars($image['notice']) ?></p>
        </div>

        <div class="current-image">
            <h5>현재 이미지</h5>
            <?php if (!empty($image['photo']) && file_exists($image['photo'])): ?>
                <img src="<?= htmlspecialchars($image['photo']) ?>?v=<?= time() ?>" alt="현재 이미지">
            <?php else: ?>
                <div style="padding: 50px; background: #eee; border-radius: 12px; color: #888;">
                    이미지가 없습니다
                </div>
            <?php endif; ?>
        </div>

        <form method="POST" enctype="multipart/form-data" id="uploadForm">
            
            <div class="upload-area" id="uploadArea" onclick="document.getElementById('imageFile').click()">
                <div class="upload-icon">📷</div>
                <p><strong>클릭하거나 이미지를 드래그하세요</strong></p>
                <p class="text-muted">JPG, PNG, GIF, WEBP 지원 (최대 5MB)</p>
                <input type="file" 
                       id="imageFile" 
                       name="new_image" 
                       accept="image/*" 
                       style="display: none;" 
                       required>
            </div>

            <img id="previewImage" class="preview-image" alt="미리보기">

            <button type="submit" class="btn-upload">🔄 이미지 교체하기</button>
        </form>

        <a href="images_edit.php" class="btn-back">← 편집 페이지로 돌아가기</a>

    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// 파일 선택 시 미리보기
document.getElementById('imageFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewImage');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// 드래그 앤 드롭
const uploadArea = document.getElementById('uploadArea');

uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadArea.style.borderColor = '#667eea';
    uploadArea.style.background = '#f0f2ff';
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    uploadArea.style.borderColor = '#e0e0e0';
    uploadArea.style.background = '#f8f9ff';
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadArea.style.borderColor = '#e0e0e0';
    uploadArea.style.background = '#f8f9ff';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('imageFile').files = files;
        
        // 미리보기 표시
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewImage');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(files[0]);
    }
});
</script>

</body>
</html>