<?php
require 'php/auth_check.php';
ob_start();
require 'php/db-connect.php';

// PDO 객체 생성
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 접속 오류: " . $e->getMessage());
}

// 현재 연도와 월 설정
$currentYear = date('Y');
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $editID = $_POST['edit'];
    $id = $_POST['id'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    $table = ($type === '수입') ? 'income_table' : 'expense_table';

    if ($editID === $id) {
        ob_end_clean();
        ?>
        <div class="edit-form-container container-card">
            <form method="POST" class="edit-form">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="type" value="<?php echo $type; ?>">

                <div class="form-group">
                    <label for="edit-date">일자:</label>
                    <input type="date" class="form-control" id="edit-date" name="date" value="<?php echo $date; ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-category">항목:</label>
                    <input type="text" class="form-control" id="edit-category" name="category" value="<?php echo $category; ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-description">비고:</label>
                    <input type="text" class="form-control" id="edit-description" name="description" value="<?php echo $description; ?>">
                </div>
                <div class="form-group">
                    <label for="edit-amount">금액:</label>
                    <input type="number" class="form-control" id="edit-amount" name="amount" value="<?php echo $amount; ?>" required>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" name="update" class="btn btn-success btn-submit">업데이트</button>
                    <button type="button" class="btn btn-secondary btn-submit" onclick="location.href='account_edit.php?month=<?php echo $currentMonth; ?>'">취소</button>
                </div>
            </form>
        </div>
        <?php
        exit;
    }
}

// 업데이트 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    $table = ($type === '수입') ? 'income_table' : 'expense_table';

    $stmt = $pdo->prepare("UPDATE $table SET date=?, category=?, description=?, amount=? WHERE id=?");
    $stmt->execute([$date, $category, $description, $amount, $id]);

    ob_end_clean();
    header("Location: account_edit.php?month=$currentMonth");
    exit;
}

// 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $type = $_POST['type'];

    $table = ($type === '수입') ? 'income_table' : 'expense_table';
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id=?");
    $stmt->execute([$id]);

    ob_end_clean();
    header("Location: account_edit.php?month=$currentMonth");
    exit;
}

// 데이터 조회
$stmt = $pdo->prepare("SELECT date, amount FROM income_table WHERE YEAR(date)=? AND MONTH(date)<=? ORDER BY date DESC");
$stmt->execute([$currentYear, $currentMonth]);
$incomeTransactionsTotal = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT date, amount FROM expense_table WHERE YEAR(date)=? AND MONTH(date)<=? ORDER BY date DESC");
$stmt->execute([$currentYear, $currentMonth]);
$expenseTransactionsTotal = $stmt->fetchAll(PDO::FETCH_ASSOC);

$monthlyIncomeTotals = array_fill(1, 12, 0);
$monthlyExpenseTotals = array_fill(1, 12, 0);

foreach ($incomeTransactionsTotal as $transaction) {
    $month = (int)date('n', strtotime($transaction['date']));
    $monthlyIncomeTotals[$month] += $transaction['amount'];
}

foreach ($expenseTransactionsTotal as $transaction) {
    $month = (int)date('n', strtotime($transaction['date']));
    $monthlyExpenseTotals[$month] += $transaction['amount'];
}

$selectedMonthIncomeTotal = $monthlyIncomeTotals[$currentMonth];
$selectedMonthExpenseTotal = $monthlyExpenseTotals[$currentMonth];

$yearIncomeTotal = array_sum(array_slice($monthlyIncomeTotals, 1, $currentMonth));
$yearExpenseTotal = array_sum(array_slice($monthlyExpenseTotals, 1, $currentMonth));
$balance = $yearIncomeTotal - $yearExpenseTotal;

$stmt = $pdo->prepare("SELECT * FROM income_table WHERE MONTH(date)=? AND YEAR(date)=? ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$incomeTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM expense_table WHERE MONTH(date)=? AND YEAR(date)=? ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$expenseTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>사용내역서 편집</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    font-family: 'Noto Sans KR', sans-serif;
    min-height: 100vh;
}
.container-card {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px 30px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
h1,h3 {text-align:center; color:#343a40;}
.table th, .table td {vertical-align: middle;}
.amount-column {text-align:right;}
.btn-edit-delete button {margin-right:5px;}
.jikji_footer {margin-top:30px; text-align:center;}
.jikji_footer .btn {margin:5px;}
.btn.active {background-color:#28a745; color:#fff;}
.notice {color:#6c757d; text-align:center; margin-top:10px; margin-bottom:15px;}
.summary-label-income, .summary-label-expense {text-align:right; font-weight:bold;}
.summary-value {text-align:left;}
</style>
</head>
<body>

<div class="container-card">
  <?php
  $today = date('Y/m/d H:i');
  echo "<div style='margin-bottom: 20px; text-align:right;'>오늘의 날짜: $today</div>";

  echo "<div style='margin-bottom: 15px; text-align:center;'>";
  foreach (range(1,12) as $month) {
      $activeClass = ($month == $currentMonth) ? 'active btn btn-sm' : 'btn btn-outline-secondary btn-sm';
      echo "<a class='$activeClass mx-1' href='?month=$month'>$month 월</a>";
  }
  echo "</div>";
  echo "<div class='notice'>[알림] 수정/삭제 후 페이지가 새로고침됩니다!</div>";
  ?>

  <!-- 수입 목록 -->
  <h3>[수입 목록]</h3>
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
      <?php $counter=1; foreach($incomeTransactions as $t): ?>
        <tr>
          <td><?= $counter++; ?></td>
          <td><?= $t['date']; ?></td>
          <td><?= $t['category']; ?></td>
          <td><?= $t['description']; ?></td>
          <td class="amount-column"><?= number_format($t['amount']); ?>원</td>
          <td class="btn-edit-delete">
            <form method="POST">
              <input type="hidden" name="id" value="<?= $t['id']; ?>">
              <input type="hidden" name="date" value="<?= $t['date']; ?>">
              <input type="hidden" name="type" value="수입">
              <input type="hidden" name="category" value="<?= $t['category']; ?>">
              <input type="hidden" name="description" value="<?= $t['description']; ?>">
              <input type="hidden" name="amount" value="<?= $t['amount']; ?>">
              <button type="submit" name="edit" value="<?= $t['id']; ?>" class="btn btn-primary btn-sm">수정</button>
              <button type="submit" name="delete" value="<?= $t['id']; ?>" class="btn btn-danger btn-sm">삭제</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2" class="summary-label-income">월수입 합계:</td>
        <td colspan="2" class="summary-value"><?= number_format($selectedMonthIncomeTotal); ?>원</td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td colspan="2" class="summary-label-income">년수입 합계:</td>
        <td colspan="2" class="summary-value"><?= number_format($yearIncomeTotal); ?>원</td>
        <td colspan="2"></td>
      </tr>
    </tfoot>
  </table>

  <!-- 지출 목록 -->
  <h3>[지출 목록]</h3>
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
      <?php $counter=1; foreach($expenseTransactions as $t): ?>
        <tr>
          <td><?= $counter++; ?></td>
          <td><?= $t['date']; ?></td>
          <td><?= $t['category']; ?></td>
          <td><?= $t['description']; ?></td>
          <td class="amount-column"><?= number_format($t['amount']); ?>원</td>
          <td class="btn-edit-delete">
            <form method="POST">
              <input type="hidden" name="id" value="<?= $t['id']; ?>">
              <input type="hidden" name="date" value="<?= $t['date']; ?>">
              <input type="hidden" name="type" value="지출">
              <input type="hidden" name="category" value="<?= $t['category']; ?>">
              <input type="hidden" name="description" value="<?= $t['description']; ?>">
              <input type="hidden" name="amount" value="<?= $t['amount']; ?>">
              <button type="submit" name="edit" value="<?= $t['id']; ?>" class="btn btn-primary btn-sm">수정</button>
              <button type="submit" name="delete" value="<?= $t['id']; ?>" class="btn btn-danger btn-sm">삭제</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2" class="summary-label-expense">월지출 합계:</td>
        <td colspan="2" class="summary-value"><?= number_format($selectedMonthExpenseTotal); ?>원</td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td colspan="2" class="summary-label-expense">년지출 합계:</td>
        <td colspan="2" class="summary-value"><?= number_format($yearExpenseTotal); ?>원</td>
        <td colspan="2"></td>
      </tr>
    </tfoot>
  </table>

  <!-- 총잔액 -->
  <div class="jikji_footer">
    <h3 class="total_balance">총잔액: <span><?= number_format($balance); ?>원</span></h3>
    <a class="account_image" href="./images_upload.php"><button type="button" class="btn btn-success">영수증 입력하기</button></a>
    <button id="copyButton" class="btn btn-outline-secondary">회원열람용 사이트복사</button>
  </div>
</div>

<script>
function copyAddress() {
    const address = "http://jikji35.dothome.co.kr/home/account_view.php?month=해당월을 숫자로입력";
    navigator.clipboard.writeText(address).then(() => alert("주소가 복사되었습니다."));
}
document.getElementById('copyButton').addEventListener('click', copyAddress);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>
