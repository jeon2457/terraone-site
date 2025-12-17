<?php
require 'php/auth_check.php';
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}

$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n');
$currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$months = range(1, 12);

$stmt = $pdo->prepare("SELECT * FROM images WHERE MONTH(date)=? AND YEAR(date)=? ORDER BY date DESC");
$stmt->execute([$currentMonth, $currentYear]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>이미지 편집</title>

<!-- 파비콘 아이콘들 -->
<link rel="icon" href="/favicon.png?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* 기존 images_edit.php 디자인 적용 */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 15px 2px 10px 2px;
    font-family: 'Noto Sans KR', sans-serif;
}
.container { max-width: 1200px; }

.card {
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    text-align: center;
}

/* Month buttons */
.month-buttons { 
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(60px, 1fr)); 
    gap: 8px; 
    margin: 16px 0; 
}
.btn-month { 
    padding: 8px 6px; 
    border-radius: 8px; 
    border: 2px solid #e0e0e0; 
    background: white; 
    color: #333; 
    font-weight:600; 
    cursor:pointer; 
    transition: all .15s;
    text-decoration: none;
    display: block;
    text-align: center;
}
.btn-month:hover { 
    border-color: #d0d8ff; 
    color: #333; 
    transform: translateY(-2px); 
}
.btn-month.active { 
    background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); 
    color: white; 
    border-color: #667eea; 
    transform: scale(1.02); 
}

/* table styles */
.table-container { overflow-x: auto; margin-top: 10px; }

table { width: 100%; border-collapse: collapse; }

thead { 
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
    color: white; 
}
th, td {
    padding: 12px;
    text-align: center;
    vertical-align: middle;
    border-bottom: 1px solid #eee;
}

.thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    border: 3px solid transparent;
}

.thumbnail:hover {
    transform: scale(1.1);
    border-color: #667eea;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.5);
}

.summary-input {
    width: 100%;
    padding: 8px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    resize: vertical;
    min-height: 80px;
}

.btn-action {
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    margin: 3px;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
}
.btn-send { background: #28a745; color: white; }
.btn-send:hover { background: #218838; transform: translateY(-2px); }
.btn-delete { background: #dc3545; color: white; }
.btn-delete:hover { background: #c82333; transform: translateY(-2px); }
.btn-download { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; display: inline-block; }
.btn-download:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102,126,234,0.3); color: white; }

/* 🔥 버튼 비활성화 상태 */
.btn-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

.nav-buttons { display: flex; gap: 10px; margin-top: 20px; }

.btn-nav { 
    flex: 1; 
    padding: 15px; 
    border-radius: 12px; 
    border: 2px solid #667eea; 
    background: white; 
    color: #667eea; 
    font-weight: 700; 
    text-decoration: none;
    text-align: center;
    display: block;
}
.btn-nav:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* 모달 */
.image-modal { 
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 0; 
    top: 0; 
    width: 100%; 
    height: 100%; 
    background-color: rgba(0,0,0,0.9); 
    cursor: pointer; 
}
.image-modal img { 
    position: absolute; 
    top: 50%; 
    left: 50%; 
    transform: translate(-50%, -50%); 
    max-width: 90%; 
    max-height: 90%; 
    border-radius: 10px; 
}
.close-modal { 
    position: absolute; 
    top: 20px; 
    right: 40px; 
    color: white; 
    font-size: 40px; 
    font-weight: bold; 
    cursor: pointer;
    z-index: 2000;
    text-shadow: 0 0 10px rgba(0,0,0,0.6);
}
.close-modal:hover {
    color: #ff6666;
    transform: scale(1.1);
}

@media (max-width: 768px) {
  .month-buttons { grid-template-columns: repeat(3, 1fr); }
  
  tbody tr {
      display: block;
      margin-bottom: 20px;
      padding-bottom: 20px;
      border-bottom: 2px solid #ddd;
      background: white;
      padding: 15px;
      border-radius: 12px;
  }
  td.summary-col,
  td.action-col { display: block; width: 100%; margin-top: 10px; }
  thead { display: none; }
  .thumbnail { width: 120px; height: 120px; }
  
  tbody td {
      display: block;
      text-align: left;
      border: none;
      padding: 8px 0;
  }
  
  tbody td::before {
      content: attr(data-label);
      font-weight: 700;
      color: #667eea;
      margin-right: 10px;
  }
  
  .summary-input { min-height: 100px; }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // 💾 저장 버튼
    document.querySelectorAll(".btn-send").forEach(function(btn){
        btn.addEventListener("click", function(){
            let id = this.dataset.idx;
            let val = document.querySelector("#summary-"+id).value;
            
            // 버튼 비활성화
            this.disabled = true;
            this.textContent = "💾 저장 중...";

            fetch("update_notice.php", {
                method:"POST",
                headers:{"Content-type":"application/x-www-form-urlencoded"},
                body:"imageId="+id+"&summary="+encodeURIComponent(val)
            })
            .then(r => r.text())
            .then(t => {
                alert("전송되었습니다.");
                location.reload();
            })
            .catch(error => {
                alert("저장 실패: " + error.message);
                this.disabled = false;
                this.textContent = "💾 저장";
            });
        });
    });

    // 🗑️ 삭제 버튼 (단순 버전 - "ok" 또는 "error" 응답)
    document.querySelectorAll(".btn-delete").forEach(function(btn){
        btn.addEventListener("click", function(){
            let id = this.dataset.idx;
            let deleteBtn = this;

            if(confirm("정말 삭제하시겠습니까?")) {
                // 버튼 비활성화
                deleteBtn.disabled = true;
                deleteBtn.textContent = "🗑️ 삭제 중...";

                fetch("images_delete.php", {
                    method:"POST",
                    headers:{"Content-Type":"application/x-www-form-urlencoded"},
                    body:"imageId="+id
                })
                .then(response => response.text())
                .then(text => {
                    console.log("서버 응답:", text); // 디버깅용
                    
                    if (text.trim() === "ok") {
                        alert("삭제되었습니다.");
                        location.reload();
                    } else {
                        alert("삭제 실패: " + text);
                        deleteBtn.disabled = false;
                        deleteBtn.textContent = "🗑️ 삭제";
                    }
                })
                .catch(error => {
                    console.error("삭제 오류:", error);
                    alert("삭제 중 오류가 발생했습니다:\n" + error.message);
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = "🗑️ 삭제";
                });
            }
        });
    });
});

function openModal(src){
    document.getElementById('imageModal').style.display = "block";
    document.getElementById('modalImage').src = src;
}
function closeModal(){
    document.getElementById('imageModal').style.display = "none";
}
document.addEventListener('keydown', function(e){
    if(e.key === "Escape") closeModal();
});
</script>

</head>
<body>

<div class="container">

<div class="card">
    <div class="card-header">
        <h2>🖊️ 이미지 편집 
            <span style="font-size:0.9rem; opacity:0.95;">(총 <?= count($images) ?>개)</span>
        </h2>
    </div>

    <div class="card-body">

        <!-- 월 선택 버튼 -->
        <div class="month-buttons">
            <?php foreach($months as $month): ?>
                <a href="?month=<?= $month ?>&year=<?= $currentYear ?>" class="btn-month <?= ($month==$currentMonth)?'active':'' ?>">
                    <?= $month ?>월
                </a>
            <?php endforeach; ?>
        </div>

        <div class="table-container">

            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>날짜</th>
                        <th>이미지</th>
                        <th>요약</th>
                        <th>작업</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($images && count($images) > 0): ?>
                    <?php $counter=1; foreach($images as $img): 
                    $id = $img['idx'];

                    $timestamp = strtotime($img['date']);
                    $date = date('Y/m/d', $timestamp) . '(' . mb_substr('일월화수목금토', date('w', $timestamp), 1) . ') ' . date('H:i', $timestamp);

                    $notice = htmlspecialchars($img['notice']);
                    $isURL = !empty($img['url']);
                    $isBLOB = !empty($img['photo']);

                    if($isBLOB){
                        $imgSrc = "download_image.php?id=$id";
                        $downloadLink = "download_image.php?id=$id";
                    } elseif($isURL){
                        $imgSrc = $img['url'];
                        $downloadLink = "download_url.php?url=" . urlencode($img['url']);
                    } else {
                        $imgSrc = "./images/clova.png";
                        $downloadLink = "./images/clova.png";
                    }
                    ?>
                    <tr>
                        <td data-label="No"><?= $counter ?></td>

                        <td data-label="날짜"><?= $date ?></td>

                        <td data-label="이미지">
                            <img class="thumbnail" src="<?= htmlspecialchars($imgSrc) ?>"
                                 onclick="openModal('<?= htmlspecialchars($imgSrc) ?>')">
                        </td>

                        <td class="summary-col" data-label="요약">
                            <textarea id="summary-<?= $id ?>" class="summary-input"><?= $notice ?></textarea>
                        </td>

                        <td class="action-col" data-label="작업">
                            <button class="btn-action btn-send" data-idx="<?= $id ?>">💾 저장</button>
                            <button class="btn-action btn-delete" data-idx="<?= $id ?>">🗑️ 삭제</button>
                            <a href="<?= $downloadLink ?>" class="btn-action btn-download" download>⬇️ 다운로드</a>
                        </td>
                    </tr>

                    <?php $counter++; endforeach; ?>

                <?php else: ?>
                    <tr><td colspan="5" class="text-center">등록된 이미지가 없습니다.</td></tr>
                <?php endif; ?>

                </tbody>
            </table>

        </div>

        <div class="nav-buttons">
            <a href="admin_member_1.php" class="btn-nav">⏪ 되돌아가기</a>
        </div>

    </div>
</div>

</div>

<!-- 모달 -->
<div id="imageModal" class="image-modal" onclick="closeModal()">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <img id="modalImage" src="" alt="확대 이미지">
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>