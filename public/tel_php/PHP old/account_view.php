<?php
// account_view.php

require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');

// DB 연결은 db-connect-pdo.php에서 처리한다고 가정
try {
    // db-connect-pdo.php에서 $pdo가 생성되었다고 가정
    if (!isset($pdo) || !$pdo instanceof PDO) {
        // 임시 방어 코드 (db-connect-pdo.php 내용에 따라 수정 필요)
        $host = 'localhost'; // 실제 DB 정보로 대체
        $dbname = 'your_dbname'; // 실제 DB 정보로 대체
        $username = 'your_username'; // 실제 DB 정보로 대체
        $password = 'your_password'; // 실제 DB 정보로 대체
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    die("DB 접속 오류: " . $e->getMessage());
}

// 현재 연도 + 선택월
// 🚨 라인 24 부근의 공백을 일반 공백으로 대체했습니다.
$currentYear = date('Y');
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// 날짜 포맷 함수
function formatDateWithWeekday($datetime){
    if (!$datetime || $datetime === '0000-00-00 00:00:00') return '-';
    $ts = strtotime($datetime);
    if ($ts === false) return $datetime;
    $week = mb_substr("일월화수목금토", date('w', $ts), 1);
    return date("Y/m/d", $ts) . "($week) " . date("H:i", $ts);
}

/* ======================================================
    🔹 1) 1~현재월 수입/지출 전체 조회
====================================================== */

// 수입
$stmt = $pdo->prepare("SELECT date, amount FROM income_table 
                        WHERE YEAR(date)=? AND MONTH(date)<=? 
                        ORDER BY date ASC");
$stmt->execute([$currentYear, $currentMonth]);
$incomeAll = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 지출
$stmt = $pdo->prepare("SELECT date, amount FROM expense_table 
                        WHERE YEAR(date)=? AND MONTH(date)<=? 
                        ORDER BY date ASC");
$stmt->execute([$currentYear, $currentMonth]);
$expenseAll = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 월별 합계
$monthlyIncomeTotals = array_fill(1, 12, 0);
$monthlyExpenseTotals = array_fill(1, 12, 0);

foreach ($incomeAll as $tr) {
    $m = (int)date('n', strtotime($tr['date']));
    $monthlyIncomeTotals[$m] += $tr['amount'];
}
foreach ($expenseAll as $tr) {
    $m = (int)date('n', strtotime($tr['date']));
    $monthlyExpenseTotals[$m] += $tr['amount'];
}

// 선택 월 합계
$selectedMonthIncomeTotal = $monthlyIncomeTotals[$currentMonth];
$selectedMonthExpenseTotal = $monthlyExpenseTotals[$currentMonth];

// 월결산액
$monthlyBalance = $selectedMonthIncomeTotal - $selectedMonthExpenseTotal;

// 연간 누계
$yearIncomeTotal = 0;
$yearExpenseTotal = 0;
for ($i = 1; $i <= $currentMonth; $i++) {
    $yearIncomeTotal += $monthlyIncomeTotals[$i];
    $yearExpenseTotal += $monthlyExpenseTotals[$i];
}

// 총잔액
$balance = $yearIncomeTotal - $yearExpenseTotal;


/* ======================================================
    🔹 2) 선택월 상세 조회
====================================================== */
$stmt = $pdo->prepare("SELECT * FROM income_table 
                        WHERE MONTH(date)=? AND YEAR(date)=? 
                        ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$incomeTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM expense_table 
                        WHERE MONTH(date)=? AND YEAR(date)=? 
                        ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$expenseTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>사용내역서보기</title>

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
/* 기본 테이블 */
.table th, .table td { text-align:center; vertical-align:middle; }
.amount-column { text-align:right; }

/* 월 선택 버튼 */
.month-selector a { margin:5px 1px 1px 1px; }
.month-selector a.active { background:#007bff; color:white; }

/* 안내 박스 */
.alert-info { background:#d1ecf1; }

/* 소제목 */
.section-title {
    display:block;
    width:100%;
    padding:10px 0;
    margin:20px 0 10px 0;
    border-radius:12px;
    background-color:#e3f2fd;
    color:#333;
    font-weight:600;
    text-align:center;
    font-size:1.1rem;
}

/* 반응형 */
.table-responsive { width:100%; overflow-x:auto; }

/* 하단 결산 영역 (PC: 가로 / 모바일: 세로) */
.balance-box {
    display:flex;
    justify-content:center;
    gap:30px;
    font-size:1.3rem;
    font-weight:700;
    margin:25px 0;
    text-align:center;
}

.balance-item span {
    font-size:1.35rem;
}

/* 툴팁을 적용할 텍스트에 커서 스타일 추가 */
.balance-item [data-bs-toggle="tooltip"] {
    cursor: pointer;
    text-decoration: underline dotted;
}

/* PC 기본 컬럼 너비 */
.table th:nth-child(1), .table td:nth-child(1) { width: 50px; }  /* NO */
.table th:nth-child(2), .table td:nth-child(2) { width: 180px; } /* 일자 */
.table th:nth-child(3), .table td:nth-child(3) { width: auto; }  /* 항목 */
.table th:nth-child(4), .table td:nth-child(4) { width: auto; }  /* 비고 */
.table th:nth-child(5), .table td:nth-child(5) { width: 110px; } /* 금액 */

@media(max-width:576px){
    .table th, .table td { font-size:0.85rem; padding:0.3rem; }
    .section-title { font-size:1rem; padding:8px 0; }
    .month-selector a { font-size:0.9rem; padding:0.2rem 0.6rem; }

    /* 모바일에서는 세로 배치 */
    .balance-box {
        flex-direction:column;
        gap:10px;
        font-size:1.2rem;
    }

    /* 모바일 컬럼 너비 조정 */
    .table th:nth-child(1), .table td:nth-child(1) { 
        width: 30px;  /* NO - 최소화 */
        font-size: 0.75rem;
    }
    
    .table th:nth-child(2), .table td:nth-child(2) { 
        width: 70px;  /* 일자 - 좁게 (줄바꿈 발생) */
        font-size: 0.7rem;
        word-break: break-all;
        line-height: 1.2;
    }
    
    .table th:nth-child(3), .table td:nth-child(3) { 
        width: auto;  /* 항목 - 넓게 */
        min-width: 60px;
    }
    
    .table th:nth-child(4), .table td:nth-child(4) { 
        width: auto;  /* 비고 - 넓게 */
        min-width: 60px;
    }
    
    .table th:nth-child(5), .table td:nth-child(5) { 
        width: 85px;  /* 금액 - 10,000,000원까지 한줄 */
        white-space: nowrap;
        font-size: 0.8rem;
        padding-left: 2px;
        padding-right: 2px;
    }
}
</style>
</head>

<body>
<div class="container">

    <div class="text-center mt-3 mb-3">
        오늘의 날짜: <?= formatDateWithWeekday(date('Y-m-d H:i:s')) ?>
    </div>

    <div class="month-selector text-center mb-3">
        <?php for($m=1;$m<=12;$m++): ?>
            <a href="?month=<?= $m ?>"
               class="btn <?= ($m==$currentMonth ? 'btn-primary active' : 'btn-secondary') ?>">
                <?= $m ?>월
            </a>
        <?php endfor; ?>
    </div>

    <div class="alert alert-info text-center mb-3">
        📌 합계가 이상하면 월 버튼을 다시 눌러 갱신하세요!
    </div>

    <span class="section-title mt-4">[수입 목록]</span>
    <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>일자</th>
                <th>항목</th>
                <th>비고</th>
                <th>금액</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!$incomeTransactions): ?>
                <tr><td colspan="5">데이터가 없습니다.</td></tr>
            <?php else: $i=1; foreach($incomeTransactions as $tr): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= formatDateWithWeekday($tr['date']) ?></td>
                    <td><?= htmlspecialchars($tr['category']) ?></td>
                    <td><?= htmlspecialchars($tr['description']) ?></td>
                    <td class="amount-column"><?= number_format($tr['amount']) ?>원</td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>

        <tfoot>
    <tr>
        <td colspan="3" class="text-end"><strong>월수입 합계:</strong></td>
        <td colspan="2" class="text-end amount-column">
            <strong><?= number_format($selectedMonthIncomeTotal) ?>원</strong>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="text-end"><strong>월수입 누계(1~<?= $currentMonth ?>월):</strong></td>
        <td colspan="2" class="text-end amount-column">
            <strong><?= number_format($yearIncomeTotal) ?>원</strong>
        </td>
    </tr>
</tfoot>


    </table>
    </div>

    <span class="section-title mt-4">[지출 목록]</span>
    <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>일자</th>
                <th>항목</th>
                <th>비고</th>
                <th>금액</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!$expenseTransactions): ?>
                <tr><td colspan="5">데이터가 없습니다.</td></tr>
            <?php else: $i=1; foreach($expenseTransactions as $tr): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= formatDateWithWeekday($tr['date']) ?></td>
                    <td><?= htmlspecialchars($tr['category']) ?></td>
                    <td><?= htmlspecialchars($tr['description']) ?></td>
                    <td class="amount-column"><?= number_format($tr['amount']) ?>원</td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>

        <tfoot>
    <tr>
        <td colspan="3" class="text-end"><strong>월지출 합계:</strong></td>
        <td colspan="2" class="text-end amount-column">
            <strong><?= number_format($selectedMonthExpenseTotal) ?>원</strong>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="text-end"><strong>월지출 누계(1~<?= $currentMonth ?>월):</strong></td>
        <td colspan="2" class="text-end amount-column">
            <strong><?= number_format($yearExpenseTotal) ?>원</strong>
        </td>
    </tr>
</tfoot>


    </table>
    </div>

    <div class="balance-box">
      
        <div class="balance-item">
            <span
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="월결산액은 해당하는 달의 (월수입합계 - 월지출합계) 의 차액입니다."
            >
                월결산액:
            </span>
            <span class="<?= ($monthlyBalance >= 0 ? 'text-primary' : 'text-danger') ?>">
                <?= number_format($monthlyBalance) ?>원
            </span>
        </div>

        <div class="balance-item">
            <span
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="총잔액(누적):은 1월달부터 지금 선택한 달까지의 총 남아있는 금액입니다. (1월~해당월 월수입 누계금액 - 1월~해당월 월지출 누계금액)"
            >
                총잔액(누적):
            </span>
            <span class="<?= ($balance > 0 ? 'text-danger' : 'text-primary') ?>">
                <?= number_format($balance) ?>원
            </span>
        </div>
    </div>

    <div class="d-flex justify-content-center gap-3 mb-4">
        <a href="./select.php" class="btn btn-secondary btn-sm">⏪ 되돌아가기</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
</body>
</html>