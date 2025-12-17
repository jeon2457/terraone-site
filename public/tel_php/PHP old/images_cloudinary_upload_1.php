<?php
session_start();

// Cloudinary 설정
$cloud_name = "dghx4ciwc"; 
$api_key = "367476117442322";
$api_secret = "3_1JaaakBOyp7qDkbAjIWbQ6FDE";
$upload_preset = "direct_upload";

// [중요] 내 기기로 다운로드 로직 - HTML 출력 전에 실행되어야 함
if (isset($_POST['download_to_device'])) {
    if (!empty($_POST['selected_images'])) {
        $urls = $_POST['selected_images'];
        if (count($urls) === 1) {
            $url = $urls[0];
            $filename = basename(parse_url($url, PHP_URL_PATH));
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL 인증서 무시 (호환성)
            $data = curl_exec($ch);
            curl_close($ch);

            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Length: " . strlen($data));
            echo $data;
            exit; // 실행 후 즉시 종료하여 HTML이 섞이지 않게 함
        } else {
            $error_msg = "내 기기로 다운로드는 한 번에 한 장씩만 가능합니다.";
        }
    } else {
        $error_msg = "다운로드할 이미지를 선택해주세요.";
    }
}

// 이미지 업로드 처리
$upload_status = "";
if (isset($_POST['upload'])) {
    if (!empty($_FILES['image']['tmp_name'])) {
        $image_file = $_FILES['image']['tmp_name'];
        $upload_url = "https://api.cloudinary.com/v1_1/{$cloud_name}/image/upload";
        
        $data = [
            'file' => new CURLFile($image_file),
            'upload_preset' => $upload_preset
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $uploaded = json_decode($response, true);
        curl_close($ch);

        if (!empty($uploaded['secure_url'])) {
            $upload_status = "success";
        }
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

// 서버 폴더로 다운로드 처리
$server_download_msg = "";
if (isset($_POST['download_selected'])) {
    if (!empty($_POST['selected_images'])) {
        $download_dir = __DIR__ . "/download/";
        if (!is_dir($download_dir)) mkdir($download_dir, 0755, true);
        
        $count = 0;
        foreach ($_POST['selected_images'] as $url) {
            $filename = basename(parse_url($url, PHP_URL_PATH));
            $save_path = $download_dir . $filename;
            
            $ch = curl_init($url);
            $fp = fopen($save_path, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            $count++;
        }
        $server_download_msg = "✅ {$count}개 이미지를 서버 /download 폴더에 저장했습니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cloudinary 이미지 업로드 & 관리</title>

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

.info-box {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left: 4px solid var(--accent-color);
    border-radius: var(--border-radius);
    padding: 15px 20px;
    margin: 15px 0;
    font-size: 0.9rem;
    line-height: 1.6;
    color: #1565c0;
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.1);
}

.info-box strong {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
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

.nav-buttons {
    width: 100%;
    text-align: center;
    margin-bottom: 10px;
}

.nav-buttons button {
    width: 100%;
    white-space: normal !important;
    word-break: break-word;
}

.nav-buttons button a {
    display: block;
    width: 100%;
    padding: 10px;
    text-decoration: none;
    color: inherit;
}


.btn-navigation {
    margin-top: 13px;
    width: 100%;
    padding: 14px 20px;
    border-radius: 10px;
    border: none;
    background: #7f8c8d;
    color: white;
    font-weight: 600;
    font-size: 15px;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-navigation:hover {
    background: #95a5a6;
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
    background-color: #6ba067ff;
    color: white;
    border-color: #1e7e34;
    font-weight: bold;
}
.btn-first:hover {
    background: #32923eff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}


.btn-kakao {
    text-decoration: none !important; 
    background-color: #FEE500 !important;
    color: #3C1E1E !important;
    font-weight: bold;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s ease;
    text-align: center;
}
.btn-kakao:hover {
    background-color: #f5d900 !important;
}

.kakao-icon {
    margin-right: 5px;
    font-size: 1.2em;
}

/* 새롭게 추가된 DB 저장 버튼 스타일 */
.btn-db-save {
    text-decoration: none !important;
    text-align: center;
    background-color: #FEE500 !important;
    color: #f75a2aff !important;
    font-weight: bold;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s ease;
}
.btn-db-save:hover {
    background-color: #facd07ff !important;
}


/* 버튼 컨테이너 스타일 */
.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 10px;
    margin-top: 10px;
    justify-content: center;
}

.return-button {
    margin-top: 10px;
}

@media (max-width: 768px) {
    .container { padding: 20px; border-radius: 0; }
    .card-container { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); }
    .card img { height: 140px; }

    .action-buttons button, .action-buttons a {
        flex: 1 1 auto;
        width: 100%;
        margin: 0 !important;
    }
}
</style>
</head>
<body>

<div class="container">
    <?php if (isset($error_msg)): ?>
        <div class="alert" style="background: #ffebee; color: #c62828;">⚠️ <?php echo $error_msg; ?></div>
    <?php endif; ?>
    <?php if ($server_download_msg): ?>
        <div class="alert" style="background: #e8f5e9; color: #2e7d32;"><?php echo $server_download_msg; ?></div>
    <?php endif; ?>

    <h2>📤 이미지 업로드</h2>
    <div class="upload-section">
        <div class="info-box">
            <strong>📢 알림</strong>
            (이미지 전송방법) 이곳은 Cloudinary에서 이미지를 업로드/다운로드 관리 서비스해주는곳과 연동되므로 
            굳이 이 Cloudinary사이트로 들어가지않아도 된다. 편리하게 여기서 바로 작업할수있게 만든곳이다. 
            여기서는 직접 내 웹서버의 DB images테이블의 url칼럼 으로 이미지를 전송시킬수는 없다. 그렇게 작업을 
            하려면 "해당이미지의 주소를 복사한후에" 아래에있는 "이미지 DB에 저장" 버튼을 클릭하면 
            /images_upload.php페이지(📸 이미지 업로드 페이지) 에서 주소를 입력해서 "✅ 전송"버튼을 클릭하면 DB(데이타베이스)로 전송이 가능하다.<br>
            ☞ 나의 웹서버의 DB images 테이블 url칼럼으로 저장된다. 웹페이지 images_view.php(열람) / 
            images_edit.php(편집) 열면 DB에서는 url정보를 가져와서 페이지에 뿌려진다.
            ☞ 실제로 해당 url주소의 이미지는 ImageBB, Firebase Storage, Cloudinary... 
            서버에 보관되어 있어야만한다.
        </div>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" name="upload" style="background:#2980b9; color:white;">🚀 업로드 시작</button>
        </form>
    </div>

    <h2>📑 저장된 이미지 목록 (<?php echo count($images); ?>개)</h2>
    <form method="post" id="mainForm">
        <div class="card-container">
            <?php foreach ($images as $img): ?>
                <div class="card">
                    <img src="<?php echo $img['secure_url']; ?>" alt="img">
                    <label style="display:block; margin-top:5px; cursor:pointer;">
                        <input type="checkbox" name="selected_images[]" value="<?php echo $img['secure_url']; ?>" class="img-check"> 선택
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="link-input-section" style="background:#e3f2fd; padding:20px; border-radius:12px;">
            <h3>✔ 선택한 이미지 링크</h3>
            <textarea id="selected_link" rows="3" readonly placeholder="이미지를 선택하면 링크가 표시됩니다"></textarea>
            
            <div class="action-buttons">
                <button type="submit" name="download_selected" class="btn-download">💾 서버로 다운로드</button>
                <button type="submit" name="download_to_device" class="btn-device">📱 내 기기로 다운로드</button>
                <button type="button" class="btn-copy" onclick="copyLinks()">📋 링크 복사</button>
                <a href="images_upload.php" class="btn-db-save">🖼️ 이미지 DB에 저장</a>
                <a href="https://open.kakao.com/o/gWWWIK5h" target="_blank" class="btn-kakao">🔗 카카오톡 공유</a>
                <a href="images_cloudinary_gallery_1.php" class="btn-navigation" style="background:#6ba067;">⏪ Cloudinary 갤러리 가기</a>
                <a href="images_upload.php" class="btn-navigation">⏪ 돌아가기</a>
            </div>
           
        </div>
    </form>
</div>

<script>
const checkboxes = document.querySelectorAll('.img-check');
const linkInput = document.getElementById('selected_link');

// 체크박스 변경 시 링크 텍스트 업데이트
checkboxes.forEach(ch => {
    ch.addEventListener('change', () => {
        const selected = Array.from(document.querySelectorAll('.img-check:checked')).map(c => c.value);
        linkInput.value = selected.join('\n');
    });
});

// 링크 복사 (HTTPS/HTTP 공용)
function copyLinks() {
    if (!linkInput.value) {
        alert('⚠️ 선택된 이미지가 없습니다.');
        return;
    }
    const textArea = document.createElement("textarea");
    textArea.value = linkInput.value;
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        alert('✅ 클립보드에 복사되었습니다!');
    } catch (err) {
        alert('❌ 복사 실패');
    }
    document.body.removeChild(textArea);
}
</script>

</body>
</html>