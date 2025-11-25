<?php
// account_view.php
// Firebase 디자인을 유지하면서 PHP+MySQL(PDO)로 동작하도록 구현한 파일입니다.
// 사용전: php/db-connect.php 파일이 존재하고 올바른 DB 접속 정보가 있어야 합니다.

require __DIR__ . '/php/db-connect.php';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // 운영 환경에서는 에러 내용을 화면에 그대로 출력하지 마세요.
    echo "<h2>DB 연결 실패</h2>";
    exit;
}

// 현재 연도 및 선택 월 처리
$currentYear = (int)date('Y');
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
if ($currentMonth < 1 || $currentMonth > 12) {
    $currentMonth = (int)date('n');
}

// 1) 선택한 월까지의 월별 합계(년 합계 계산용)
$stmt = $pdo->prepare("SELECT date, amount FROM income_table WHERE YEAR(`date`) = ? AND MONTH(`date`) <= ?");
$stmt->execute([$currentYear, $currentMonth]);
$incomeAll = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT date, amount FROM expense_table WHERE YEAR(`date`) = ? AND MONTH(`date`) <= ?");
$stmt->execute([$currentYear, $currentMonth]);
$expenseAll = $stmt->fetchAll();

$monthlyIncomeTotals = array_fill(1, 12, 0);
$monthlyExpenseTotals = array_fill(1, 12, 0);

foreach ($incomeAll as $r) {
    $m = (int)date('n', strtotime($r['date']));
    $monthlyIncomeTotals[$m] += (float)$r['amount'];
}
foreach ($expenseAll as $r) {
    $m = (int)date('n', strtotime($r['date']));
    $monthlyExpenseTotals[$m] += (float)$r['amount'];
}

$selectedMonthIncomeTotal = $monthlyIncomeTotals[$currentMonth] ?? 0;
$selectedMonthExpenseTotal = $monthlyExpenseTotals[$currentMonth] ?? 0;

$yearIncomeTotal = 0;
$yearExpenseTotal = 0;
for ($i = 1; $i <= $currentMonth; $i++) {
    $yearIncomeTotal += $monthlyIncomeTotals[$i];
    $yearExpenseTotal += $monthlyExpenseTotals[$i];
}

$balance = $yearIncomeTotal - $yearExpenseTotal;

// 2) 선택한 월의 상세 레코드 (표시용)
$stmt = $pdo->prepare("SELECT * FROM income_table WHERE YEAR(`date`) = ? AND MONTH(`date`) = ? ORDER BY `date` ASC, `id` ASC");
$stmt->execute([$currentYear, $currentMonth]);
$incomeTransactions = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM expense_table WHERE YEAR(`date`) = ? AND MONTH(`date`) = ? ORDER BY `date` ASC, `id` ASC");
$stmt->execute([$currentYear, $currentMonth]);
$expenseTransactions = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>사용내역서 보기</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --income-color: #4CAF50;
      --expense-color: #f44336;
    }
    body { background: var(--primary-gradient); min-height:100vh; padding:20px; font-family:'Noto Sans KR', sans-serif; }
    .container { max-width:1200px; margin:0 auto; }
    .header-card { border-radius:20px; box-shadow:0 10px 40px rgba(0,0,0,0.2); background:white; padding:25px; margin-bottom:20px; }
    .date-display { text-align:center; color:#667eea; font-size:1.1rem; font-weight:600; margin-bottom:20px; }
    .month-buttons { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-bottom:15px; }
    .btn-month { padding:10px 20px; border-radius:10px; border:2px solid #e0e0e0; background:white; color:#666; font-weight:600; cursor:pointer; transition:all .3s; text-decoration:none; }
    .btn-month:hover { border-color:#667eea; color:#667eea; }
    .btn-month.active { background: var(--primary-gradient); color:white; border-color:transparent; transform:scale(1.05); }
    .alert-info { background: linear-gradient(135deg,#e3f2fd 0%,#bbdefb 100%); border:none; border-radius:12px; color:#1976d2; }
    .section-card { background:white; border-radius:20px; box-shadow:0 10px 40px rgba(0,0,0,0.2); padding:25px; margin-bottom:30px; }
    .section-title { font-size:1.8rem; font-weight:700; text-align:center; margin-bottom:25px; color:#333; }
    .income-title { color:var(--income-color); } .expense-title { color:var(--expense-color); }
    .table-wrapper { overflow-x:auto; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
    .custom-table { margin:0; background:white; border-radius:12px; overflow:hidden; width:100%; }
    .custom-table thead { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; }
    .custom-table thead th { padding:15px; font-weight:700; border:none; text-align:center; }
    .custom-table tbody td { padding:12px; vertical-align:middle; border-bottom:1px solid #f0f0f0; }
    .custom-table tbody tr:last-child td { border-bottom:none; }
    .custom-table tbody tr:hover { background:#f8f9ff; }
    .amount-column { text-align:right; font-weight:700; color:#333; }
    .summary-row { background: linear-gradient(135deg,#f8f9ff 0%,#e8eaf6 100%); font-weight:700; }
    .summary-label-income { background: linear-gradient(135deg,#e8f5e9 0%,#c8e6c9 100%); color:var(--income-color); font-weight:700; text-align:center; }
    .summary-label-expense { background: linear-gradient(135deg,#ffebee 0%,#ffcdd2 100%); color:var(--expense-color); font-weight:700; text-align:center; }
    .summary-value { font-size:1.2rem; font-weight:700; color:#667eea; text-align:center; }
    .balance-card { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); border-radius:20px; padding:30px; color:white; text-align:center; box-shadow:0 10px 40px rgba(0,0,0,0.3); margin-bottom:20px; }
    .balance-title { font-size:1.5rem; font-weight:700; margin-bottom:15px; opacity:0.9; }
    .balance-amount { font-size:2.5rem; font-weight:900; margin:0; }
    .balance-positive { color:#4CAF50; text-shadow:0 2px 10px rgba(76,175,80,0.3); }
    .balance-negative { color:#f44336; text-shadow:0 2px 10px rgba(244,67,54,0.3); }
    .nav-buttons { display:flex; gap:10px; }
    .btn-nav { flex:1; padding:15px; border-radius:12px; border:none; background:white; color:#667eea; font-weight:700; text-decoration:none; text-align:center; transition:all .3s; border:2px solid white; }
    .btn-nav:hover { background:transparent; color:white; border-color:white; }
    .loading { text-align:center; padding:40px; }
    .empty-state { text-align:center; padding:40px; color:#999; }
    @media (max-width:768px) {
      .month-buttons { gap:5px; }
      .btn-month { padding:8px 15px; font-size:0.9rem; }
      .balance-amount { font-size:1.8rem; }
      .section-title { font-size:1.4rem; }
      .custom-table { font-size:0.85rem; }
      .custom-table thead th, .custom-table tbody td { padding:8px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header-card">
      <div class="date-display" id="dateDisplay">
        <?php echo "📅 오늘의 날짜: " . date('Y/m/d H:i'); ?>
      </div>

      <div class="month-buttons" id="month-buttons">
        <?php
          for ($m = 1; $m <= 12; $m++) {
              $active = ($m === $currentMonth) ? 'btn-month active' : 'btn-month';
              echo "<a href=\"?month={$m}\" class=\"{$active}\" data-month=\"{$m}\">{$m}월</a>";
          }
        ?>
      </div>

      <div class="alert alert-info text-center" role="alert">
        💡 합계가 제대로 보이지 않으면 월 버튼을 눌러주세요!
      </div>
    </div>

    <!-- 수입 테이블 -->
    <div class="section-card">
      <h3 class="section-title income-title">📈 수입 목록</h3>
      <div class="table-wrapper">
        <table class="table custom-table">
          <thead>
            <tr>
              <th style="width:8%">No</th>
              <th style="width:15%">일자</th>
              <th style="width:35%">항목</th>
              <th style="width:27%">비고</th>
              <th style="width:15%">금액</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($incomeTransactions)): ?>
              <tr>
                <td colspan="5" class="empty-state">
                  <p>이번 달 수입 내역이 없습니다.</p>
                </td>
              </tr>
            <?php else: 
              $c = 1;
              foreach ($incomeTransactions as $t): ?>
                <tr>
                  <td style="text-align:center;"><?php echo $c++; ?></td>
                  <td style="text-align:center;"><?php echo htmlspecialchars($t['date']); ?></td>
                  <td><?php echo htmlspecialchars($t['category']); ?></td>
                  <td><?php echo htmlspecialchars($t['description']); ?></td>
                  <td class="amount-column"><?php echo number_format((int)$t['amount']); ?>원</td>
                </tr>
            <?php endforeach; endif; ?>
          </tbody>
          <tfoot>
            <tr class="summary-row">
              <td colspan="2" class="summary-label-income">월수입 합계</td>
              <td colspan="3" class="summary-value"><?php echo number_format($selectedMonthIncomeTotal); ?>원</td>
            </tr>
            <tr class="summary-row">
              <td colspan="2" class="summary-label-income">년수입 합계</td>
              <td colspan="3" class="summary-value"><?php echo number_format($yearIncomeTotal); ?>원</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- 지출 테이블 -->
    <div class="section-card">
      <h3 class="section-title expense-title">📉 지출 목록</h3>
      <div class="table-wrapper">
        <table class="table custom-table">
          <thead>
            <tr>
              <th style="width:8%">No</th>
              <th style="width:15%">일자</th>
              <th style="width:35%">항목</th>
              <th style="width:27%">비고</th>
              <th style="width:15%">금액</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($expenseTransactions)): ?>
              <tr>
                <td colspan="5" class="empty-state">
                  <p>이번 달 지출 내역이 없습니다.</p>
                </td>
              </tr>
            <?php else:
              $c2 = 1;
              foreach ($expenseTransactions as $t): ?>
                <tr>
                  <td style="text-align:center;"><?php echo $c2++; ?></td>
                  <td style="text-align:center;"><?php echo htmlspecialchars($t['date']); ?></td>
                  <td><?php echo htmlspecialchars($t['category']); ?></td>
                  <td><?php echo htmlspecialchars($t['description']); ?></td>
                  <td class="amount-column"><?php echo number_format((int)$t['amount']); ?>원</td>
                </tr>
            <?php endforeach; endif; ?>
          </tbody>
          <tfoot>
            <tr class="summary-row">
              <td colspan="2" class="summary-label-expense">월지출 합계</td>
              <td colspan="3" class="summary-value"><?php echo number_format($selectedMonthExpenseTotal); ?>원</td>
            </tr>
            <tr class="summary-row">
              <td colspan="2" class="summary-label-expense">년지출 합계</td>
              <td colspan="3" class="summary-value"><?php echo number_format($yearExpenseTotal); ?>원</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- 총잔액 -->
    <div class="balance-card">
      <h3 class="balance-title">💰 총잔액</h3>
      <h2 id="totalBalance" class="balance-amount <?php echo ($balance >= 0) ? 'balance-positive' : 'balance-negative'; ?>">
        <?php echo number_format($balance); ?>원
      </h2>

      <div class="nav-buttons mt-4">
        <a href="account_input.php" class="btn-nav">➕ 입력하기</a>
        <a href="account_edit.php" class="btn-nav">✏️ 편집하기</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // 월 버튼 클릭 시 히스토리 대체 후 서버 리로드 (뒤로가기 기록을 남기지 않음)
    document.querySelectorAll('#month-buttons a').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const month = this.getAttribute('data-month');
        if (!month) return;
        window.history.replaceState({}, document.title, `?month=${month}`);
        // 서버에서 렌더링된 페이지로 이동
        window.location.href = `?month=${month}`;
      });
    });
  </script>
</body>
</html>
