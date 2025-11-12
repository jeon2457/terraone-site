<!-- ✅ 이 페이지는 images_view.php 사용내역서 이미지를 열람할수있는 페이지이다. 
이미지를 다운로드하면 download_image.php와 연결되어 처리된다.  -->

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="J.S.J" />
  <meta name="format-detection" content="telephone=no">
  <title>영수증보기</title>
  <link rel="stylesheet" href="css/images_view.css" />
  <link rel="manifest" href="manifest.json">
  <meta name="msapplication-config" content="/browserconfig.xml">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  <!-- Bootstrap CSS (5.3.3) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="/favicon.ico?v=2" />

  <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />

  <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">
     
    

  <!-- 테이블 중앙 정렬 및 주소복사 스타일 추가 -->
  <style>
    th, td {
      text-align: center !important; /* 가로 중앙 정렬 */
      vertical-align: middle !important; /* 세로 중앙 정렬 */
    }
    .addr_copy {
      display: inline-block;
      padding: 2px 8px;
      border: 1px solid #000; /* 사각 테두리 */
      border-radius: 4px; /* 모서리 둥글게 */
      background-color: #f8f9fa; /* 배경색 */
      margin-right: 10px; /* 우측 간격 추가 */
    }
    .addr_copy:hover {
      background-color: #e9ecef; /* 호버 시 색상 변경 */
    }
    .modal-body p {
      margin-bottom: 0; /* 기본 마진 제거 */
    }
    .modal-body .next-line {
      display: block; /* 다음 줄로 넘김 */
      margin-top: 10px; /* 위쪽 간격 추가 */
    }
  </style>
</head>

<body>

<?php
  
require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
//include 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-2)  

date_default_timezone_set('Asia/Seoul'); // 시간출력을 명시적으로 한국시각으로 지정함!

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결에 실패했습니다: " . $e->getMessage());
}

$today = date('Y/m/d H:i');
echo "<div style='margin-top: 20px'>오늘의 날짜: $today</div>";

$months = range(1, 12);
$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n');

echo "<div class='text-month'>";
foreach ($months as $month) {
    $activeClass = ($month == $currentMonth) ? 'active' : '';
    echo "<a class='btn $activeClass' href='?month=$month'>$month 월</a>";
}
echo "</div>";

echo "<div class='notice'> [알림] 위의 해당되는 X월 버튼을 클릭하면 이미지를 볼수있습니다.</div>";

// 선택된 월에 해당하는 이미지 가져오기
// DESC: 최신 이미지를 제일 상단에 출력,  ASC:  최신 이미지를 제일 하단에 출력
$stmt = $pdo->prepare("SELECT * FROM images WHERE MONTH(date) = ? ORDER BY date DESC");
$stmt->bindParam(1, $currentMonth);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table>";
echo "<tr><th>No</th><th>날짜</th><th>이미지</th><th>요약</th><th>내려받기</th></tr>";

$numberCounter = 1;
foreach ($images as $image) {
  $imageId = $image['idx'];
  $photo = $image['photo'];
  $date = $image['date'];
  $imagePath = "./data/profile/" . basename($photo);

  echo "<tr>";
  echo "<td>$numberCounter</td>";

  //echo "<td>$date</td>";
  $formattedDate = date('Y-m-d H:i', strtotime($date));
  echo "<td>$formattedDate</td>";

  echo "<td><img class='thumbnail' src='$imagePath' alt='Image'></td>";

  $stmt = $pdo->prepare("SELECT notice FROM images WHERE idx = ?");
  $stmt->bindParam(1, $imageId);
  $stmt->execute();
  $summary = $stmt->fetchColumn();

  echo "<td>$summary</td>";
  echo "<td class='big_image'><a href='#' class='download-link' data-idx='$imageId'>다운로드</a></td>";
  echo "</tr>";
  $numberCounter++;
}
echo "</table>";
?>

<!-- 모달 창 -->
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="downloadModalLabel">[[ 이미지 다운로드 방법안내 ]]</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- 닷홈호스팅에서는 모바일에서 다운로드가 정상적으로 되는거같은데 ivyro호스팅에서는 
            Chrome앱에서는 되고, Naver앱에서는 다운로드가 되지를 않는다. -->
        <p>(방법1) 이미지를 길게 손가락으로 터치하면 열리는 창에서 "내 휴대폰:이미지 저장"을 선택한다. 폰의 특정폴더(갤러리)에 저장되는데 열어서 확인가능하다. <br>
        (방법2) 이미지를 폰크기에 맞게 양손가락으로 펼쳐서 확대하고 캡쳐해서 폰에 저장하면된다.</p>

     
      </div>
      
    </div>
  </div>
</div>

<!-- JavaScript 코드 -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const downloadModal = new bootstrap.Modal(document.getElementById('downloadModal'));
  let currentImageId = null;

  // 다운로드 링크 클릭 이벤트
  document.body.addEventListener("click", function(e) {
    if (e.target.classList.contains("download-link")) {
      e.preventDefault();
      currentImageId = e.target.getAttribute("data-idx");
      console.log("Modal opened for image ID:", currentImageId);
      downloadModal.show();
    }
  });

  
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>