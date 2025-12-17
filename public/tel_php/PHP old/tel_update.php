<?php
session_start();
// ✅ 관리자 인증
require './php/auth_check.php';

// ✅ PDO DB 연결
require './php/db-connect-pdo.php';

// require './php/update_leaders_sms2.php'; // 필요시 주석 해제

if (!isset($_POST['edit_id'])) {
    echo "<script>alert('수정할 회원을 선택하세요.'); history.back();</script>";
    exit;
}

$idx = $_POST['edit_id'];
$stmt = $pdo->prepare("SELECT * FROM tel WHERE idx = ?");
$stmt->execute([$idx]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<script>alert('회원정보를 찾을 수 없습니다.'); history.back();</script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>회원정보 수정</title>

  <link rel="icon" href="/favicon.png?v=2" />
  <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
      /* 모바일 최적화 - 화면을 가득 채우기 */
      .mobile-container {
          padding: 15px !important;
          width: 100%;
          max-width: 600px;
          margin: auto;
      }

      input.form-control {
          height: 45px;
          font-size: 1.1rem;
      }

      label {
          font-size: 1rem;
          font-weight: 600;
          display: flex;
          align-items: center;
          gap: 8px; /* 아이콘과 텍스트 사이 간격 */
      }
      
      /* 아이콘 색상 (선택 사항) */
      label i {
          color: #667eea;
          font-size: 1.1rem;
      }

      button, a.btn {
          width: 45%;
          height: 45px;
          font-size: 1.1rem;
      }

      /* SMS_2 필드 읽기전용 스타일 */
      #sms_2_field.auto-generated {
          background-color: #f0f0f0;
          cursor: not-allowed;
      }

      .info-badge {
          font-size: 0.85rem;
          margin-left: 8px;
      }

      h3 {
          color: #667eea;
          font-weight: 700;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 10px;
          margin-bottom: 30px;
      }


      @media (max-width: 768px) {

          h3 {
              font-size: 1.5rem;
          }
      }


  </style>
</head>

<body>
<div class="container-fluid mobile-container mt-3">

  <h3>
    <i class="bi bi-person-gear"></i>
    회원정보 수정
  </h3>

  <form action="tel_update_action.php" method="post">
    <input type="hidden" name="idx" value="<?= $row['idx'] ?>">

    <div class="mb-3">
      <label class="form-label">
        <i class="bi bi-person-badge"></i> 아이디 (id)
      </label>
      <input type="text" name="id" class="form-control" 
             value="<?= htmlspecialchars($row['id']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">
        <i class="bi bi-person"></i> 이름
      </label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">
        <i class="bi bi-telephone"></i> 전화번호
      </label>
      <input type="text" name="tel" class="form-control" value="<?= htmlspecialchars($row['tel']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">
        <i class="bi bi-geo-alt"></i> 주소
      </label>
      <input type="text" name="addr" class="form-control" value="<?= htmlspecialchars($row['addr']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">
        <i class="bi bi-journal-text"></i>
        비고 (직책)
        <span class="badge bg-info info-badge">회장/총무 입력 시 SMS_2 자동생성</span>
      </label>
      <input type="text" name="remark" id="remark_field" class="form-control" value="<?= htmlspecialchars($row['remark']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">
        <i class="bi bi-chat-dots"></i> SMS
      </label>
      <input type="text" name="sms" class="form-control" value="<?= htmlspecialchars($row['sms']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">
        <i class="bi bi-chat-square-dots"></i>
        SMS_2 (다중발송)
        <span class="badge bg-warning info-badge" id="auto_badge" style="display:none;">자동생성됨</span>
      </label>
      <input type="text" name="sms_2" id="sms_2_field" class="form-control" value="<?= htmlspecialchars($row['sms_2']) ?>">
      <small class="text-muted">회장/총무가 아닌 경우 수동입력 가능</small>
    </div>

    <div class="mb-3">
      <label class="form-label">
        <i class="bi bi-shield-check"></i> 권한 (user_level)
      </label>
      <input type="text" name="user_level" class="form-control" value="<?= htmlspecialchars($row['user_level']) ?>">
    </div>

    <div class="text-center mt-4 d-flex justify-content-between">
      <button type="submit" class="btn btn-primary">저장</button>
      <a href="tel_edit.php" class="btn btn-secondary">취소</a>
    </div>
  </form>
</div>

<script>
// 전화번호 자동 하이픈 함수
function formatPhoneNumber(value) {
    value = value.replace(/[^0-9]/g, ''); // 숫자만
    if (value.length < 4) return value;
    if (value.length < 7) return value.replace(/(\d{3})(\d+)/, '$1-$2');
    return value.replace(/(\d{3})(\d{4})(\d+)/, '$1-$2-$3');
}

document.addEventListener("DOMContentLoaded", () => {
    const telInput = document.querySelector("input[name='tel']");
    const smsInput = document.querySelector("input[name='sms']");
    const sms2Input = document.getElementById("sms_2_field");
    const remarkInput = document.getElementById("remark_field");
    const autoBadge = document.getElementById("auto_badge");
    const currentIdx = "<?= $row['idx'] ?>"; // 현재 수정 중인 회원의 idx

    /* ---------------------
       TEL → SMS 자동복사
    --------------------- */
    telInput.addEventListener("input", () => {
        let digits = telInput.value.replace(/[^0-9]/g, '');
        if (digits.length > 11) digits = digits.slice(0,11);

        telInput.value = formatPhoneNumber(digits);
        smsInput.value = telInput.value;
    });


    

    /* ---------------------
       REMARK 변경 시 SMS_2 자동생성 체크
    --------------------- */
    remarkInput.addEventListener("input", checkAndGenerateSms2);

    async function checkAndGenerateSms2() {
        const remarkValue = remarkInput.value.trim();
        
        // 회장 또는 총무가 포함되어 있는지 확인
        if (remarkValue.includes('회장') || remarkValue.includes('총무')) {
            // 서버에서 전체 회원 전화번호 가져오기 (현재 회원 제외)
            try {
                const response = await fetch('get_all_phones.php?exclude_idx=' + currentIdx);
                const data = await response.json();
                
                if (data.success) {
                    sms2Input.value = data.phones.join(',');
                    sms2Input.classList.add('auto-generated');
                    sms2Input.readOnly = true;
                    autoBadge.style.display = 'inline-block';
                }
            } catch (error) {
                console.error('전화번호 가져오기 실패:', error);
            }
        } else {
            // 회장/총무가 아니면 수동입력 가능
            sms2Input.classList.remove('auto-generated');
            sms2Input.readOnly = false;
            autoBadge.style.display = 'none';
        }
    }

    // 페이지 로드 시 초기 체크
    checkAndGenerateSms2();

    /* ---------------------
       SMS_2 다중 입력 (수동입력 시)
    --------------------- */
    sms2Input.addEventListener("input", () => {
        // 읽기전용이면 입력 무시
        if (sms2Input.readOnly) return;

        let raw = sms2Input.value.replace(/[^0-9,]/g, ''); // 숫자와 쉼표만
        let numbers = raw.split(',').filter(n => n.length > 0);
        let result = [];

        numbers.forEach(num => {
            if (num.length === 11) {
                result.push(formatPhoneNumber(num));
            } else {
                result.push(num); // 입력 중
            }
        });

        sms2Input.value = result.join(',');
    });
});
</script>

</body>
</html>