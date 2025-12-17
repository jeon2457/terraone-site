<?php
// ✅ PDO DB 연결
require './php/db-connect-pdo.php';
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
  <link rel="icon" href="/favicon.png?v=2" />
  <link rel="icon" type="image/png" sizes="36x36" href="./favicons/2/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="./favicons/2/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="./favicons/2/android-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="32x32" href="./favicons/2/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="./favicons/2/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="./favicons/2/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="./favicons/2/apple-icon-72x72.png">


 <style>

/* ==========================
   테이블 헤더(각 항목 개별)
   ========================== */

/* 공통: 헤더 글자 진하게 */
.table thead th span {
    font-weight: 700 !important;
}

/* 제목부분( NO/이름/전화번호/거주지/비고/SMS... ) */
th {
    padding: 0.7rem 0.3rem; /* 상하 / 좌우 */
    font-weight: bold;      /* 진하게 */
}


/* 개별 글자 크기 조절 — 크기는 사용자님이 직접 수치 조정 */
th.no span {
    font-size: 0.6rem;      /* NO */
}

th.name span {
    font-size: 1.2rem;      /* 이름 */
}

th.tel span {
    font-size: 1.2rem;      /* 전화번호 */
}

th.address span {
    font-size: 0.8rem;      /* 거주지 */
}

th.remark span {
    font-size: 1rem;      /* 비고 */
}

th.sms span {
    font-size: 0.9rem;      /* SMS */
}

/* 1) 이름 / 전화번호 글씨 +2px */
.name-cell a,
.tel-cell a {
    font-size: 1.1rem !important; /* 약 +2px 효과 */
    font-weight: 600;
}

/* 이름 열 가로폭 조정 — 숫자는 원하시는 크기로 조절 */
th.name {
    width: 80px;   /* ← 원하는 값으로 조절하세요 (예: 60px, 70px 등) */
}

/* td(내용칸)도 동일하게 폭 조절 */
td.name-cell {
    width: 80px;   /* ← 같은 값으로 맞추기 */
}

th.tel, td.tel-cell {
    width: 123px !important;  /* 전화번호 최대 압축 */
    white-space: nowrap;
    padding: 2px 5px;  /* 위아래 2px, 좌우 8px 여백 */

}

/* 3) 행 높이 증가 */
.table tbody tr td {
    padding-top: 10px !important;
    padding-bottom: 10px !important;
}

/* (선택) 기본 table 구조는 유지하면서 글씨 눌림 방지 */
.table tbody tr {
    line-height: 1.2;
}

/* 회장/총무 거주지 링크 - 일반 텍스트처럼 보이게 */
.leader-sms-link {
    color: inherit;
    text-decoration: none;
    cursor: pointer;
}

.leader-sms-link:hover {
    color: inherit;
    text-decoration: none;
}
</style>


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
                // Escape output for security
                $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                $tel_link = htmlspecialchars($row['tel'], ENT_QUOTES, 'UTF-8');
                $tel_display = htmlspecialchars($row['tel'], ENT_QUOTES, 'UTF-8');
                $addr = htmlspecialchars($row['addr'], ENT_QUOTES, 'UTF-8');
                $remark = htmlspecialchars($row['remark'], ENT_QUOTES, 'UTF-8');
                $sms_link = htmlspecialchars($row['sms'], ENT_QUOTES, 'UTF-8');
                
                // ✅ 비고에 "회장" 또는 "총무"가 포함되어 있는지 확인
                $is_leader = (stripos($row['remark'], '회장') !== false || stripos($row['remark'], '총무') !== false);
                
                // ✅ 회장/총무인 경우 실시간으로 SMS_2 데이터 생성
                $sms_2_link = '';
                if ($is_leader) {
                    // DB에 저장된 sms_2가 있으면 사용, 없으면 실시간 생성
                    if (!empty($row['sms_2'])) {
                        $sms_2_link = htmlspecialchars($row['sms_2'], ENT_QUOTES, 'UTF-8');
                    } else {
                        // 실시간 생성: 본인 제외 전체 회원 전화번호
                        $stmt_temp = $pdo->prepare("SELECT tel FROM tel WHERE tel != ? AND tel IS NOT NULL AND tel != '' ORDER BY name ASC");
                        $stmt_temp->execute([$row['tel']]);
                        $temp_tels = $stmt_temp->fetchAll(PDO::FETCH_COLUMN);
                        $sms_2_link = implode(',', $temp_tels);
                    }
                }

                echo "<tr>";
                echo "<td>{$count}</td>";
                echo "<td class='name-cell'><a href='tel:{$tel_link}'>{$name}</a></td>";
                echo "<td class='tel-cell'><a href='tel:{$tel_link}'>{$tel_display}</a></td>";

                // ✅ 회장/총무인 경우에만 거주지에 SMS_2 링크 적용 (일반 텍스트처럼 보임)
                if ($is_leader) {
                  // tel_sms_send.php로 이동하도록 링크 변경
                  echo "<td><a href='tel_sms_send.php?exclude_tel={$row['tel']}' class='leader-sms-link'>{$addr}</a></td>";
                } else {
                  echo "<td>{$addr}</td>";
                }


                echo "<td class='remark-cell'>{$remark}</td>";
                echo "<td><a href='sms:{$sms_link}'><img src='./images/sms-4.png' alt='SMS 보내기' class='sms-icon'></a></td>";
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
        <span class="jik"><a href="https://jikji35.github.io/terra-1/book/index.html" target="_blank">https://jikji35.github.io/terra-1</a></span>
        <img src="./images/anicircle03_green.gif" alt="" /> <!-- Alt text improvement -->
      </div>

      <!-- Go to Top Button -->
      <div class="gototop">
        <button id="goTopBtn" type="button" aria-label="맨 위로 이동" style="background:none; border:none; cursor:pointer;">
          <img class="fa" src="./images/arrow-1.jpg" alt="맨 위로 가기"/>
        </button>
      </div>

    </section>
  </div>

  <script>
    document.getElementById("goTopBtn").addEventListener("click", function() {
      const start = window.scrollY;
      const duration = 1500; // 스크롤 이동시간: 1.5초
      const startTime = performance.now();

      function scrollStep(timestamp) {
        const elapsed = timestamp - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // ease-in-out 효과
        const ease = progress < 0.5
          ? 2 * progress * progress
          : -1 + (4 - 2 * progress) * progress;

        window.scrollTo(0, start * (1 - ease));

        if (progress < 1) {
          requestAnimationFrame(scrollStep);
        }
      }

      requestAnimationFrame(scrollStep);
    });
  </script>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpCrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>