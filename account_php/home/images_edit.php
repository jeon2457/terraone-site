
<?php
// ⭐ 맨 위에 반드시 이 인증절차 통과 코드가 있어야 합니다!
// ⭐ 관리자 인증 — 로그인 + 레벨10 확인 
// 해당 연결DB(데이타베이스) member테이블에 회원등록이 되어있어야한다.
// level(레벨위치)이 10으로 지정한 관리자만 페이지접속권한이 있다.
require 'php/auth_check.php';
?>


<!-- ✅ 이 페이지는 요약(메모) 추가설명및 이미지를 삭제할수 있으며 번호는 데이타베이스(DB)에서 사용하지않고 날짜순 오름차순으로 만들어졌다.
(알림) 이페이지에서 수정버튼을 만들어서 날짜/이미지/요약을 수정하려고 했지만 에러로 인해서 포기했다. 수정할게 있다면 그냥 삭제시키고 다시 업로드하면 상관없다.  -->


<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="J.S.J" />
  <title>영수증편집</title>
  <link rel="stylesheet" href="css/images_edit.css" />
  <link rel="manifest" href="manifest.json">
  <meta name="msapplication-config" content="/browserconfig.xml">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  
  
  
  <!-- 부트스트랩 CDN 링크 -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-YvpCrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
  />
	
  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="/favicon.ico?v=2" />

  <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />

  <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">
    


  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var deleteButtons = document.querySelectorAll(".btn_mem_delete");
      var summaryInputs = document.querySelectorAll(".summary-input");

      summaryInputs.forEach(function(input) {
        input.addEventListener("change", function() {
          var imageId = this.getAttribute("data-idx");
          var summary = this.value;

          // AJAX 요청으로 텍스트를 DB의 notice 필드에 보내기
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "update_notice.php", true);
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
              console.log("텍스트가 성공적으로 업데이트되었습니다.");
            }
          };
          xhr.send("imageId=" + imageId + "&summary=" + summary);
        });
      });

      deleteButtons.forEach(function(button) {
        button.addEventListener("click", function() {
          var imageId = this.getAttribute("data-idx");
          var confirmation = confirm("이미지를 삭제하시겠습니까?");
          if (confirmation) {
            // AJAX 요청으로 이미지 삭제 처리
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_image.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
              if (xhr.readyState === 4 && xhr.status === 200) {
                // 삭제가 성공적으로 처리되면 해당 행을 테이블에서 제거
                var tableRow = button.parentNode.parentNode;
                tableRow.parentNode.removeChild(tableRow);
              }
            };
            xhr.send("imageId=" + imageId);
          }
        });
      });
    });
  </script>
</head>
<body>

<?php
  
  
require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
//include 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-2)  
  
date_default_timezone_set('Asia/Seoul'); // 시간출력을 명시적으로 한국시각으로 지정함!

// PDO 객체 생성 및 데이터베이스 연결
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결에 실패했습니다: " . $e->getMessage());
}

// 선택형 버튼 생성
$months = range(1, 12);
$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n'); // 현재 월
echo "<div>";
foreach ($months as $month) {
    $activeClass = ($month == $currentMonth) ? 'active' : '';
    echo "<a class='btn $activeClass' href='?month=$month'>$month 월</a>";
}
echo "</div>";

// 선택된 월에 해당하는 이미지 가져오기
// DESC: 최신 이미지를 제일 상단에 출력,  ASC:  최신 이미지를 제일 하단에 출력
$stmt = $pdo->prepare("SELECT * FROM images WHERE MONTH(date) = ? ORDER BY date DESC");
$stmt->bindParam(1, $currentMonth);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 항목 출력
echo "<table>";
echo "<tr>";
echo "<th>번호</th>";
echo "<th>날짜</th>";
echo "<th style='width: 30%'>이미지</th>";
echo "<th style='width: 30%'>요약</th>";
echo "<th>전송</th>";
echo "<th>삭제</th>";
echo "</tr>";

// 이미지 출력
$numberCounter = 1; // 번호 카운터 추가
foreach ($images as $image) {
  $imageId = $image['idx']; // idx 필드를 사용하여 이미지의 고유 번호 가져오기
  $photo = $image['photo'];
  $date = $image['date'];
  $imagePath = "./data/profile/" . basename($photo);
  echo "<tr>";
  echo "<td>$numberCounter</td>"; // idx 대신 카운터 사용

  //echo "<td>$date</td>";
  $formattedDate = date('Y-m-d H:i', strtotime($date));
  echo "<td>$formattedDate</td>";

  echo "<td><img class='thumbnail' src='$imagePath' alt='Image'></td>";
  echo "<td><textarea style='height: 140px' class='summary-input' data-idx='$imageId'></textarea></td>";
  echo "<td><button class='btn_summary_submit' data-idx='$imageId'>전송</button></td>";
  echo "<td><button class='btn_mem_delete' data-idx='$imageId'>삭제</button></td>";
  echo "</tr>";
  $numberCounter++; // 카운터 증가
}

echo "</table>";

// 이미지 업로드 및 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 이미지 중복 업로드를 방지를 위한 세션 시작
  session_start();

  // 이미지 파일이 있는지 확인
  if (!empty($_FILES['photo']['name'])) {
      $uploadDir = './data/profile/'; // 이미지가 저장될 폴더
      $filename = $_FILES['photo']['name'];
      $tmpFilePath = $_FILES['photo']['tmp_name'];
      $newFilePath = $uploadDir . $filename;

      // 이미지 파일의 해시 값을 생성
      $imageHash = md5_file($tmpFilePath);

      // 세션에 이미지 해시 값이 존재하는지 확인
      if (isset($_SESSION['uploaded_images']) && in_array($imageHash, $_SESSION['uploaded_images'])) {
          echo "이미지가 중복으로 업로드되었습니다.";
          exit;
      }

      // 이미지 파일을 지정된 폴더로 이동
      if (move_uploaded_file($tmpFilePath, $newFilePath)) {
          // 데이터베이스에 이미지 파일 경로와 날짜 저장
          $currentDate = date('Y-m-d H:i:s'); // 현재 날짜와 시간
          $stmt = $pdo->prepare("INSERT INTO images (photo, date) VALUES (?, ?)");
          $stmt->bindParam(1, $newFilePath);
          $stmt->bindParam(2, $currentDate);
          if ($stmt->execute()) {
              // 이미지가 성공적으로 업로드되었을 때 세션에 이미지 해시 값을 추가
              $_SESSION['uploaded_images'][] = $imageHash;
              echo "이미지가 성공적으로 업로드되었습니다.";
          } else {
              echo "이미지 업로드 중 오류가 발생했습니다.";
          }
      } else {
          echo "이미지 업로드 중 오류가 발생했습니다.";
      }
  } else {
      echo "이미지를 선택해주세요.";
  }
}
?>

    <!-- ✅ Bootstrap JS (번들) -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
</body>
</html>