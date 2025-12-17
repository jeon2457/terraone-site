<?php
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}

$today = date('Y/m/d') . '(' . mb_substr('일월화수목금토', date('w'), 1) . ') ' . date('H:i');

$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n');
$months = range(1,12);

$stmt = $pdo->prepare("SELECT * FROM images WHERE MONTH(date)=? ORDER BY date DESC");
$stmt->execute([$currentMonth]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>영수증 보기</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<!-- 파비콘 아이콘들 -->
<link rel="icon" href="/favicon.png?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="./favicons/2/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="./favicons/2/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="./favicons/2/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="./favicons/2/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="./favicons/2/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="./favicons/2/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="./favicons/2/apple-icon-72x72.png">


<style>
img.thumbnail {
    width: 100px;
    height: auto;
    cursor: pointer;
    border: 2px solid #ddd;
    border-radius: 8px;
    transition: transform 0.2s;
}
img.thumbnail:hover {
    transform: scale(1.05);
    border-color: #007bff;
}

.section-title {
    text-align:center; 
    color:#007bff; 
    font-weight:700; 
    margin-bottom:30px; 
    padding:10px; 
    background:#e9f3ff; 
    border-radius:10px;
    border:1px solid #c9e3ff;
}


.month-selector {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 6px 4px;
    margin: 12px 0;
}

.month-selector a { 
    padding: 5px 10px; 
    border-radius:5px; 
    text-decoration:none; 
    background:#f0f0f0;
    margin: 4px;
    display: inline-block;
}
.month-selector a.active { background:#007bff; color:#fff; }

table td {
    vertical-align: middle;
}

/* 모달 */
.modal { 
    display:none; 
    position:fixed; 
    z-index:1050; 
    top:0; 
    left:0; 
    width:100%; 
    height:100%; 
    background:rgba(0,0,0,0.8); 
    overflow:auto; 
    padding-top:50px;
}
.modal-content { 
    margin:auto; 
    display:block; 
    max-width:90%; 
    max-height:95%; 
}
.close-modal { 
    position: fixed; 
    top: 20px; 
    right: 30px; 
    color: #fff; 
    font-size: 55px;
    font-weight: bold; 
    cursor: pointer; 
    z-index: 2000;
    text-shadow: 0 0 10px rgba(0,0,0,0.6);
}

.close-modal:hover {
    color: #ff6666;
    transform: scale(1.1);
}

/* 🔥 모바일 반응형 - 비고와 다운로드 아래로 배치 */
@media(max-width: 768px){
    .month-selector a {
        font-size: 0.9rem;
        padding: 8px 12px;
    }
    
    /* 테이블을 카드 형식으로 변경 */
    table.table thead {
        display: none;
    }
    
    table.table tbody tr {
        display: grid;
        grid-template-columns: 1fr 1fr;  /* 2열 그리드 */
        grid-template-rows: auto auto auto;
        gap: 10px;
        margin-bottom: 20px;
        padding: 15px;
        border: 2px solid #ddd;
        border-radius: 12px;
        background: #f9f9f9;
    }
    
    table.table tbody td {
        border: none;
        padding: 8px;
        text-align: left;
    }
    
    /* No - 좌측 상단 */
    table.table tbody td:nth-child(1) {
        grid-column: 1;
        grid-row: 1;
        font-weight: bold;
        color: #007bff;
    }
    
    /* 날짜 - 우측 상단 */
    table.table tbody td:nth-child(2) {
        grid-column: 2;
        grid-row: 1;
        text-align: right;
        font-size: 0.9rem;
    }
    
    /* 이미지 - 좌측 중앙 (2행 차지) */
    table.table tbody td:nth-child(3) {
        grid-column: 1;
        grid-row: 2 / 4;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    table.table tbody td:nth-child(3) img.thumbnail {
        width: 120px;
        height: auto;
    }
    
    /* 비고 - 우측 중앙 */
    table.table tbody td:nth-child(4) {
        grid-column: 2;
        grid-row: 2;
        display: flex;
        align-items: center;
        word-break: break-word;
    }
    
    table.table tbody td:nth-child(4)::before {
        content: "📝 ";
        margin-right: 5px;
    }
    
    /* 다운로드 - 우측 하단 */
    table.table tbody td:nth-child(5) {
        grid-column: 2;
        grid-row: 3;
        display: flex;
        align-items: flex-end;
    }
    
    table.table tbody td:nth-child(5) .btn {
        width: 100%;
    }
    
    .modal-content { 
        width:100%; 
        max-height:95%; 
    }
}
</style>


</head>
<body>

<div class="text-center section-title mt-3 font-weight-bold">
    오늘의 날짜: <?= $today ?>
</div>



<!-- ⭐ 긴급 공지사항 (이미지 다운로드가 안될때 필요시 (아래)주석 해제) -->
<!-- 이미지는 네이버의 MYBOX=>공유방=>영수증=>해당월에 이미지를 올려놓고 공유링크를
아래주소에서 수정(해당이미지의 링크주소로 해야한다.) 입력처리하면 됩니다.  -->


<!-- <div class="alert alert-warning text-center mx-auto mt-3" style="max-width: 800px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
    <h5 style="color: #856404; font-weight: bold; margin-bottom: 15px;">
        ⚠️ 공지사항
    </h5>
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 15px;">
        서버 장애로 인해 이미지를 다운받을 수가 없습니다.<br>
        아래 주소로 클릭해서 들어가면 이미지 다운로드가 가능합니다.
    </p>
    <div style="background: #fff; border: 2px solid #ffc107; border-radius: 8px; padding: 12px; margin: 10px auto; max-width: 400px;">
        <a href="https://naver.me/xDCkGCwK" target="_blank" style="color: #007bff; font-weight: bold; text-decoration: none; font-size: 18px;">
            https://naver.me/xDCkGCwK
        </a>
    </div>
    <small style="color: #6c757d;">📌 위 링크를 클릭하시면 별도 페이지로 이동합니다.</small>
</div> -->


<!-- ⭐ 긴급 공지사항  여기까지 !! (위) 필요시 주석 해제) -->




<div class="month-selector text-center mt-2 mb-2">
<?php foreach($months as $month): ?>
    <a href="?month=<?= $month ?>" class="<?= ($month==$currentMonth)?'active':'' ?>"><?= $month ?>월</a>
<?php endforeach; ?>
</div>

<table class="table table-bordered text-center">
<thead>
<tr>
    <th>No</th>
    <th>날짜</th>
    <th>이미지</th>
    <th>비고</th>
    <th>다운로드</th>
</tr>
</thead>

<tbody>
<?php
$counter = 1;
foreach($images as $img):
    $imgId = $img['idx'];
    $timestamp = strtotime($img['date']);
    $date = date('Y/m/d', $timestamp) . '(' . mb_substr('일월화수목금토', date('w', $timestamp), 1) . ') ' . date('H:i', $timestamp);

    $notice = htmlspecialchars($img['notice']);
    $isURL = !empty($img['url']);
    $isBLOB = !empty($img['photo']);

    // 이미지 주소 설정
    if ($isBLOB) {
        $imgSrc = "download_image.php?id=$imgId";
        $downloadLink = "download_image.php?id=$imgId";
    } elseif ($isURL) {
        $imgSrc = $img['url'];
        $downloadLink = "download_url.php?url=" . urlencode($img['url']);
    } else {
        $imgSrc = "./images/clova.png";
        $downloadLink = "./images/clova.png";
    }
?>
<tr>
<td><?= $counter ?></td>
<td><?= $date ?></td>
<td>
    <img class="thumbnail" src="<?= htmlspecialchars($imgSrc, ENT_QUOTES) ?>"
         onclick="openModal('<?= htmlspecialchars($imgSrc, ENT_QUOTES) ?>')"
         onerror="this.onerror=null;this.src='./images/clova.png';"
         alt="IMAGE" title="클릭하면 확대됩니다">
</td>
<td><?= $notice ?></td>
<td>
    <a href="<?= htmlspecialchars($downloadLink, ENT_QUOTES) ?>" class="btn btn-primary btn-sm">⬇️ 다운로드</a>
</td>
</tr>
<?php
$counter++;
endforeach;
?>
</tbody>
</table>

<div class="text-center mb-3">
    <a href="./select.php" class="btn btn-dark mt-5">⬅ 되돌아가기</a>
</div>

<!-- 모달 -->
<div id="imageModal" class="modal">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script>
function openModal(src){
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.style.display = "block";
    modalImg.src = src;
}
function closeModal(){
    document.getElementById('imageModal').style.display = "none";
}
document.addEventListener('keydown', function(e){
    if(e.key === "Escape") closeModal();
});
</script>

</body>
</html>