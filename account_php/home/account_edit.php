<!-- ✅ 파일그래로의 주소를 입력하면 에러가 나므로 반드시 ?month=숫자(현재의 달)을 같이 입력해야한다. (예) http://localhost/account_input/account_edit.php?month=5  현재5월달이라면 ===>  ?month=5 추가입력!   상단 월단위의 버튼을 눌러줘도 복구된다. -->


<?php require 'php/auth_check.php'; ?>


<!-- ⭐ 관리자 인증 — 로그인 + 레벨10 확인 -->
 <?php
require 'php/auth_check.php';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="J.S.J" />
    <title>사용내역서편집</title>
    <link rel="stylesheet" href="css/account_edit.css" />
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    

    <!-- Bootstrap CSS (5.3.3) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

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
ob_start(); // 출력 버퍼링 시작(오류 메세지 방지차원!)


require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
//include 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-2)  
  


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 접속 오류: " . $e->getMessage());
}

// 현재 연도와 월 설정
$currentYear = date('Y');
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// 수정 버튼 클릭 처리
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
        ob_end_clean(); // 이전 출력 버퍼 지우기
        ?> 
        <div class="edit-form-container">
            <form method="POST" action="" class="edit-form">
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
                    <button type="submit" name="update" class="btn btn-primary">업데이트</button>
                    <button type="button" class="btn btn-secondary" onclick="location.href='account_edit.php?month=<?php echo $currentMonth; ?>'">취소</button>
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

    if ($type === '수입') {
        $table = 'income_table';
    } elseif ($type === '지출') {
        $table = 'expense_table';
    }

    $stmt = $pdo->prepare("UPDATE $table SET date = ?, category = ?, description = ?, amount = ? WHERE id = ?");
    $stmt->execute([$date, $category, $description, $amount, $id]);

    ob_end_clean();
    header("Location: account_edit.php?month=$currentMonth");
    exit;
}

// 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $type = $_POST['type'];

    if ($type === '수입') {
        $table = 'income_table';
    } else if ($type === '지출') {
        $table = 'expense_table';
    }

    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->execute([$id]);

    ob_end_clean();
    header("Location: account_edit.php?month=$currentMonth");
    exit;
}

// 데이터 조회
$stmt = $pdo->prepare("SELECT date, amount FROM income_table WHERE YEAR(date) = ? AND MONTH(date) <= ? ORDER BY date DESC");
$stmt->execute([$currentYear, $currentMonth]);
$incomeTransactionsTotal = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT date, amount FROM expense_table WHERE YEAR(date) = ? AND MONTH(date) <= ? ORDER BY date DESC");
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

$yearIncomeTotal = 0;
$yearExpenseTotal = 0;
for ($i = 1; $i <= $currentMonth; $i++) {
    $yearIncomeTotal += $monthlyIncomeTotals[$i];
    $yearExpenseTotal += $monthlyExpenseTotals[$i];
}

$balance = $yearIncomeTotal - $yearExpenseTotal;

$stmt = $pdo->prepare("SELECT * FROM income_table WHERE MONTH(date) = ? AND YEAR(date) = ? ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$incomeTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM expense_table WHERE MONTH(date) = ? AND YEAR(date) = ? ORDER BY date ASC");
$stmt->execute([$currentMonth, $currentYear]);
$expenseTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


  
    <div class="container">
        <section>
            <?php
            $today = date('Y/m/d H:i');
            echo "<div style='margin-top: 30px'>오늘의 날짜: $today</div>";

            $months = range(1, 12);
            echo "<div style='margin-top: 20px'>";
            foreach ($months as $month) {
                $activeClass = ($month == $currentMonth) ? 'active' : '';
                echo "<a class='btn $activeClass' href='?month=$month'>$month 월</a>";
            }
            echo "</div>";
            echo "<div class='notice'> [알림] 수정/삭제 후 페이지가 새로고침됩니다! </div>";
            ?>

            <!-- 수입 목록 -->
            <h3 style="width: 100%; text-align: center; color:darkgrey; margin-top: 30px">[수입 목록]</h3>
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
                    <?php 
                    $incomeCounter = 1;
                    foreach ($incomeTransactions as $transaction): ?>
                        <tr>
                            <td><?php echo $incomeCounter++; ?></td>
                            <td><?php echo $transaction['date']; ?></td>
                            <td><?php echo $transaction['category']; ?></td>
                            <td><?php echo $transaction['description']; ?></td>
                            <td class="amount-column"><?php echo number_format($transaction['amount']); ?>원</td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">
                                    <input type="hidden" name="date" value="<?php echo $transaction['date']; ?>">
                                    <input type="hidden" name="type" value="수입">
                                    <input type="hidden" name="category" value="<?php echo $transaction['category']; ?>">
                                    <input type="hidden" name="description" value="<?php echo $transaction['description']; ?>">
                                    <input type="hidden" name="amount" value="<?php echo $transaction['amount']; ?>">
                                    <div class="btn_edit_delete">
                                        <button type="submit" name="edit" value="<?php echo $transaction['id']; ?>" class="btn btn-primary">수정</button>
                                        <button type="submit" name="delete" value="<?php echo $transaction['id']; ?>" class="btn btn-danger">삭제</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="summary-label-income">월수입 합계:</td>
                        <td colspan="2" class="summary-value"><?php echo number_format($selectedMonthIncomeTotal); ?>원</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="summary-label-income">년수입 합계:</td>
                        <td colspan="2" class="summary-value"><?php echo number_format($yearIncomeTotal); ?>원</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

            <!-- 지출 목록 -->
            <h3 style="width: 100%; text-align: center; color:darkgrey; margin-top: 30px">[지출 목록]</h3>
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
                    <?php 
                    $expenseCounter = 1;
                    foreach ($expenseTransactions as $transaction): ?>
                        <tr>
                            <td><?php echo $expenseCounter++; ?></td>
                            <td><?php echo $transaction['date']; ?></td>
                            <td><?php echo $transaction['category']; ?></td>
                            <td><?php echo $transaction['description']; ?></td>
                            <td class="amount-column"><?php echo number_format($transaction['amount']); ?>원</td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">
                                    <input type="hidden" name="date" value="<?php echo $transaction['date']; ?>">
                                    <input type="hidden" name="type" value="지출">
                                    <input type="hidden" name="category" value="<?php echo $transaction['category']; ?>">
                                    <input type="hidden" name="description" value="<?php echo $transaction['description']; ?>">
                                    <input type="hidden" name="amount" value="<?php echo $transaction['amount']; ?>">
                                    <div class="btn_edit_delete">
                                        <button type="submit" name="edit" value="<?php echo $transaction['id']; ?>" class="btn btn-primary">수정</button>
                                        <button type="submit" name="delete" value="<?php echo $transaction['id']; ?>" class="btn btn-danger">삭제</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="summary-label-expense">월지출 합계:</td>
                        <td colspan="2" class="summary-value"><?php echo number_format($selectedMonthExpenseTotal); ?>원</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="summary-label-expense">년지출 합계:</td>
                        <td colspan="2" class="summary-value"><?php echo number_format($yearExpenseTotal); ?>원</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

            <!-- 총잔액 -->
            <div class="jikji_footer">
                <h3 class="total_balance">총잔액:<span> <?php echo number_format($balance); ?>원</span></h3>
                <a class="account_image" href="./images_upload.php"><button type="button" class="btn btn-success">영수증 입력하기</button></a>
                <button id="copyButton">회원열람용 사이트복사</button>
            </div>

            <script>
                function copyAddress() {
                    var address = "http://jikji35.dothome.co.kr/home/account_view.php?month=해당월을 숫자로입력 하시오";
                    var tempTextArea = document.createElement("textarea");
                    tempTextArea.value = address;
                    document.body.appendChild(tempTextArea);
                    tempTextArea.select();
                    document.execCommand("copy");
                    document.body.removeChild(tempTextArea);
                    alert("주소가 복사되었습니다.");
                }
                var copyButton = document.getElementById("copyButton");
                copyButton.addEventListener("click", copyAddress);
            </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
           
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
ob_end_flush(); // 출력 버퍼링 종료 및 출력
?>