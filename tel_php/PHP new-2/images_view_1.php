<?php
// images_view.php
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');


// ⬇️⬇️⬇️ 반드시 추가해야 하는 부분!!
try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    echo "DB 연결 실패!";
    exit;
}



// 이미지 목록 조회 (최신순)
$stmt = $pdo->prepare("SELECT * FROM images ORDER BY date DESC");
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>이미지 보기</title>
  
  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="/favicon.png?v=2" />
  <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #667eea;
      --secondary-color: #764ba2;
      --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    body {
      background: var(--bg-gradient);
      min-height: 100vh;
      padding: 20px;
      font-family: 'Noto Sans KR', sans-serif;
    }
    .container { max-width: 1200px; margin: 0 auto; }
    .card { border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); border: none; overflow: hidden; margin-bottom: 20px; }
    .card-header { background: var(--bg-gradient); color: white; padding: 25px; text-align: center; border: none; }
    .card-header h2 { margin: 0; font-weight: 700; font-size: 1.8rem; }
    .card-body { padding: 30px; background: white; }
    .month-buttons { display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 10px; margin-bottom: 30px; }
    .btn-month { padding: 12px; border-radius: 10px; border: 2px solid #e0e0e0; background: white; color: #333; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 0.95rem; }
    .btn-month:hover { border-color: var(--primary-color); color: var(--primary-color); }
    .btn-month.active { background: var(--bg-gradient); color: white; border-color: var(--primary-color); transform: scale(1.05); }
    .table-container { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; }
    thead { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    th { padding: 15px; text-align: center; font-weight: 600; font-size: 1rem; }
    td { padding: 12px; text-align: center; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
    tbody tr:hover { background-color: #f8f9ff; }
    .thumbnail { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: transform 0.3s; }
    .thumbnail:hover { transform: scale(1.1); }
    
    .summary-text { 
      max-width: 400px;   /* PC에서 적당한 너비 */
      white-space: normal;
      word-break: break-word;
    }

    
    /* 🔥 다운로드 버튼 스타일 개선 */
    .btn-download { 
      padding: 6px 12px; 
      border-radius: 6px; 
      border: none; 
      background: var(--bg-gradient); 
      color: white; 
      font-weight: 600; 
      cursor: pointer; 
      transition: all 0.3s;
      font-size: 0.85rem;
      text-decoration: none;
      display: inline-block;
    }
    .btn-download:hover { 
      transform: translateY(-2px); 
      box-shadow: 0 5px 15px rgba(102,126,234,0.3);
      color: white;
    }
    .btn-download:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
    

    .loading, .no-data { text-align: center; padding: 40px; color: #999; font-size: 1.1rem; }
    .image-modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); cursor: pointer; }
    .image-modal img { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; max-height: 90%; border-radius: 10px; }
    .close-modal { position: absolute; top: 20px; right: 40px; color: white; font-size: 40px; font-weight: bold; cursor: pointer; }


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


    
    /* 🔥 모바일 반응형 - 카드 형식 */
    @media (max-width: 768px) {
      .month-buttons { grid-template-columns: repeat(3, 1fr); }
      
      /* 테이블을 카드 형식으로 변경 */
      .table-container { overflow-x: visible; }
      
      table { border: none; }
      
      thead { display: none; } /* 헤더 숨김 */
      
      tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 15px;
        background: white;
      }
      
      tbody tr:hover {
        background: #f8f9ff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }
      
      tbody td {
        display: block;
        text-align: left;
        border: none;
        padding: 8px 0;
      }
      
      /* No 열 - 우측 상단 뱃지 */
      tbody td:nth-child(1) {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--bg-gradient);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
      }
      
      /* 날짜 */
      tbody td:nth-child(2)::before {
        content: "📅 날짜: ";
        font-weight: 700;
        color: #667eea;
      }
      
      /* 이미지 - 중앙 정렬 */
      tbody td:nth-child(3) {
        text-align: center;
        padding: 15px 0;
      }
      
      .thumbnail { 
        width: 120px; 
        height: 120px; 
      }
      
      /* 요약 */
      tbody td:nth-child(4)::before {
        content: "📝 요약: ";
        font-weight: 700;
        color: #667eea;
      }
      
      tbody td:nth-child(4) .summary-text {
        max-width: 100%;
        white-space: normal;
        word-break: break-all;
      }
      
      /* 다운로드 버튼 - 중앙 정렬 */
      tbody td:nth-child(5) {
        text-align: center;
        padding: 15px 0 0 0;
      }
      
      .btn-download { 
        font-size: 0.9rem; 
        padding: 10px 20px;
        width: 100%;
      }

      .nav-buttons { display: flex; gap: 10px; margin-top: 20px; }
      
      /* No Data 메시지 */
      tbody tr td.no-data {
        text-align: center;
        position: static;
        padding: 40px 20px;
      }
      
      tbody tr td.no-data::before {
        content: "";
      }
    }
  </style>
</head>
<body>
  <div class="container">
  <div class="card">
    <div class="card-header"><h2>🖼️ 이미지 보기</h2></div>
    <div class="card-body">

      <!-- 월 선택 버튼 -->
      <div class="month-buttons" id="monthButtons">
        <?php for ($m=1; $m<=12; $m++): ?>
        <button type="button" class="btn-month <?= ($m == (int)date('n'))?'active':'' ?>" data-month="<?= $m ?>"><?= $m ?>월</button>
        <?php endfor; ?>
      </div>

      <!-- 테이블 -->
      <div class="table-container">
      <table class="table">
        <thead>
            <tr>
                <th>No</th><th>날짜</th><th>이미지</th><th>요약</th><th>저장</th>
            </tr>
        </thead>
        <tbody id="imageTableBody">
        <?php if ($images): ?>
          <?php foreach ($images as $index => $img): ?>
            <?php
              $idx = (int)$img['idx'];
              $url = $img['url'];   // ★ 변경됨
              $dateRaw = $img['date'];
              $notice = htmlspecialchars($img['notice']);

              // Month for filtering
              $month = 0;
              if (!empty($dateRaw) && $dateRaw !== '0000-00-00 00:00:00') {
                  $dt = date_create($dateRaw);
                  if ($dt) $month = (int)$dt->format('n');
              }

              // 파일명 만들기 (URL에서 추출)
              $filename = basename(parse_url($url, PHP_URL_PATH));
              $downloadName = 'image_' . date('Ymd_His', strtotime($dateRaw)) . '.jpg';
            ?>
            <tr data-month="<?= $month ?>">
              <td><?= $index+1 ?></td>
              <td><?= $dateRaw ?></td>

              <!-- 이미지 썸네일 -->
              <td>
                <?php if (!empty($url)): ?>
                  <img src="<?= htmlspecialchars($url) ?>" class="thumbnail" onclick="openModal('<?= htmlspecialchars($url) ?>')">
                <?php else: ?>
                  <div style="width:80px;height:80px;background:#eee;border-radius:8px;text-align:center;line-height:80px;color:#999;">No Image</div>
                <?php endif; ?>
              </td>

              <td><div class="summary-text"><?= $notice ?: '-' ?></div></td>

              <td>
                <?php if (!empty($url)): ?>
                <a class="btn-download"
                   href="<?= htmlspecialchars($url) ?>"
                   download="<?= $downloadName ?>">
                   ⬇️이미지저장
                </a>
                <?php else: ?>
                  <button class="btn-download" disabled>⬇️이미지저장</button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="no-data">등록된 이미지가 없습니다.</td></tr>
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




  <!-- 이미지 확대 모달 -->
  <div id="imageModal" class="image-modal" onclick="closeModal()">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <img id="modalImage" src="" alt="확대 이미지">
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // 초기 선택 월
    let selectedMonth = new Date().getMonth() + 1;
    
    // 🔥 원본 테이블 HTML 저장 (필터링 초기화용)
    let originalTableHTML = '';

    // 월 버튼 클릭 처리
    document.querySelectorAll('.btn-month').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.btn-month').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        selectedMonth = parseInt(btn.getAttribute('data-month'), 10);
        filterByMonth(selectedMonth);
      });
    });

    function filterByMonth(month) {
      console.log('필터링 시작:', month + '월');
      
      // 🔥 원본 테이블로 복원 (이미지가 없는 월 클릭 후에도 복원 가능)
      if (originalTableHTML) {
        document.getElementById('imageTableBody').innerHTML = originalTableHTML;
      }
      
      const rows = document.querySelectorAll('#imageTableBody tr[data-month]');
      let visibleCount = 0;
      
      rows.forEach(row => {
        const rowMonth = parseInt(row.getAttribute('data-month')) || 0;
        if (rowMonth === month) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      console.log('필터링 결과:', visibleCount + '개 표시');

      // 🔥 보이는 행이 없을 때만 메시지 추가 (기존 행은 유지)
      if (visibleCount === 0) {
        const tbody = document.getElementById('imageTableBody');
        const noDataRow = document.createElement('tr');
        noDataRow.innerHTML = '<td colspan="5" class="no-data">선택한 월의 이미지가 없습니다.</td>';
        tbody.appendChild(noDataRow); // innerHTML 대신 appendChild 사용
      }
    }

    // 초기화
    window.addEventListener('DOMContentLoaded', () => {
      // 🔥 원본 테이블 HTML 저장
      originalTableHTML = document.getElementById('imageTableBody').innerHTML;
      console.log('원본 테이블 저장 완료');
      
      // 현재 월 버튼 활성화
      const active = document.querySelector('.btn-month.active');
      if (!active) {
        const btn = document.querySelector('.btn-month[data-month="' + selectedMonth + '"]');
        if (btn) btn.classList.add('active');
      }
      
      // 초기 필터링
      filterByMonth(selectedMonth);
    });

    // 모달
    function openModal(imageUrl) {
      const modal = document.getElementById('imageModal');
      const modalImg = document.getElementById('modalImage');
      modalImg.src = imageUrl;
      modal.style.display = 'block';
    }
    
    function closeModal() {
      const modal = document.getElementById('imageModal');
      modal.style.display = 'none';
      document.getElementById('modalImage').src = '';
    }

    // 모달 클릭 시 닫기
    document.getElementById('imageModal').addEventListener('click', (e) => {
      if (e.target.id === 'imageModal' || e.target.classList.contains('close-modal')) {
        closeModal();
      }
    });
  </script>
</body>
</html>