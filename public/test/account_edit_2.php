<?php
require 'php/auth_check.php';
require 'php/db-connect.php'; // DB 접속 정보

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB 접속 오류: " . $e->getMessage());
}

$currentYear = date('Y');
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// 수정/업데이트 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $type = $_POST['type'] ?? null;

    if (isset($_POST['update'])) {
        $stmt = $pdo->prepare("UPDATE " . ($type==='수입'?'income_table':'expense_table') . " SET date=?, category=?, description=?, amount=? WHERE id=?");
        $stmt->execute([$_POST['date'], $_POST['category'], $_POST['description'], $_POST['amount'], $id]);
        header("Location: account_edit.php?month=$currentMonth");
        exit;
    }
    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM " . ($type==='수입'?'income_table':'expense_table') . " WHERE id=?");
        $stmt->execute([$id]);
        header("Location: account_edit.php?month=$currentMonth");
        exit;
    }
}

// 월별 데이터 조회
$stmt = $pdo->prepare("SELECT * FROM income_table WHERE YEAR(date)=? AND MONTH(date)=? ORDER BY date ASC");
$stmt->execute([$currentYear, $currentMonth]);
$incomeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM expense_table WHERE YEAR(date)=? AND MONTH(date)=? ORDER BY date ASC");
$stmt->execute([$currentYear, $currentMonth]);
$expenseData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 합계 계산
function sumAmount($data) {
    return array_sum(array_map(fn($item)=>$item['amount'], $data));
}
$monthlyIncome = sumAmount($incomeData);
$monthlyExpense = sumAmount($expenseData);

$stmt = $pdo->prepare("SELECT * FROM income_table WHERE YEAR(date)=? AND MONTH(date)<=?");
$stmt->execute([$currentYear, $currentMonth]);
$yearIncome = sumAmount($stmt->fetchAll(PDO::FETCH_ASSOC));

$stmt = $pdo->prepare("SELECT * FROM expense_table WHERE YEAR(date)=? AND MONTH(date)<=?");
$stmt->execute([$currentYear, $currentMonth]);
$yearExpense = sumAmount($stmt->fetchAll(PDO::FETCH_ASSOC));

$balance = $yearIncome - $yearExpense;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>사용내역서 편집</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea, #764ba2);
    --income-color: #4CAF50;
    --expense-color: #f44336;
}
body { background: var(--primary-gradient); font-family:'Noto Sans KR', sans-serif; min-height:100vh; padding:20px;}
.container { max-width:1200px; margin:auto;}
.header-card, .section-card { background:white; border-radius:20px; box-shadow:0 10px 40px rgba(0,0,0,0.2); padding:25px; margin-bottom:20px;}
.date-display { text-align:center; color:#667eea; font-weight:600; margin-bottom:20px;}
.month-buttons { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-bottom:15px;}
.btn-month { padding:10px 20px; border-radius:10px; border:2px solid #e0e0e0; background:white; color:#666; font-weight:600; cursor:pointer; transition:0.3s;}
.btn-month:hover { border-color:#667eea; color:#667eea;}
.btn-month.active { background: var(--primary-gradient); color:white; border-color:transparent; transform:scale(1.05);}
.table-wrapper { overflow-x:auto; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1);}
.custom-table { margin:0; border-radius:12px; overflow:hidden; background:white;}
.custom-table thead { background: var(--primary-gradient); color:white;}
.custom-table th, .custom-table td { padding:12px; text-align:center;}
.amount-column { text-align:right; font-weight:700; color:#333;}
.summary-row { background:linear-gradient(135deg,#f8f9ff,#e8eaf6); font-weight:700;}
.summary-label-income { background:linear-gradient(135deg,#e8f5e9,#c8e6c9); color:var(--income-color); font-weight:700; text-align:center;}
.summary-label-expense { background:linear-gradient(135deg,#ffebee,#ffcdd2); color:var(--expense-color); font-weight:700; text-align:center;}
.summary-value { font-size:1.2rem; font-weight:700; color:#667eea; text-align:center;}
.balance-card { background: var(--primary-gradient); border-radius:20px; padding:30px; color:white; text-align:center; box-shadow:0 10px 40px rgba(0,0,0,0.3); margin-bottom:20px;}
.balance-title { font-size:1.5rem; font-weight:700; margin-bottom:15px; opacity:0.9;}
.balance-amount { font-size:2.5rem; font-weight:900; margin:0;}
.balance-positive { color:#4CAF50; text-shadow:0 2px 10px rgba(76,175,80,0.3);}
.balance-negative { color:#f44336; text-shadow:0 2px 10px rgba(244,67,54,0.3);}
</style>
</head>
<body>
<div class="container">
<div class="header-card">
    <div class="date-display"><?=date('Y/m/d H:i')?></div>
    <div class="month-buttons">
        <?php for($m=1;$m<=12;$m++):
            $active = $m==$currentMonth?'active':'';
        ?>
        <a class="btn-month <?=$active?>" href="?month=<?=$m?>"><?=$m?>월</a>
        <?php endfor;?>
    </div>
</div>

<div class="section-card">
    <h3 class="section-title income-title">📈 수입 목록</h3>
    <div class="table-wrapper">
        <table class="table custom-table">
            <thead>
                <tr><th>NO</th><th>일자</th><th>항목</th><th>비고</th><th>금액</th><th>관리</th></tr>
            </thead>
            <tbody>
                <?php $i=1; foreach($incomeData as $item): ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><?=$item['date']?></td>
                    <td><?=$item['category']?></td>
                    <td><?=$item['description']?></td>
                    <td class="amount-column"><?=number_format($item['amount'])?>원</td>
                    <td>
                        <form method="POST">
                            <?php foreach($item as $k=>$v) echo "<input type='hidden' name='$k' value='$v'>";?>
                            <button type="submit" name="update" value="<?=$item['id']?>" class="btn btn-primary">업데이트</button>
                            <button type="submit" name="delete" value="<?=$item['id']?>" class="btn btn-danger">삭제</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach;?>
                <?php if(empty($incomeData)): ?>
                <tr><td colspan="6" class="text-center">이번 달 수입 내역이 없습니다.</td></tr>
                <?php endif;?>
            </tbody>
            <tfoot>
                <tr class="summary-row">
                    <td colspan="2" class="summary-label-income">월수입 합계</td>
                    <td colspan="4" class="summary-value"><?=number_format($monthlyIncome)?>원</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="2" class="summary-label-income">년수입 합계</td>
                    <td colspan="4" class="summary-value"><?=number_format($yearIncome)?>원</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="section-card">
    <h3 class="section-title expense-title">📉 지출 목록</h3>
    <div class="table-wrapper">
        <table class="table custom-table">
            <thead>
                <tr><th>NO</th><th>일자</th><th>항목</th><th>비고</th><th>금액</th><th>관리</th></tr>
            </thead>
            <tbody>
                <?php $i=1; foreach($expenseData as $item): ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><?=$item['date']?></td>
                    <td><?=$item['category']?></td>
                    <td><?=$item['description']?></td>
                    <td class="amount-column"><?=number_format($item['amount'])?>원</td>
                    <td>
                        <form method="POST">
                            <?php foreach($item as $k=>$v) echo "<input type='hidden' name='$k' value='$v'>";?>
                            <button type="submit" name="update" value="<?=$item['id']?>" class="btn btn-primary">업데이트</button>
                            <button type="submit" name="delete" value="<?=$item['id']?>" class="btn btn-danger">삭제</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach;?>
                <?php if(empty($expenseData)): ?>
                <tr><td colspan="6" class="text-center">이번 달 지출 내역이 없습니다.</td></tr>
                <?php endif;?>
            </tbody>
            <tfoot>
                <tr class="summary-row">
                    <td colspan="2" class="summary-label-expense">월지출 합계</td>
                    <td colspan="4" class="summary-value"><?=number_format($monthlyExpense)?>원</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="2" class="summary-label-expense">년지출 합계</td>
                    <td colspan="4" class="summary-value"><?=number_format($yearExpense)?>원</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="balance-card">
    <div class="balance-title">💰 총잔액</div>
    <div class="balance-amount <?=$balance>=0?'balance-positive':'balance-negative'?>"><?=number_format($balance)?>원</div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
