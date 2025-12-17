<?php
require 'php/auth_check.php';
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB 접속 오류: " . $e->getMessage());
}

$currentYear = date('Y');
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');

$stmt = $pdo->prepare("SELECT * FROM income_table WHERE YEAR(date)=? AND MONTH(date)=? ORDER BY date ASC");
$stmt->execute([$currentYear, $currentMonth]);
$incomeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM expense_table WHERE YEAR(date)=? AND MONTH(date)=? ORDER BY date ASC");
$stmt->execute([$currentYear, $currentMonth]);
$expenseData = $stmt->fetchAll(PDO::FETCH_ASSOC);

function sumAmount($data) {
    $sum = 0;
    foreach($data as $item) {
        $sum += $item['amount'];
    }
    return $sum;
}

$monthlyIncome = sumAmount($incomeData);
$monthlyExpense = sumAmount($expenseData);

$stmt = $pdo->prepare("SELECT * FROM income_table WHERE YEAR(date)=? AND MONTH(date)<=?");
$stmt->execute([$currentYear, $currentMonth]);
$yearIncome = sumAmount($stmt->fetchAll(PDO::FETCH_ASSOC));

$stmt = $pdo->prepare("SELECT * FROM expense_table WHERE YEAR(date)=? AND MONTH(date)<=?");
$stmt->execute([$currentYear, $currentMonth]);
$yearExpense = sumAmount($stmt->fetchAll(PDO::FETCH_ASSOC));

$monthlyBalance = $monthlyIncome - $monthlyExpense;
$balance = $yearIncome - $yearExpense;

$balanceColor = ($monthlyBalance >= 0) ? 'color:#d32f2f;' : 'color:#1976D2;';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>사용내역서 보기</title>

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
    --primary-gradient: linear-gradient(135deg, #667eea, #764ba2);
    --income-color: #4CAF50;
    --expense-color: #f44336;
}

body { 
    background: var(--primary-gradient); 
    font-family:'Noto Sans KR', sans-serif; 
    min-height:100vh; 
    padding:4px;
}

.container {
    max-width: 100%;
    padding-left: 0;
    padding-right: 0;
    margin-left: 0;
    margin-right: 0;
}

.highlight-red { color: red; }
.highlight-blue { color: blue; }

.header-card, .section-card {
    background:white; 
    border-radius:20px; 
    box-shadow:0 10px 40px rgba(0,0,0,0.2); 
    padding:8px; 
    margin:20px 10px 10px;
}

.date-display { 
    text-align:center; 
    color:#667eea; 
    font-weight:600; 
    margin-bottom:20px;
}

.month-buttons { 
    display:flex; 
    flex-wrap:wrap; 
    gap:6px; 
    justify-content:center; 
    margin-bottom:15px;
}

.btn-month { 
    text-decoration: none;
    padding:2px 5px; 
    border-radius:10px; 
    border:2px solid #e0e0e0; 
    background:white; 
    color:#666; 
    font-weight:600; 
    cursor:pointer; 
    transition:0.3s;
}

.btn-month:hover { 
    border-color:#667eea; 
    color:#667eea; 
}

.btn-month.active { 
    background: var(--primary-gradient); 
    color:white; 
    border-color:transparent; 
    transform:scale(1.05); 
}

.table-wrapper { 
    overflow-x:auto; 
    border-radius:12px; 
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.custom-table { 
    margin:0; 
    border-radius:12px; 
    overflow:hidden; 
    background:white;
    width: 100%;
    table-layout: fixed;
}

.custom-table thead { 
    background: var(--primary-gradient); 
    color:white;
}

.custom-table th, .custom-table td { 
    padding:6px; 
    text-align:center;
    word-break: break-word;
    vertical-align: middle;
}

.custom-table th:nth-child(1), .custom-table td:nth-child(1) { width: 50px; }
.custom-table th:nth-child(2), .custom-table td:nth-child(2) { width: 130px; }
.custom-table th:nth-child(3), .custom-table td:nth-child(3) { width: auto; }
.custom-table th:nth-child(4), .custom-table td:nth-child(4) { width: auto; }
.custom-table th:nth-child(5), .custom-table td:nth-child(5) { width: 100px; }

@media (max-width: 768px) {
    .custom-table th, .custom-table td { 
        padding: 4px; 
        font-size: 0.85rem; 
    }

    .custom-table th:nth-child(1), .custom-table td:nth-child(1) { width: 35px; } 
    
    .custom-table th:nth-child(2), .custom-table td:nth-child(2) { 
        width: 85px; 
        font-size: 0.75rem; 
        letter-spacing: -0.5px;
    } 
    
    .custom-table th:nth-child(3), .custom-table td:nth-child(3) { width: auto; }
    .custom-table th:nth-child(4), .custom-table td:nth-child(4) { width: auto; }
    
    .custom-table th:nth-child(5), .custom-table td:nth-child(5) { 
        width: 80px; 
        white-space: nowrap; 
        letter-spacing: -0.5px;
    }
}

.amount-column { 
    text-align:right; 
    font-weight:700; 
    color:#333;
    padding-right: 8px;
}

.summary-value { 
    text-align:right !important; 
    padding-right: 8px;
}

.summary-label-income, 
.summary-label-expense {
    text-align: center !important;
}

.summary-label-income { 
    background:linear-gradient(135deg,#F2C3D8,#F19AC1); 
    color:var(--income-color); 
    font-weight:700;
}

.summary-label-expense { 
    background:linear-gradient(135deg,#B3D6ED,#7EC2ED); 
    color:var(--expense-color); 
    font-weight:700;
}

.balance-card {
    background: #f0f2f5;
    border-radius:20px; 
    padding:30px; 
    color:#333; 
    text-align:center; 
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin:10px;
}

.balance-text {
    font-size:1.3rem; 
    font-weight:700; 
    margin:0;
    text-align:center;
    display:block;
}

.nav-buttons { 
    display: flex; 
    gap: 10px; 
    margin-top: 20px; 
}

.btn-nav { 
    flex:1; 
    padding:15px; 
    border-radius:12px; 
    border:none; 
    background:white; 
    color:#667eea; 
    font-weight:700; 
    text-decoration:none; 
    text-align:center; 
    transition:all .3s; 
    border:2px solid white; 
}

.btn-nav:hover { 
    background:transparent; 
    color:#FF88A7; 
    border-color:white; 
}

.description-link {
    color: #667eea;
    cursor: pointer;
    text-decoration: none;
}

.description-link:hover {
    text-decoration: underline;
    color: #764ba2;
}

.modal-content {
    border-radius: 20px;
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

.modal-body {
    padding: 30px;
    font-size: 1.1rem;
    line-height: 1.6;
    word-break: break-word;
}
</style>
</head>
<body>
<div class="container">

<div class="header-card">
    <div class="date-display"><?=date('Y/m/d H:i')?></div>
    <div class="month-buttons">
        <?php for($m=1;$m<=12;$m++): ?>
            <a class="btn-month <?=($m==$currentMonth?'active':'')?>" href="?month=<?=$m?>"><?=$m?>월</a>
        <?php endfor; ?>
    </div>
</div>

<div class="section-card">
    <h3 class="section-title income-title">
        📉 <span class="highlight-red">수입</span> 목록
    </h3>

    <div class="table-wrapper">
        <table class="table custom-table">
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
                <?php 
                $i=1; 
                foreach($incomeData as $item): 
                    $desc = htmlspecialchars($item['description']);
                    $descLen = mb_strlen($desc);
                ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><?=date('Y/m/d H:i', strtotime($item['date']))?></td>
                    <td><?=htmlspecialchars($item['category'])?></td>
                    <td>
                        <?php if($descLen > 15): ?>
                            <span class="description-link" onclick="showDescriptionModal('<?=addslashes($desc)?>')">
                                <?=mb_substr($desc, 0, 15)?>...
                            </span>
                        <?php else: ?>
                            <?=$desc?>
                        <?php endif; ?>
                    </td>
                    <td class="amount-column"><?=number_format($item['amount'])?>원</td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($incomeData)): ?>
                <tr><td colspan="5" class="text-center">이번 달 수입 내역이 없습니다.</td></tr>
                <?php endif; ?>
            </tbody>

            <tfoot>
                <tr class="summary-row">
                    <td colspan="3" class="summary-label-income">월수입 합계</td>
                    <td colspan="2" class="summary-value summary-label-income"><?=number_format($monthlyIncome)?>원</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="3" class="summary-label-income">1~<?=$currentMonth?>월 월수입 누계</td>
                    <td colspan="2" class="summary-value summary-label-income"><?=number_format($yearIncome)?>원</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="section-card">
    <h3 class="section-title income-title">
        📉 <span class="highlight-blue">지출</span> 목록
    </h3>
    <div class="table-wrapper">
        <table class="table custom-table">
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
                <?php 
                $i=1; 
                foreach($expenseData as $item): 
                    $desc = htmlspecialchars($item['description']);
                    $descLen = mb_strlen($desc);
                ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><?=date('Y/m/d H:i', strtotime($item['date']))?></td>
                    <td><?=htmlspecialchars($item['category'])?></td>
                    <td>
                        <?php if($descLen > 15): ?>
                            <span class="description-link" onclick="showDescriptionModal('<?=addslashes($desc)?>')">
                                <?=mb_substr($desc, 0, 15)?>...
                            </span>
                        <?php else: ?>
                            <?=$desc?>
                        <?php endif; ?>
                    </td>
                    <td class="amount-column"><?=number_format($item['amount'])?>원</td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($expenseData)): ?>
                <tr><td colspan="5" class="text-center">이번 달 지출 내역이 없습니다.</td></tr>
                <?php endif; ?>
            </tbody>

            <tfoot>
                <tr class="summary-row">
                    <td colspan="3" class="summary-label-expense">월지출 합계</td>
                    <td colspan="2" class="summary-value summary-label-expense"><?=number_format($monthlyExpense)?>원</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="3" class="summary-label-expense">1~<?=$currentMonth?>월 월지출 누계</td>
                    <td colspan="2" class="summary-value summary-label-expense"><?=number_format($yearExpense)?>원</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="balance-card">
    <p class="balance-text">
        월결산액:
        <span 
            data-bs-toggle="tooltip" 
            data-bs-placement="top"
            title="월결산액은 해당하는 달의 (월수입합계) - (월지출합계) 의 차액입니다."
            class="balance-month" 
            style="<?= $balanceColor ?>"
        >
            <?=number_format($monthlyBalance)?>원
        </span>
        <br>

        총잔액(누적):
        <span 
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="총잔액(누적):은 1월달부터 지금 선택한 달까지의 총 남아있는 금액입니다. (1월~해당월 월수입 누계금액) - (1월~해당월 월지출 누계금액)"
            class="balance-total" 
            style="color:#d32f2f;"
        >
            <?=number_format($balance)?>원
        </span>
    </p>

    <div class="nav-buttons">
        <a href="admin_member.php" class="btn-nav">⏪ 되돌아가기</a>
    </div>
</div>

</div>

<!-- 비고 전체 내용 모달 -->
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">비고 전체 내용</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="descriptionModalBody">
            </div>
        </div>
    </div>
</div>

<script>
function showDescriptionModal(description) {
    document.getElementById('descriptionModalBody').textContent = description;
    var modal = new bootstrap.Modal(document.getElementById('descriptionModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>