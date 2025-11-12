<!-- ✅ 페이지 그대로의 주소를 입력하면 에러가 나므로 반드시 ?month=숫자(현재의 달)을 같이 입력해야한다. (예)http://localhost/home/account_view.php?month=5  현재5월달이라면 ===>  ?month=5 추가입력!   상단 월단위의 버튼을 눌러줘도 복구된다. -->

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="J.S.J" />
  <title>사용내역서보기</title>
  <link rel="stylesheet" href="css/account_view.css" />
  <link rel="manifest" href="manifest.json">
  <meta name="msapplication-config" content="/browserconfig.xml">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  <!-- Bootstrap CSS (5.3.3) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="/favicon.ico?v=2" />

  <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />

  <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">
  

</head>

<body>

<?php
  
  
require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
//include 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-2)  
  


$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

// 오늘의 날짜 출력
$today = date('Y/m/d H:i');
echo "<div style='margin-top: 30px'>오늘의 날짜: $today</div>";
  

// 월 선택 버튼 생성
// <div>에 id="month-buttons" 추가.(히스토리 차단목적;되돌아가기 없앰)
// <a> 태그에 data-month='$month' 추가.(히스토리 차단목적;되돌아가기 없앰)
  
$months = range(1, 12);
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');
echo "<div style='margin-top: 20px' id='month-buttons'>";
foreach ($months as $month) {
  $activeClass = ($month == $currentMonth) ? 'active' : '';
  echo "<a class='btn $activeClass' href='?month=$month' data-month='$month'>$month 월</a>";
}
echo "</div>";
echo "<div class='notice'> [알림] 현재 합계부분이 제대로 보이지않으면 위 월단위의 버튼을 누르세요! </div>";

// 현재 연도 가져오기
$currentYear = date('Y');

// 선택한 월까지의 수입/지출 데이터 조회
$stmt = $pdo->prepare("SELECT date, amount FROM income_table WHERE YEAR(date) = ? AND MONTH(date) <= ? ORDER BY date DESC");
$stmt->execute([$currentYear, $currentMonth]);
$incomeTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT date, amount FROM expense_table WHERE YEAR(date) = ? AND MONTH(date) <= ? ORDER BY date DESC");
$stmt->execute([$currentYear, $currentMonth]);
$expenseTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 월별 합계 계산
$monthlyIncomeTotals = array_fill(1, 12, 0);
$monthlyExpenseTotals = array_fill(1, 12, 0);

foreach ($incomeTransactions as $transaction) {
  $month = (int)date('n', strtotime($transaction['date']));
  $monthlyIncomeTotals[$month] += $transaction['amount'];
}

foreach ($expenseTransactions as $transaction) {
  $month = (int)date('n', strtotime($transaction['date']));
  $monthlyExpenseTotals[$month] += $transaction['amount'];
}

// 선택한 월의 월수입/월지출 합계
$selectedMonthIncomeTotal = $monthlyIncomeTotals[$currentMonth];
$selectedMonthExpenseTotal = $monthlyExpenseTotals[$currentMonth];

// 년수입/년지출 합계
$yearIncomeTotal = 0;
$yearExpenseTotal = 0;
for ($i = 1; $i <= $currentMonth; $i++) {
  $yearIncomeTotal += $monthlyIncomeTotals[$i];
  $yearExpenseTotal += $monthlyExpenseTotals[$i];
}

// 총잔액 계산
$balance = $yearIncomeTotal - $yearExpenseTotal;

// 표시용 데이터 (선택한 달, 일자 오름차순 정렬)
$stmt = $pdo->prepare("SELECT * FROM income_table WHERE MONTH(date) = ? AND YEAR(date) = ? ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$incomeTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM expense_table WHERE MONTH(date) = ? AND YEAR(date) = ? ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$expenseTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 수정/삭제 처리 (기존 유지)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
  $editID = $_POST['edit'];
  $id = $_POST['id'];
  $date = $_POST['date'];
  $type = $_POST['type'];
  $category = $_POST['category'];
  $description = $_POST['description'];
  $amount = $_POST['amount'];

  if ($type === '수입') {
    $table = 'income_table';
  } elseif ($type === '지출') {
    $table = 'expense_table';
  }

  if ($editID === $id) {
    ?>
    <form method="POST" action="account_book.php">
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
        <input type="text" class="form-control" id="edit-description" name="description" value="<?php echo $description; ?>" required>
      </div>
      <div class="form-group">
        <label for="edit-amount">금액:</label>
        <input type="number" class="form-control" id="edit-amount" name="amount" value="<?php echo $amount; ?>" required>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary" name="save">저장</button>
        <button type="button" class="btn btn-secondary" onclick="location.href='account_edit.php'">취소</button>
      </div>
    </form>
    <?php
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
  $deleteID = $_POST['delete'];
  $id = $_POST['id'];
  $type = $_POST['type'];

  if ($type === '수입') {
    $table = 'income_table';
  } else if ($type === '지출') {
    $table = 'expense_table';
  }

  $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
  $stmt->execute([$id]);

  header("Location: account_edit.php");
  exit;
}
?>

<!-- 수입 목록 -->
<h3 style="width: 100%; text-align: center; color:darkgrey; margin-top: 30px">[수입 목록]</h3>
<table class="table table-bordered">
  <thead>
    <tr>
      <th style="width: 5%; text-align: center;">no</th>
      <th style="width: 10%; text-align: center;">일자</th>
      <th style="width: 45%; text-align: center;">항목</th>
      <th style="width: 30%; text-align: center;">비고</th>
      <th style="width: 10%; text-align: center;">금액</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $incomeCounter = 1;
    foreach ($incomeTransactions as $transaction): ?>
      <tr>
        <td style="text-align: center;"><?php echo $incomeCounter++; ?></td>
        <td style="text-align: center;"><?php echo $transaction['date']; ?></td>
        <td><?php echo $transaction['category']; ?></td>
        <td><?php echo $transaction['description']; ?></td>
        <td class="amount-column"><?php echo number_format($transaction['amount']); ?>원</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2" class="summary-label-income">월수입 합계:</td>
      <td colspan="2" class="summary-value"><?php echo number_format($selectedMonthIncomeTotal); ?>원</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="2" class="summary-label-income">년수입 합계:</td>
      <td colspan="2" class="summary-value"><?php echo number_format($yearIncomeTotal); ?>원</td>
      <td></td>
    </tr>
  </tfoot>
</table>

<!-- 지출 목록 -->
<h3 style="width: 100%; text-align: center; color:darkgrey; margin-top: 30px">[지출 목록]</h3>
<table class="table table-bordered">
  <thead>
    <tr>
      <th style="width: 5%; text-align: center;">no</th>
      <th style="width: 10%; text-align: center;">일자</th>
      <th style="width: 45%; text-align: center;">항목</th>
      <th style="width: 30%; text-align: center;">비고</th>
      <th style="width: 10%; text-align: center;">금액</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $expenseCounter = 1;
    foreach ($expenseTransactions as $transaction): ?>
      <tr>
        <td style="text-align: center;"><?php echo $expenseCounter++; ?></td>
        <td style="text-align: center;"><?php echo $transaction['date']; ?></td>
        <td><?php echo $transaction['category']; ?></td>
        <td><?php echo $transaction['description']; ?></td>
        <td class="amount-column"><?php echo number_format($transaction['amount']); ?>원</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2" class="summary-label-expense">월지출 합계:</td>
      <td colspan="2" class="summary-value"><?php echo number_format($selectedMonthExpenseTotal); ?>원</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="2" class="summary-label-expense">년지출 합계:</td>
      <td colspan="2" class="summary-value"><?php echo number_format($yearExpenseTotal); ?>원</td>
      <td></td>
    </tr>
  </tfoot>
</table>

<!-- 총잔액 -->
<div>
  <h3 class="total_balance">총잔액:<span> <?php echo number_format($balance); ?>원</span></h3>
  <a class="account_image" href="./images_view.php">
    <button type="button" class="btn btn-success">영수증 사진보기</button>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


<!-- ✅ 여러 "월"을 페이지보기를 했다가 완전히 사이트를 닫고자할때 거쳐온 달들을 다시 돌아가는것을 생략하고 빨리 빠져 나가고자할때 이 스크립트를 활용! 138,141라인도 일부 코드를 추가함.
JavaScript로 클릭 이벤트를 가로채고, history.replaceState()로 히스토리를 대체   -->
<script>
  document.querySelectorAll('#month-buttons a').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault(); // 기본 링크 동작 차단
      const month = this.getAttribute('data-month');
      // URL을 변경하되 히스토리를 대체
      window.history.replaceState({}, document.title, `?month=${month}`);
      // 페이지 새로고침
      window.location.href = `?month=${month}`;
    });
  });
</script>


<!-- 채널톡 상담 소스 -->
<script>
  (function(){var w=window;if(w.ChannelIO){return w.console.error("ChannelIO script included twice.");}var ch=function(){ch.c(arguments);};ch.q=[];ch.c=function(args){ch.q.push(args);};w.ChannelIO=ch;function l(){if(w.ChannelIOInitialized){return;}w.ChannelIOInitialized=true;var s=document.createElement("script");s.type="text/javascript";s.async=true;s.src="https://cdn.channel.io/plugin/ch-plugin-web.js";var x=document.getElementsByTagName("script")[0];if(x.parentNode){x.parentNode.insertBefore(s,x);}}if(document.readyState==="complete"){l();}else{w.addEventListener("DOMContentLoaded",l);w.addEventListener("load",l);}})();
  
  ChannelIO('boot', {
    "pluginKey": "8a8da17f-40dc-4f7b-87c4-718e23824191"
  });
</script>
</body>
</html>