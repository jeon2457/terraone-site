<?php
session_start();
// ⭐ 맨 위에 반드시 이 코드가 있어야 합니다!
require 'php/auth_check.php';
// Cloudinary 설정
$cloud_name = "dghx4ciwc"; 
$api_key    = "367476117442322";
$api_secret = "3_1JaaakBOyp7qDkbAjIWbQ6FDE";
$upload_preset = "direct_upload"; // Unsigned preset 사용

// 이미지 업로드 처리
if (isset($_POST['upload'])) {
    if (!empty($_FILES['image']['tmp_name'])) {
        $image_file = $_FILES['image']['tmp_name'];
        $upload_url = "https://api.cloudinary.com/v1_1/{$cloud_name}/image/upload";

        // 파일 정보 확인
        echo "<div style='background:#e3f2fd;padding:15px;border-left:4px solid #2196F3;margin:10px 0;'>";
        echo "<strong>📁 업로드 파일 정보:</strong><br>";
        echo "파일명: " . $_FILES['image']['name'] . "<br>";
        echo "파일크기: " . round($_FILES['image']['size']/1024, 2) . " KB<br>";
        echo "MIME Type: " . $_FILES['image']['type'] . "<br>";
        echo "</div>";

        $data = [
            'file' => new CURLFile($image_file),
            'upload_preset' => $upload_preset
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            echo "<div style='background:#ffebee;color:#c62828;padding:15px;border-left:4px solid #f44336;margin:10px 0;'>";
            echo "❌ cURL 오류: " . curl_error($ch);
            echo "</div>";
        }

        curl_close($ch);

        $uploaded = json_decode($response, true);

        echo "<div style='background:#f3e5f5;padding:15px;border-left:4px solid #9c27b0;margin:10px 0;'>";
        echo "<strong>🔍 HTTP 상태 코드:</strong> {$http_code}<br>";
        echo "<strong>📡 API 응답:</strong>";
        echo "<pre style='background:#222;color:#0f0;padding:10px;margin-top:10px;overflow-x:auto;'>";
        print_r($uploaded);
        echo "</pre>";
        echo "</div>";

        if (!empty($uploaded['secure_url'])) {
            echo "<div style='background:#e8f5e9;color:#2e7d32;padding:15px;border-left:4px solid #4caf50;margin:10px 0;'>";
            echo "✅ <strong>업로드 성공!</strong><br>";
            echo "URL: <a href='{$uploaded['secure_url']}' target='_blank'>{$uploaded['secure_url']}</a><br>";
            echo "Public ID: {$uploaded['public_id']}<br>";
            echo "포맷: {$uploaded['format']}<br>";
            echo "</div>";
        } elseif (!empty($uploaded['error'])) {
            echo "<div style='background:#ffebee;color:#c62828;padding:15px;border-left:4px solid #f44336;margin:10px 0;'>";
            echo "❌ <strong>업로드 실패!</strong><br>";
            echo "오류 메시지: {$uploaded['error']['message']}<br>";
            echo "</div>";
        }
    } else {
        echo "<div style='background:#ffebee;color:#c62828;padding:15px;border-left:4px solid #f44336;margin:10px 0;'>";
        echo "❌ 파일이 선택되지 않았습니다.";
        echo "</div>";
    }
}

// 이미지 리스트 가져오기
$resource_url = "https://api.cloudinary.com/v1_1/{$cloud_name}/resources/image?max_results=30";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $resource_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");
$list_response = curl_exec($ch);
curl_close($ch);

$list = json_decode($list_response, true);
$images = $list['resources'] ?? [];

// 체크된 이미지 다운로드 처리
if (isset($_POST['download_selected'])) {
    if (!empty($_POST['selected_images'])) {
        $download_dir = __DIR__ . "/download/";
        
        if (!is_dir($download_dir)) {
            mkdir($download_dir, 0755, true);
        }
        
        $downloaded_count = 0;
        foreach ($_POST['selected_images'] as $url) {
            $filename = basename(parse_url($url, PHP_URL_PATH));
            $save_path = $download_dir . $filename;
            if (file_put_contents($save_path, file_get_contents($url))) {
                $downloaded_count++;
            }
        }
        
        echo "<div style='background:#e8f5e9;color:#2e7d32;padding:15px;border-left:4px solid #4caf50;margin:10px 0;'>";
        echo "✅ {$downloaded_count}개 이미지 다운로드 완료! (download 폴더)";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cloudinary 이미지 업로드 & 관리</title>

<!-- 파비콘 아이콘들 -->
<link rel="icon" href="1/favicons/favicon.png?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="1/favicons/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="1/favicons/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="1/favicons/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="1/favicons/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="1/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="1/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="1/favicons/apple-icon-72x72.png">

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

h2 {
    color: #2c3e50;
    margin: 20px 0;
    padding-bottom: 10px;
    border-bottom: 3px solid #3498db;
}

.upload-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    margin: 20px 0;
}

input[type="file"] {
    padding: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    width: 100%;
    max-width: 400px;
    margin: 10px 0;
}

button {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(52,152,219,0.3);
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(52,152,219,0.4);
}

.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.card { 
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 15px;
    background: white;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card:hover {
    border-color: #3498db;
    box-shadow: 0 8px 24px rgba(52,152,219,0.3);
    transform: translateY(-5px);
}

.card img { 
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 10px;
}

.card label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 500;
    color: #2c3e50;
}

.card input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.card p {
    font-size: 11px;
    color: #7f8c8d;
    word-break: break-all;
    margin-top: 8px;
    line-height: 1.4;
}

.link-input-section {
    background: #e3f2fd;
    padding: 20px;
    border-radius: 12px;
    margin: 20px 0;
}

#selected_link {
    width: 100%;
    padding: 12px;
    border: 2px solid #2196F3;
    border-radius: 8px;
    font-size: 14px;
    margin: 10px 0;
}

/* 👇 여기! 갤러리로 가기 버튼 스타일 새로 적용됨 */
.nav-buttons {
    width: 100%;
    text-align: center;
    margin-bottom: 10px;
}

.nav-buttons button {
    width: 100%;
    white-space: normal !important;  /* 긴 텍스트 줄바꿈 */
    word-break: break-word;          /* 단어 중간이라도 줄바꿈 */
}

.nav-buttons button a {
    display: block;
    width: 100%;
    padding: 10px;
    text-decoration: none;
    color: inherit;
}


.btn-navigation {
    width: 100%;
    padding: 14px 20px;
    border-radius: 10px;
    border: none;
    background: #f07455ff;
    color: white;
    font-weight: 600;
    font-size: 15px;
    text-align: center;
    transition: all 0.3s;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-navigation:hover {
    background: #fa491cff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-navigation a {
    color: white;
    text-decoration: none;
    display: block;
}


/* 1. nav-buttons 클래스를 가진 요소 중 가장 첫 번째 요소 선택 */
.btn-first {
    /* 첫 번째 버튼에만 적용할 배경색 */
    background-color: #6ba067ff; /* 예시: 녹색 배경 */
    color: white;             /* 텍스트 색상을 흰색으로 */
    border-color: #1e7e34;    /* 테두리 색상 */
    font-weight: bold;
}
.btn-first:hover {
    background: #32923eff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}


.btn-kakao {
    text-decoration: none !important; /* 밑줄 제거 */
    background-color: #FEE500 !important;  /* 카카오 노란색 */
    color: #3C1E1E !important;
    font-weight: bold;
    border: none;
    margin-top: 18px;  /* 원하면 직접 margin도 줄 수 있음 */
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s ease;
}
.btn-kakao:hover {
    background-color: #f5d900 !important;
}

.kakao-icon {
    margin-right: 5px;
    font-size: 1.2em; /* 아이콘 크기 조정 */
}

/* 버튼 컨테이너 스타일 추가 */
/* display: flex;: 버튼들을 유연하게 배치합니다.
flex-wrap: wrap;: 화면이 좁아지면 버튼이 자동으로 다음 줄로 내려갑니다.
gap: 5px;: 버튼 사이의 간격을 가로, 세로 모두 5px (요청하신 3px보다 
약간 더 여유 있게) 벌려줍니다. 줄바꿈이 일어났을 때 위아래 간격도 이 속성이 
자동으로 처리해 줍니다. */
.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 10px;
    justify-content: center;   /* ← 중앙 정렬 추가 */
}

@media (max-width: 768px) {
    .container { padding: 20px; border-radius: 0; }
    .card-container { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); }
    .card img { height: 140px; }

    .action-buttons button {
        flex: 1 1 auto; /* 버튼 크기 자동 조절 */
        width: 100%;    /* 모바일에서는 가로 꽉 차게 */
    }
}


</style>
</head>
<body>

<div class="container">
    <h2>📤 이미지 업로드 (Cloudinary)</h2>
    
    <div class="upload-section">
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/*" required>
            <button name="upload">🚀 업로드 시작</button>
        </form>
        
        <div style="margin-top: 15px; padding: 12px; background: #fff3e0; border-radius: 8px; font-size: 13px; color: #e65100;">
            💡 <strong>참고:</strong> (이미지 전송방법) 이곳은 Cloudinary에서 이미지를 업로드/다운로드 
            관리 서비스해주는곳과 연동되므로 굳이 이 Cloudinary사이트로 들어가지않아도 된다. 편리하게 
            여기서 바로 작업할수있게 만든곳이다. 여기서는 직접 내 웹서버의 DB images테이블의 url칼럼
            으로 이미지를 전송시킬수는 없다. 그렇게 작업을 하려면 "해당이미지의 주소를 복사한후에" 하단에있는 
            1/new_terraone_php/input_upload.php로(3곳중 선택) 한번 더 거쳐야 DB(데이타베이스)로 전송이 가능하다.<br>
            ☞ 나의 웹서버의 DB images 테이블 url칼럼으로 저장된다. 웹페이지 images_view.php(열람) / 
            images_edit.php(편집) 열면 DB에서는 url정보를 가져와서 페이지에 뿌려진다.
            ☞ 실제로 해당 url주소의 이미지는 ImageBB, Firebase Storage, Cloudinary... 
            서버에 보관되어 있어야만한다.
        </div>
    </div>

    <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">

    <h2>📑 저장된 이미지 목록 (<?= count($images) ?>개)</h2>
    
    <form method="post">
        <div class="card-container">
            <?php if (empty($images)): ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #95a5a6;">
                    <p style="font-size: 48px;">📭</p>
                    <p style="font-size: 16px; margin-top: 10px;">업로드된 이미지가 없습니다</p>
                </div>
            <?php else: ?>
                <?php foreach ($images as $img): ?>
                    <div class="card">
                        <img src="<?= $img['secure_url'] ?>" alt="이미지">
                        <label>
                            <input type="checkbox" name="selected_images[]" value="<?= $img['secure_url'] ?>">
                            선택
                        </label>
                        <p>🔗 <?= basename($img['public_id']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($images)): ?>
        <div class="link-input-section">
            <h3 style="color: #1976D2; margin-bottom: 10px;">✔ 선택한 이미지 링크</h3>
            <input type="text" id="selected_link" placeholder="이미지를 선택하면 링크가 자동으로 입력됩니다" readonly>
        
            
            <div class="action-buttons d-flex justify-content-center gap-3">
                <button type="submit" name="download_selected" class="btn-download">💾 선택 이미지 다운로드</button>
                <button type="button" onclick="copyLinks()">📋 링크 복사</button>
                <a class="btn-kakao" href="https://open.kakao.com/o/gWWWIK5h" target="_blank">
                    🔗 카카오톡 공유방
                </a>

            </div>

       

            <div class="nav-buttons">
                <button type="button" class="btn-navigation btn-first">
                    <a href="images_cloudinary_gallery.php">⏪ Cloudinary 갤러리로 가기(전용방)</a>
                </button>
            </div>

            <div class="nav-button-item">
            <button type="button" class="btn-navigation">
                <a href="images_upload.php">⏪ DB로 이미지 업로드하기</a>
            </button>
        </div> 
        
        </div>
        <?php endif; ?>
    </form>
</div>

<script>
// 체크박스 선택 시 링크 자동 입력
const checkboxes = document.querySelectorAll('input[type="checkbox"]');
const linkInput = document.getElementById('selected_link');

checkboxes.forEach(ch => {
    ch.addEventListener('change', updateSelectedLinks);
});

function updateSelectedLinks() {
    let selected = [];
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(c => {
        selected.push(c.value);
    });
    linkInput.value = selected.join('\n');
}

function copyLinks() {
    if (linkInput.value) {
        linkInput.select();
        document.execCommand('copy');
        alert('✅ 링크가 복사되었습니다!');
    } else {
        alert('⚠️ 선택된 이미지가 없습니다.');
    }
}
</script>

</body>
</html>
