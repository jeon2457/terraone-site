<?php
session_start();

// 현재 페이지 URL 저장(다이렉트로 이 페이지로 진입시 진입차단, 
// 로그인 검증후에 다시 이 페이지로 진입허용!)
$_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];


require './php/auth_check.php';   // 로그인 + 관리자 레벨 확인
require './php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');

// 회원 목록 조회 (이름순 정렬)
$stmt = $pdo->query("SELECT * FROM tel ORDER BY name ASC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 전체 전화번호 목록 (SMS 발송용)
$allPhoneNumbers = array_filter(array_column($members, 'tel'));
$allPhoneNumbersStr = implode(',', $allPhoneNumbers);

// 로그인 사용자 정보
$userName = $_SESSION['user_name'] ?? '관리자';
$userLevel = $_SESSION['user_level'] ?? 10;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>회원 전체 목록</title>

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
  --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

body {
  background: var(--primary-gradient);
  min-height: 100vh;
  padding: 25px 3px 5px 3px;
  font-family: 'Noto Sans KR', sans-serif;
}

.container { max-width: 1400px; margin: 0 auto; }

.header-card {
  background: white;
  border-radius: 20px 20px 0 0;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  position: relative;
}

.header-title {
  font-size: 2rem;
  font-weight: 700;
  color: #667eea;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 15px;
}

.user-info {
  position: absolute;
  top: 25px;
  right: 25px;
  display: flex;
  align-items: center;
  gap: 15px;
}

.user-badge {
  background: var(--primary-gradient);
  color: white;
  padding: 8px 20px;
  border-radius: 20px;
  font-weight: 600;
}

.btn-logout {
  padding: 8px 20px;
  border-radius: 20px;
  border: 2px solid #667eea;
  background: white;
  color: #667eea;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  text-decoration: none;
}

.btn-logout:hover {
  background: #667eea;
  color: white;
}

.action-buttons {
  display: flex;
  gap: 10px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.btn-action {
  padding: 12px 24px;
  border-radius: 12px;
  border: none;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 1rem;
}

.btn-signup { background: #28a745; color: white; }
.btn-view { background: #17a2b8; color: white; }
.btn-edit { background: #ffc107; color: #333; }
.btn-delete { background: #dc3545; color: white; }



.btn-action:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.table-card {
  background: white;
  border-radius: 0 0 20px 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  overflow: hidden;
}

.table-container {
  overflow-x: auto;
  max-height: 70vh;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}

thead {
  background: var(--primary-gradient);
  color: white;
  position: sticky;
  top: 0;
  z-index: 10;
}

th {
  padding: 15px 10px;
  text-align: center;
  font-weight: 700;
  white-space: nowrap;
  font-size: 0.95rem;
}

td {
  padding: 12px 10px;
  text-align: center;
  border-bottom: 1px solid #f0f0f0;
  vertical-align: middle;
}

tbody tr:hover {
  background: #f8f9ff;
}

.checkbox-col { width: 40px; }
.number-col { width: 60px; font-weight: 700; color: #667eea; }
.id-col { width: 100px; }
.name-col { width: 100px; font-weight: 600; }
.phone-col { width: 140px; }
.address-col { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.remark-col { width: 100px; }
.sms-col { width: 80px; }
.sms-list-col { max-width: 300px; font-size: 0.85rem; }
.level-col { width: 100px; }

.level-badge {
  padding: 5px 15px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 0.85rem;
  display: inline-block;
}

.level-admin { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.level-plus { background: #ffc107; color: #333; }
.level-temp { background: #e0e0e0; color: #666; }

.sms-icon {
  width: 32px;
  height: 32px;
  cursor: pointer;
  transition: transform 0.3s;
}

.sms-icon:hover {
  transform: scale(1.2);
}

.btn-back {
  padding: 12px 24px;
  border-radius: 12px;
  border: 2px solid #667eea;
  background: white;
  color: #667eea;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.3s;
  display: inline-block;
}

.btn-back:hover {
  background: #667eea;
  color: white;
}

@media (max-width: 768px) {
  .header-title { font-size: 1.5rem; }
  .user-info { position: static; margin-top: 15px; justify-content: center; }
  .action-buttons { justify-content: center; }
  
  table { font-size: 0.85rem; }
  th, td { padding: 8px 5px; }
  
  .address-col, .sms-list-col { max-width: 150px; }
  

  /* 상단 버튼 크기 살짝 축소 */
  .btn-action {
    font-size: 0.8rem;     /* 기본 1rem → 0.9rem */
    padding: 8px 18px;    /* 기본 12px 24px → 살짝 축소 */
    border-radius: 10px;
    margin-top: 2px;
    margin-right: 15px; /* 오른쪽으로 10px 간격 */

  }

  /* 로그아웃 버튼도 동일하게 */
  .btn-logout {
    margin-right: 5px; /* 오른쪽으로 5px 이동 */
    padding: 6px 16px;
    font-size: 0.7rem;
  }

  /* 관리자명 뱃지 */
  .user-badge {
    margin-left: -25px; /* 왼쪽으로 25px 이동 */
    padding: 6px 14px;
    font-size: 0.7rem;
  }

  /* 📱 모바일폰 반응형에서 특정 컬럼 숨기기 (아이디, SMS) */
  th.id-col, td.id-col,
  th.sms-col, td.sms-col,
  th.sms-list-col, td.sms-list-col,
  th.level-col, td.level-col {
    display: none !important;
  }
}

</style>
</head>
<body>

<div class="container">
  
  <!-- 헤더 -->
  <div class="header-card">
    <h1 class="header-title">
      👥 회원 전체 목록
    </h1>
    
    <div class="user-info">
      <span class="user-badge">👋 <?= htmlspecialchars($userName) ?>님 (관리자)</span>
      <a href="logout.php" class="btn-logout">로그아웃</a>
    </div>
    
    <!-- 액션 버튼 -->
    <div class="action-buttons">
      <button class="btn-action btn-signup" onclick="location.href='tel_input.php'">
        ✍️ 회원 가입
      </button>
      <button class="btn-action btn-view" onclick="location.href='tel_view.php'">
        📋 회원 열람
      </button>
      <button class="btn-action btn-edit" onclick="editSelected()">
        ✏️ 선택 수정
      </button>
      <button class="btn-action btn-delete" onclick="deleteSelected()">
        🗑️ 선택 삭제
      </button>
      <button class="btn btn-primary btn-action btn-account " onclick="location.href='admin_member_1.php'">
        🗑️ 거래명세서/영수증
      </button>
    </div>
  </div>

  <!-- 테이블 -->
  <div class="table-card">
    <div class="table-container">
      <table id="membersTable">
        <thead>
          <tr>
            <th class="checkbox-col">
              <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
            </th>
            <th class="number-col">번호</th>
            <th class="id-col">아이디</th>
            <th class="name-col">이름</th>
            <th class="phone-col">전화번호</th>
            <th class="address-col">주소</th>
            <th class="remark-col">비고</th>
            <th class="sms-col">SMS</th>
            <th class="sms-list-col">SMS 추가</th>
            <th class="level-col">레벨</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($members)): ?>
            <tr>
              <td colspan="10" style="padding: 40px; color: #999;">등록된 회원이 없습니다.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($members as $index => $member): ?>
              <?php
                // 레벨 표시
                $levelText = '';
                $levelClass = '';
                switch ($member['user_level']) {
                  case 10:
                    $levelText = '관리자';
                    $levelClass = 'level-admin';
                    break;
                  case 2:
                    $levelText = '회원+';
                    $levelClass = 'level-plus';
                    break;
                  default:
                    $levelText = '임시회원';
                    $levelClass = 'level-temp';
                }
                
                // 회장/총무 여부 확인
                $isPresident = ($member['remark'] === '회장');
                $isTreasurer = ($member['remark'] === '총무');
                $showAllSMS = $isPresident || $isTreasurer;
              ?>
              <tr>
                <td class="checkbox-col">
                  <input type="checkbox" class="member-check" value="<?= $member['idx'] ?>">
                </td>
                <td class="number-col"><?= $index + 1 ?></td>
                <td class="id-col"><?= htmlspecialchars($member['id']) ?></td>
                <td class="name-col">
                  <a href="tel:<?= htmlspecialchars($member['tel']) ?>" style="text-decoration: none; color: inherit;">
                    <?= htmlspecialchars($member['name']) ?>
                  </a>
                </td>
                <td class="phone-col">
                  <a href="tel:<?= htmlspecialchars($member['tel']) ?>" style="text-decoration: none; color: #667eea;">
                    <?= htmlspecialchars($member['tel']) ?>
                  </a>
                </td>
                <td class="address-col" title="<?= htmlspecialchars($member['addr']) ?>">
                  <?php if ($showAllSMS): ?>
                    <a href="sms:<?= $allPhoneNumbersStr ?>" style="text-decoration: none; color: inherit;">
                      <?= htmlspecialchars($member['addr']) ?>
                    </a>
                  <?php else: ?>
                    <?= htmlspecialchars($member['addr']) ?>
                  <?php endif; ?>
                </td>
                <td class="remark-col">
                  <?php if ($showAllSMS): ?>
                    <a href="sms:<?= $allPhoneNumbersStr ?>" style="text-decoration: none; color: inherit;">
                      <?= htmlspecialchars($member['remark']) ?>
                    </a>
                  <?php else: ?>
                    <?= htmlspecialchars($member['remark']) ?>
                  <?php endif; ?>
                </td>
                <td class="sms-col">
                  <a href="sms:<?= htmlspecialchars($member['tel']) ?>">
                    <img src="images/sms-4.png" class="sms-icon" alt="SMS">
                  </a>
                </td>
                <td class="sms-list-col" title="<?= htmlspecialchars($member['sms_2'] ?? '') ?>">
                  <?= htmlspecialchars($member['sms_2'] ?? '') ?>
                </td>
                <td class="level-col">
                  <span class="level-badge <?= $levelClass ?>">
                    <?= $levelText ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 위로 이동하기 버튼 -->
  <div class="text-center mt-5 mb-3">
    <button type="button" class="btn-back" id="btnTop">↑ 위로 이동하기</button>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// 전체 선택/해제
function toggleSelectAll(checkbox) {
  const checkboxes = document.querySelectorAll('.member-check');
  checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

// 선택 수정
function editSelected() {
  const selected = Array.from(document.querySelectorAll('.member-check:checked'))
    .map(cb => cb.value);
  
  if (selected.length === 0) {
    alert('수정할 회원을 선택해주세요.');
    return;
  }
  
  if (selected.length > 1) {
    alert('한 번에 한 명만 수정 가능합니다.');
    return;
  }
  
  // 🔥 tel_update.php로 POST 방식 전송 (기존 방식과 동일)
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = 'tel_update.php';
  
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'edit_id';
  input.value = selected[0];
  form.appendChild(input);
  
  document.body.appendChild(form);
  form.submit();
}

// 선택 삭제
function deleteSelected() {
  const selected = Array.from(document.querySelectorAll('.member-check:checked'))
    .map(cb => cb.value);
  
  if (selected.length === 0) {
    alert('삭제할 회원을 선택해주세요.');
    return;
  }
  
  if (!confirm(`선택한 ${selected.length}명의 회원을 삭제하시겠습니까?`)) {
    return;
  }
  
  // tel_delete.php로 전송
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = 'tel_delete.php';
  
  selected.forEach(idx => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'delete_ids[]';  // 배열로 전송
    input.value = idx;
    form.appendChild(input);
  });
  
  document.body.appendChild(form);
  form.submit();
}
</script>

<script>
document.getElementById('btnTop').addEventListener('click', function() {

  // 1) 페이지 전체 스크롤 맨 위로
  window.scrollTo({ top: 0, behavior: "smooth" });

  // 2) 내부 스크롤 박스(.table-container) 맨 위로
  const tableBox = document.querySelector('.table-container');
  if (tableBox) {
    tableBox.scrollTo({ top: 0, behavior: "smooth" });
  }

});
</script>


</body>
</html>