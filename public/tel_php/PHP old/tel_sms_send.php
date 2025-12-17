<?php
// tel_sms_send.php
session_start();
require './php/auth_check.php';
require './php/db-connect-pdo.php';

// GET 파라미터로 제외할 전화번호 확인
$exclude_tel = $_GET['exclude_tel'] ?? '';

// 전체 회원 중 제외 전화번호를 제외하고 전화번호가 유효한 회원만 조회
try {
    $stmt = $pdo->prepare("
        SELECT name, remark, tel 
        FROM tel 
        WHERE tel IS NOT NULL AND tel != ''
        " . ($exclude_tel ? "AND tel != :exclude_tel" : "") . "
        ORDER BY name ASC
    ");
    if ($exclude_tel) {
        $stmt->execute(['exclude_tel' => $exclude_tel]);
    } else {
        $stmt->execute();
    }
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $rows = [];
}

$total_count = count($rows);

// sms: 링크용 전화번호 목록 생성
$temp_tels = array_column($rows, 'tel');
$sms_list_js = implode(',', $temp_tels);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>단체 문자 발송</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.scroll-box {
    border:1px solid #e6e9ee;
    border-radius:10px;
    padding:10px;
    max-height:700px;
    overflow-y:auto;
    background:#fafafa;
}

.grid-box {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.controls {
    margin-top:16px;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    justify-content:center;
}

.title {
    text-align: center;
    background: #f0f4ff;
    padding: 16px 0;
    margin: 20px auto 30px auto;
    width: 90%;
    max-width: 500px;
    border-radius: 25px;
    font-size: 1.4rem;
    font-weight: 700;
    color: #2a3d7c;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.15);
}

.count {
    padding: 0 14px;
    font-size: 1rem;
}

.count .number {
    color: #1a73e8; /* 파랑색 */
    font-weight: 700;
}

.btn-wide {
    flex:1 1 auto;
    min-width:150px;
    max-width:250px;
}

@media (max-width: 768px) {
    .grid-box {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (max-width: 520px) {
    .grid-box {
        grid-template-columns: repeat(2, 1fr);
    }
    .controls{
        flex-direction:column;
        align-items:center;
    }
    .btn-wide{
        width:100%;
        max-width:280px;
    }
}

.grid-item {
    background: white;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    font-size: 0.9rem;
    line-height: 1.3rem;
    border: 1px solid #eee;
}
.grid-item strong { font-weight: 600; }
</style>

<script>
function confirmSend(total, smsList) {
    if (!smsList || smsList.trim() === '') {
        alert('발송할 전화번호가 없습니다.');
        return;
    }

    const ok = confirm('회원 ' + total + '명에게 단체문자메시지를 보내겠습니까?');
    if (!ok) return;

    // SMS 앱 실행
    window.location.href = 'sms:' + smsList;
}
</script>
</head>
<body>
<div class="container-card">
  <div class="header">
    <div class="title">단체 문자 발송</div>
    <div class="count">문자메시지 보낼 인원수: <span class="number"><?php echo $total_count; ?></span> 명</div>
  </div>

  <div class="scroll-box mt-3" aria-label="문자 수신자 목록">
    <div class="grid-box">
    <?php
    if ($total_count === 0) {
        echo '<div class="grid-item">목록이 비어 있습니다.</div>';
    } else {
        foreach ($rows as $i => $row) {
            $name   = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
            $remark = htmlspecialchars($row['remark'], ENT_QUOTES, 'UTF-8');
            $tel    = htmlspecialchars($row['tel'], ENT_QUOTES, 'UTF-8');

            echo "
            <div class='grid-item'>
                <strong>" . ($i+1) . ". $name</strong><br>
                ($remark)<br>
                📞 $tel
            </div>";
        }
    }
    ?>
    </div>
  </div>

  <div class="controls mt-5">
    <button type="button" class="btn btn-primary btn-wide"
        onclick="confirmSend(<?php echo $total_count; ?>, '<?php echo $sms_list_js; ?>')">
        단체문자보내기
    </button>

    <button type="button" class="btn btn-success btn-wide" onclick="location.href='tel_edit.php'">모임 담당자 변경</button>
    <button type="button" class="btn btn-secondary btn-wide" onclick="location.href='tel_view.php'">돌아가기</button>
  </div>

  <div style="margin:12px 0 0 30px; font-size:0.9rem; color:#666;">
      ※ 모바일에서 잘 동작합니다. PC 브라우저는 sms: 링크가 작동하지 않을 수 있습니다.
  </div>
</div>
</body>
</html>
