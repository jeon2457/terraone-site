<?php
session_start();

// ✅ 관리자 인증
require './php/auth_check.php';

// 세션값을 지역 변수로 세팅
$admin_id = $_SESSION['user_id'] ?? '';
$user_level = $_SESSION['user_level'] ?? '';

// ✅ PDO DB 연결
require './php/db-connect-pdo.php';

// ✅ SMS_2 자동 업데이트 함수
require './php/update_leaders_sms2.php';

// DB 연결 확인
if (!$pdo) {
    die("DB 연결 실패");
}

// 데이터 입력 처리
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id       = $_POST['id'] ?? '';
    $password = $_POST['password'] ?? '';
    $name     = $_POST['name'] ?? '';
    $tel      = $_POST['tel'] ?? '';
    $addr     = $_POST['addr'] ?? '';
    $remark   = $_POST['remark'] ?? '';
    $sms      = $_POST['sms'] ?? '';
    $sms_2    = $_POST['sms_2'] ?? '';
    $user_level = $_POST['user_level'] ?? 1; // 기본 레벨 1

    // 비밀번호 해싱
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ----------------------------------------------------
    // ① remark에 "회장" 또는 "총무" 포함 시,
    //    기존 회원 전체 전화번호를 취합하여 SMS_2에 자동 저장
    // ----------------------------------------------------
    $is_leader = (strpos($remark, '회장') !== false || strpos($remark, '총무') !== false);
    
    if ($is_leader) {
        try {
            // 현재 등록 중인 전화번호를 제외한 모든 회원 전화번호 불러오기
            $sql_get_tel = "SELECT tel FROM tel WHERE tel != '' ORDER BY name ASC";
            $stmt_tel = $pdo->prepare($sql_get_tel);
            $stmt_tel->execute();

            $numbers = $stmt_tel->fetchAll(PDO::FETCH_COLUMN);

            // 전화번호들을 콤마(,)로 구분한 문자열 생성
            if (!empty($numbers)) {
                $sms_2 = implode(",", $numbers); 
            }

        } catch (PDOException $e) {
            die("전화번호 조회 오류: " . $e->getMessage());
        }
    }
    // ----------------------------------------------------

    // 최종 DB 저장 처리
    try {
        $pdo->beginTransaction(); // 트랜잭션 시작

        // ② 신규 회원 데이터 삽입
        $sql = "INSERT INTO tel 
                (id, password, name, tel, addr, remark, sms, sms_2, user_level)
                VALUES 
                (:id, :password, :name, :tel, :addr, :remark, :sms, :sms_2, :user_level)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id'       => $id,
            ':password' => $hashedPassword,
            ':name'     => $name,
            ':tel'      => $tel,
            ':addr'     => $addr,
            ':remark'   => $remark,
            ':sms'      => $sms,
            ':sms_2'    => $sms_2,
            ':user_level' => $user_level
        ]);

        // ----------------------------------------------------
        // ③ 기존 회장/총무들의 SMS_2 업데이트
        //    (새로 추가된 회원 전화번호를 기존 회장/총무의 SMS_2에 추가)
        // ----------------------------------------------------
        
        // 모든 회원 전화번호 다시 조회 (신규 회원 포함)
        $stmt_all = $pdo->prepare("SELECT tel FROM tel WHERE tel != '' ORDER BY name ASC");
        $stmt_all->execute();
        $all_numbers = $stmt_all->fetchAll(PDO::FETCH_COLUMN);

        // 기존 회장/총무 목록 조회
        $stmt_leaders = $pdo->prepare("
            SELECT idx, tel FROM tel 
            WHERE (remark LIKE '%회장%' OR remark LIKE '%총무%')
        ");
        $stmt_leaders->execute();
        $leaders = $stmt_leaders->fetchAll(PDO::FETCH_ASSOC);

        // 각 회장/총무의 SMS_2를 업데이트 (자신의 전화번호 제외)
        foreach ($leaders as $leader) {
            $filtered_numbers = array_filter($all_numbers, function($num) use ($leader) {
                return $num !== $leader['tel']; // 자신의 번호는 제외
            });
            
            $new_sms_2 = implode(',', $filtered_numbers);
            
            $stmt_update = $pdo->prepare("UPDATE tel SET sms_2 = :sms_2 WHERE idx = :idx");
            $stmt_update->execute([
                ':sms_2' => $new_sms_2,
                ':idx' => $leader['idx']
            ]);
        }
        
        $pdo->commit(); // 트랜잭션 커밋

        // ⭐ 이 줄 추가
        updateLeadersSms2($pdo);


        echo "<script>
                alert('데이터가 저장되었습니다.');
                location.href='tel_select.php';
              </script>";
        exit;
        
    } catch (PDOException $e) {
        $pdo->rollBack(); // 오류 시 롤백
        die("데이터 삽입 오류: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>☏ 직지35 회원등록</title>
<link rel="manifest" href="manifest.json">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
body { background-color: #f4f6f9; font-size:16px; }
.container { max-width:650px; margin:40px auto; padding:30px; background:#fff; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1); }
h2.jikji35 { text-align:center; color:#007bff; font-weight:700; margin-bottom:40px; }
label { font-weight:bold; }
.asterisk::after { content:" *"; color:red; }
input[type="submit"] { width:150px; font-size:18px; }
hr { margin:25px 0; border-color:#ccc; }
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
.info-badge {
    font-size: 0.85rem;
    margin-left: 8px;
}
#sms_2.auto-generated {
    background-color: #f0f0f0;
    cursor: not-allowed;
}
</style>
</head>

<body>
<div class="container">
<h2 class="section-title">황악회원 신규등록</h2>

<form method="POST" onsubmit="return checkForm(this);">

  <div class="mb-3">
    <label for="f_id" class="form-label asterisk">아이디</label>
    <input type="text" id="f_id" name="id" class="form-control" required>
  </div>

  <div class="mb-3">
    <label for="f_password" class="form-label asterisk">비밀번호</label>
    <input type="password" id="f_password" name="password" class="form-control" required>
  </div>

  <div class="row mb-3">
    <div class="col-md-6 mb-3">
      <label for="f_name" class="form-label asterisk">이름</label>
      <input type="text" id="f_name" name="name" class="form-control" required>
    </div>

    <div class="col-md-6 mt-1 mt-md-0">
      <label for="f_tel" class="form-label asterisk">전화번호</label>
      <input type="text" id="f_tel" name="tel" class="form-control"
             placeholder="숫자로만 입력" maxlength="13" required oninput="autoHyphen(this)">
    </div>
  </div>

  <div class="mb-3">
    <label for="addr" class="form-label asterisk">거주지</label>
    <input type="text" id="addr" name="addr" class="form-control" required placeholder="간략하게만 입력 예) 서울">
  </div>

  <div class="mb-3">
    <label for="remark" class="form-label">
      비고(직책)
      <span class="badge bg-info info-badge">회장/총무 입력 시 SMS_2 자동생성</span>
    </label>
    <input type="text" id="remark" name="remark" class="form-control" placeholder="예) 회원, 총무, 회장, 대기자 등...">
  </div>

  <hr>

  <div class="mb-3">
    <label for="sms" class="form-label asterisk">SMS(Tel)</label>
    <input type="text" id="sms" name="sms" class="form-control" maxlength="13" required oninput="autoHyphen(this)">
  </div>

  <div class="mb-3">
    <label for="sms_2" class="form-label">
      SMS-2 단체
      <span class="badge bg-warning info-badge" id="auto_badge" style="display:none;">자동생성 예정</span>
    </label>
    <input type="text" id="sms_2" name="sms_2" class="form-control">
    <small class="text-muted">회장/총무가 아닌 경우 수동입력 가능</small>
  </div>

  <hr>

  <div class="mb-3">
    <label for="f_level" class="form-label asterisk">회원 레벨</label>
    <select id="f_level" name="user_level" class="form-select" required>
      <option value="">레벨 선택</option>
      <option value="1">게스트 (1)</option>
      <option value="2">정회원 (2)</option>
      <option value="10">관리자 (10)</option>
    </select>
  </div>

  <div class="text-center d-flex justify-content-center gap-3">
    <input type="submit" class="btn btn-success px-4" value="입력하기">
    <a href="tel_select_1.php" class="btn btn-secondary px-4">돌아가기</a>
  </div>

</form>
</div>

<script>
function checkForm(form) {
    return confirm("회원 정보를 등록하시겠습니까?");
}

function autoHyphen(target) {
  target.value = target.value.replace(/[^0-9]/g,'')
                             .replace(/^(\d{2,3})(\d{3,4})(\d{4})$/,'$1-$2-$3');
}

// SMS 자동 동기화
const telInput = document.getElementById('f_tel');
const smsInput = document.getElementById('sms');
telInput.addEventListener('input', () => {
  const telValue = telInput.value.replace(/[^0-9]/g, '');
  smsInput.value = telValue.replace(/^(\d{3})(\d{3,4})(\d{4})$/, '$1-$2-$3');
});

// 비고(직책) 입력 시 SMS_2 자동생성 안내
const remarkInput = document.getElementById('remark');
const sms2Input = document.getElementById('sms_2');
const autoBadge = document.getElementById('auto_badge');

remarkInput.addEventListener('input', () => {
  const remarkValue = remarkInput.value.trim();
  
  if (remarkValue.includes('회장') || remarkValue.includes('총무')) {
    sms2Input.value = '저장 시 자동생성됩니다';
    sms2Input.classList.add('auto-generated');
    sms2Input.readOnly = true;
    autoBadge.style.display = 'inline-block';
  } else {
    if (sms2Input.classList.contains('auto-generated')) {
      sms2Input.value = '';
    }
    sms2Input.classList.remove('auto-generated');
    sms2Input.readOnly = false;
    autoBadge.style.display = 'none';
  }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>