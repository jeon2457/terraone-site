<?php
session_start();
// ⭐ 관리자 인증
require 'php/auth_check.php';
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');

ob_start();

// ⭐⭐⭐ POST 처리 (삭제) ⭐⭐⭐
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = intval($_POST['delete']);
        $type = $_POST['type'];

        if ($type === '수입') {
            $stmt = $pdo->prepare("DELETE FROM income_table WHERE id = ?");
        } else {
            $stmt = $pdo->prepare("DELETE FROM expense_table WHERE id = ?");
        }
        $stmt->execute([$id]);

        // 현재 월 유지
        $redirectMonth = isset($_GET['month']) ? $_GET['month'] : date('n');
        header("Location: " . $_SERVER['PHP_SELF'] . "?month=" . $redirectMonth);
        exit;
    }
}

// 현재 연도 + 선택월
$currentYear  = date('Y');
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// 날짜 포맷
function formatDateWithWeekday($datetime) {
    if (empty($datetime) || $datetime === '0000-00-00 00:00:00') return '-';
    $ts = strtotime($datetime);
    if ($ts === false) return $datetime;

    $week = mb_substr("일월화수목금토", date('w', $ts), 1);
    return date("Y/m/d", $ts) . "($week) " . date("H:i", $ts);
}

/*  
======================================================
  🔹 [1] 1월~선택월까지 월별 누계 계산
======================================================
*/

// 수입 조회
$stmt = $pdo->prepare("SELECT date, amount FROM income_table 
                       WHERE YEAR(date)=? AND MONTH(date)<=? 
                       ORDER BY date ASC");
$stmt->execute([$currentYear, $currentMonth]);
$incomeAll = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 지출 조회
$stmt = $pdo->prepare("SELECT date, amount FROM expense_table 
                       WHERE YEAR(date)=? AND MONTH(date)<=? 
                       ORDER BY date ASC");
$stmt->execute([$currentYear, $currentMonth]);
$expenseAll = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 월별 배열 초기화
$monthlyIncomeTotals  = array_fill(1, 12, 0);
$monthlyExpenseTotals = array_fill(1, 12, 0);

// 누계 저장
foreach ($incomeAll as $tr) {
    $m = (int)date('n', strtotime($tr['date']));
    $monthlyIncomeTotals[$m] += $tr['amount'];
}
foreach ($expenseAll as $tr) {
    $m = (int)date('n', strtotime($tr['date']));
    $monthlyExpenseTotals[$m] += $tr['amount'];
}

// 선택월 합계
$selectedMonthIncomeTotal  = $monthlyIncomeTotals[$currentMonth];
$selectedMonthExpenseTotal = $monthlyExpenseTotals[$currentMonth];

// 선택월 월 결산
$monthlyBalance = $selectedMonthIncomeTotal - $selectedMonthExpenseTotal;

// 1~현재월까지 누계 합산
$yearIncomeTotal = 0;
$yearExpenseTotal = 0;

for ($i = 1; $i <= $currentMonth; $i++) {
    $yearIncomeTotal  += $monthlyIncomeTotals[$i];
    $yearExpenseTotal += $monthlyExpenseTotals[$i];
}

$balance = $yearIncomeTotal - $yearExpenseTotal;

/*  
======================================================
  🔹 [2] 선택월 수입 / 지출 상세 조회
======================================================
*/

// 수입
$stmt = $pdo->prepare("SELECT * FROM income_table WHERE MONTH(date)=? AND YEAR(date)=? ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$incomeTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 지출
$stmt = $pdo->prepare("SELECT * FROM expense_table WHERE MONTH(date)=? AND YEAR(date)=? ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$expenseTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>사용내역서편집</title>

<!-- 파비콘 아이콘들 -->
<link rel="icon" href="/favicon.png?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="./favicons/2/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="./favicons/2/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="./favicons/2/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="./favicons/2/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="./favicons/2/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="./favicons/2/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="./favicons/2/apple-icon-72x72.png">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.table th, .table td { 
    text-align:center; 
    vertical-align:middle; 
}
.amount-column { 
    text-align:right !important; 
}
.month-selector a { 
    margin:5px 1px 3px 1px; 
}
.month-selector a.active { 
    background:#007bff; 
    color:#fff; 
}

/* 소제목 버튼형태 */
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

@media(max-width:576px){
    .table th, .table td { font-size:0.85rem; padding:0.3rem; }
    .section-title { font-size:1rem; padding:8px 0; }
    .month-selector a { font-size:0.9rem; padding:0.2rem 0.6rem; }
    .action-btn {
        margin-bottom: 6px !important;
    }
}


</style>
</head>
<body>
<div class="container">

    <!-- 오늘 날짜 -->
    <div class="text-center mt-3 mb-3">
        오늘의 날짜: <?= formatDateWithWeekday(date('Y-m-d H:i:s')) ?>
    </div>

    <!-- 월 선택 -->
    <div class="month-selector text-center mb-2">
        <?php for ($m=1; $m<=12; $m++): ?>
            <a class="btn <?= ($m == $currentMonth ? 'btn-primary active' : 'btn-secondary') ?>"
               href="?month=<?= $m ?>"><?= $m ?>월</a>
        <?php endfor; ?>
    </div>

    <!-- ✔ 수입 목록 -->
    <span class="section-title mt-4">[수입 목록]</span>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>일자</th>
                <th>항목</th>
                <th>비고</th>
                <th>금액</th>
                <th>관리</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($incomeTransactions)): ?>
                <tr><td colspan="6">데이터가 없습니다.</td></tr>
            <?php else: $cnt=1; ?>
                <?php foreach ($incomeTransactions as $tr): ?>
                <tr>
                    <td><?= $cnt++ ?></td>
                    <td><?= formatDateWithWeekday($tr['date']) ?></td>
                    <td><?= htmlspecialchars($tr['category']) ?></td>
                    <td><?= htmlspecialchars($tr['description']) ?></td>
                    <td class="amount-column"><?= number_format($tr['amount']) ?>원</td>
                    <td>
                        <form method="GET" action="account_edit_form.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $tr['id'] ?>">
                            <input type="hidden" name="type" value="수입">
                            <button class="btn btn-primary btn-sm">수정</button>
                        </form>

                        <form method="POST" style="display:inline;" onsubmit="return confirm('삭제하시겠습니까?');">
                            <input type="hidden" name="type" value="수입">
                            <button name="delete" value="<?= $tr['id'] ?>" class="btn btn-danger btn-sm">삭제</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>월수입 합계:</strong></td>
                <td colspan="2" class="amount-column"><strong><?= number_format($selectedMonthIncomeTotal) ?>원</strong></td>
            </tr>

            <tr>
                <td colspan="4" class="text-end"><strong>월수입 누계(1~<?= $currentMonth ?>월):</strong></td>
                <td colspan="2" class="amount-column"><strong><?= number_format($yearIncomeTotal) ?>원</strong></td>
            </tr>
        </tfoot>


    </table>

    <!-- ✔ 지출 목록 -->
    <span class="section-title mt-4">[지출 목록]</span>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>일자</th>
                <th>항목</th>
                <th>비고</th>
                <th>금액</th>
                <th>관리</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($expenseTransactions)): ?>
                <tr><td colspan="6">데이터가 없습니다.</td></tr>
            <?php else: $cnt=1; ?>
                <?php foreach ($expenseTransactions as $tr): ?>
                <tr>
                    <td><?= $cnt++ ?></td>
                    <td><?= formatDateWithWeekday($tr['date']) ?></td>
                    <td><?= htmlspecialchars($tr['category']) ?></td>
                    <td><?= htmlspecialchars($tr['description']) ?></td>
                    <td class="amount-column"><?= number_format($tr['amount']) ?>원</td>
                    <td>
                        <form method="GET" action="account_edit_form.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $tr['id'] ?>">
                            <input type="hidden" name="type" value="지출">
                            <button class="btn btn-primary btn-sm">수정</button>
                        </form>

                        <form method="POST" style="display:inline;" onsubmit="return confirm('삭제하시겠습니까?');">
                            <input type="hidden" name="type" value="지출">
                            <button name="delete" value="<?= $tr['id'] ?>" class="btn btn-danger btn-sm">삭제</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>월지출 합계:</strong></td>
                <td colspan="2" class="amount-column"><strong><?= number_format($selectedMonthExpenseTotal) ?>원</strong></td>
            </tr>

            <tr>
                <td colspan="4" class="text-end"><strong>월지출 누계(1~<?= $currentMonth ?>월):</strong></td>
                <td colspan="2" class="amount-column"><strong><?= number_format($yearExpenseTotal) ?>원</strong></td>
            </tr>
        </tfoot>

    </table>

    <!-- ✔ 월 결산 + 총잔액 -->
    <div class="text-end mb-4">
        <h5>
            월결산액:
            <span class="<?= ($monthlyBalance >= 0 ? 'text-primary' : 'text-danger') ?>">
                <?= number_format($monthlyBalance) ?>원
            </span>
            &nbsp;&nbsp;

            총잔액(누적):
            <span class="<?php
                echo ($balance > 0 ? 'text-danger' : ($balance < 0 ? 'text-primary' : 'text-secondary'));
            ?>">
                <?= number_format($balance) ?>원
            </span>
        </h5>
    </div>

    <!-- 버튼 -->
    <div class="d-flex justify-content-center gap-3 mb-5">
        <a href="./images_view.php" class="btn btn-success btn-sm">영수증 사진보기</a>
        <a href="./images_upload.php" class="btn btn-success btn-sm">영수증 입력하기</a>
        <a href="./select.php" class="btn btn-secondary btn-sm">⏪ 되돌아가기</a>
    </div>

</div>
</body>
</html>

<?php ob_end_flush(); ?>
