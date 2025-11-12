<!-- ✅ 이페이지는 완성된 직지황악회 연락망 보여주는 페이지이다. -->
<!-- ✅ 이페이지는 DB연결 및 데이타 전송을 다른페이지와는 달리 mysqli방식이
아닌 PDO방식으로 만들어졌다. 그러므로 코드가 다르다. -->


<?php
require 'php/db-connect-pdo.php'; // 파일명주의! DB 접속 정보 불러오기

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 접속 오류: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="직지황악회" />
  <title>☏직지35</title>
  <meta name="format-detection" content="telephone=no">

  <!-- 외부 CSS 파일 연결 -->
  <link rel="stylesheet" href="./css/tel.css">

  <link rel="manifest" href="./manifest.json" />
  <meta name="msapplication-config" content="./browserconfig.xml">
  <meta name="msapplication-TileColor" content="#ffffff" />
  <meta name="msapplication-TileImage" content="./ms-icon-144x144.png" />
  <meta name="theme-color" content="#ffffff" />

  <!-- 부트스트랩 5.3.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-YvpCrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" />

  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="./favicon.ico?v=2" />
  <link rel="icon" type="image/png" sizes="36x36" href="./favicons/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="./favicons/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="./favicons/android-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="32x32" href="./favicons/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="./favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="./favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="./favicons/apple-icon-72x72.png">

  <!-- 인라인 <style> 블록 제거됨 -->

  <script type="text/javascript">
    document.oncontextmenu = function () { return false; };
  </script>

  <script type="text/javascript">
    window.onload = () => {
      // Cube rotation logic
      let deg = 0;
      const cube = document.querySelector('.cube');
      if (cube) {
        setInterval(() => {
          deg -= 90;
          cube.style.transform = `rotateX(${deg}deg)`;
        }, 1000); // Rotate every 1 second
      }

      // Clock update logic
      let lastTimeString = '';
      function updateClock() {
        var date = new Date();
        var YYYY = String(date.getFullYear());
        var MM = String(date.getMonth() + 1).padStart(2, '0');
        var DD = String(date.getDate()).padStart(2, '0');
        var hh = String(date.getHours()).padStart(2, '0');
        var mm = String(date.getMinutes()).padStart(2, '0');
        var week = getWeekday(date);

        const currentTimeString = `${YYYY}/${MM}/${DD}(${week}) ${hh}:${mm}`;
        if (currentTimeString !== lastTimeString) {
          var Clockday = document.getElementById('Clockday');
          var Clock = document.getElementById('Clock');
          if (Clockday) Clockday.innerText = `${YYYY}/${MM}/${DD}(${week})`;
          if (Clock) Clock.innerText = `${hh}:${mm}`;
          lastTimeString = currentTimeString;
        }
      }

      function getWeekday(date) {
        var weekDays = ['일', '월', '화', '수', '목', '금', '토'];
        return weekDays[date.getDay()];
      }

      setInterval(updateClock, 1000); // Update clock every second
      updateClock(); // Initial clock update

      // Loading screen logic
      setTimeout(() => {
        const loadingScreen = document.getElementById('loading-screen');
        if (loadingScreen) {
          loadingScreen.style.opacity = '0';
          setTimeout(() => {
            loadingScreen.style.display = 'none';
            const mainContent = document.getElementById('main-content');
            if (mainContent) {
              mainContent.style.display = 'block';
            }
          }, 400); // Matches CSS transition duration
        }
      }, 500); // Delay before starting fade out
    };
  </script>
</head>

<body>
  <div id="loading-screen">
    <img src="image/jikji35-1.jpg" alt="직지황악회 로딩 이미지" />
  </div>

  <div class="container" id="main-content">
    <section>
      <!-- Header: Fixed at top -->
      <div class="header">
        <div id="Clockday"><a>0000/00/00(일)</a></div> <!-- Placeholder -->
        <div id="Clock"><a>00:00</a></div> <!-- Placeholder -->
      </div>

      <!-- main-body 태그는 표준 HTML 태그가 아니므로 div로 변경하거나 제거하는 것이 좋습니다. -->
      <!-- 여기서는 구조 유지를 위해 div로 감쌉니다. -->
      <div>
        <!-- Wrap2 (Billboard): Fixed below header -->
        <div class="wrap2" id="wrap2">
          <!-- Wrap1 (Cube Container): Absolutely positioned inside wrap2 -->
          <div class="wrap1">
            <div class="cube">
              <img src="./images/email.jpg" alt="이메일 아이콘" />
              <img src="./images/phone.jpg" alt="전화 아이콘" />
              <img src="./images/jikji.jpg" alt="직지초 아이콘" />
              <img src="./images/sms.jpg" alt="문자 아이콘" />
            </div>
          </div>

          <!-- Billboard Content Container -->
          <div id="billboard-container">
            <div id="billboard">
              <img src="./images/aa.gif" width="25" height="25" border="0" alt=""> <!-- Alt text improvement -->
              <img src="./images/dd.gif" width="25" height="25" border="0" alt=""> <!-- Alt text improvement -->
              <span class="custom-span">직지초35회 김천지부 동기연락망</span>
              <img src="./images/dd.gif" width="25" height="25" border="0" alt=""> <!-- Alt text improvement -->
              <img src="./images/aa.gif" width="25" height="25" border="0" alt=""> <!-- Alt text improvement -->
            </div>
          </div>
        </div>

        <!-- Table Container: Provides padding for fixed header/wrap2 -->
        <div class="table-container">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="no"><span>NO</span></th>
                <th class="name"><span>이름</span></th>
                <th class="tel"><span>전화번호</span></th>
                <th class="address"><span>거주지</span></th>
                <th class="remark"><span>비고</span></th>
                <th class="sms"><span>SMS</span></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $stmt = $pdo->prepare("SELECT * FROM tel WHERE id != '' ORDER BY name ASC");
              $stmt->execute();
              $count = 1;

              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Escape output for security (optional but recommended)
                $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                $tel_link = htmlspecialchars($row['tel'], ENT_QUOTES, 'UTF-8');
                $tel_display = htmlspecialchars($row['tel'], ENT_QUOTES, 'UTF-8');
                $addr = htmlspecialchars($row['addr'], ENT_QUOTES, 'UTF-8');
                $remark = htmlspecialchars($row['remark'], ENT_QUOTES, 'UTF-8');
                $sms_link = htmlspecialchars($row['sms'], ENT_QUOTES, 'UTF-8');
                $sms_2_link = isset($row['sms_2']) ? htmlspecialchars($row['sms_2'], ENT_QUOTES, 'UTF-8') : $sms_link; // Use sms_2 if exists

                echo "<tr>";
                echo "<td>{$count}</td>";
                echo "<td class='name-cell'><a href='tel:{$tel_link}'>{$name}</a></td>";
                echo "<td class='tel-cell'><a href='tel:{$tel_link}'>{$tel_display}</a></td>";

                // Special link for specific IDs
                if ($row['id'] == 1 || $row['id'] == 16) {
                   echo "<td><a href='sms:{$sms_2_link}'>{$addr}</a></td>";
                } else {
                   echo "<td>{$addr}</td>";
                }

                echo "<td class='remark-cell'>{$remark}</td>"; // Remark column
                echo "<td><a href='sms:{$sms_link}'><img src='./images/sms-4.jpg' alt='SMS 보내기' class='sms-icon'></a></td>"; // Alt text improvement
                echo "</tr>";
                $count++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div> <!-- End of main-body wrapper div -->

      <!-- Footer -->
      <div class="foot">
        <img src="./images/anicircle03_green.gif" alt="" /> <!-- Alt text improvement -->
        <span class="jik"><a href="./book/index.html" target="_blank">https://jikji35.github.io/terra-1</a></span>
        <img src="./images/anicircle03_green.gif" alt="" /> <!-- Alt text improvement -->
      </div>

      <!-- Go to Top Button -->
      <div class="gototop">
        <a href="#"><img class="fa" src="./images/arrow-1.jpg" alt="맨 위로 가기"/></a> <!-- Alt text improvement -->
      </div>
    </section>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpCrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>