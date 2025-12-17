<?php
require 'php/auth_check.php'; // ✅ 권한 체크!
require 'php/db-connect-pdo.php';
date_default_timezone_set('Asia/Seoul');



// 기본값
$type = 'income';
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type = $_POST['type'];
    $date = $_POST['date'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    if (!$date || !$category || !$amount) {
        $message = "❌ 모든 필수 항목을 입력해주세요.";
        $message_type = "danger";
    } else {
        try {
            if ($type === 'income') {
                $sql = "INSERT INTO income_table (date, category, description, amount) VALUES (?, ?, ?, ?)";
            } else {
                $sql = "INSERT INTO expense_table (date, category, description, amount) VALUES (?, ?, ?, ?)";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$date, $category, $description, $amount]);

            $message = "✅ 등록되었습니다!";
            $message_type = "success";

            // 입력값 초기화
            $category = '';
            $description = '';
            $amount = '';
        } catch (Exception $e) {
            $message = "❌ 오류 발생: " . $e->getMessage();
            $message_type = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>사용내역서 입력</title>

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
/* ======== 여기부터는 HTML 원본과 동일한 CSS ======== */

/*  수입,지출 버튼색상  */
:root {
  --primary-color: #f44336;
  --secondary-color: #201dfaff;
  --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

body {
  background: var(--bg-gradient);
  min-height: 100vh;
  padding: 20px;
  font-family: 'Noto Sans KR', sans-serif;
}

.container {
  max-width: 600px;
  margin: 0 auto;
}

.card {
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  border: none;
  overflow: hidden;
}

.card-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 25px;
  text-align: center;
  border: none;
}

.card-header h2 {
  margin: 0;
  font-weight: 700;
  font-size: 1.8rem;
}

.card-body {
  padding: 30px;
  background: white;
}

.form-label {
  font-weight: 600;
  color: #333;
  margin-bottom: 8px;
}

.form-control, .form-select {
  border-radius: 10px;
  border: 2px solid #e0e0e0;
  padding: 12px;
  transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-group-custom {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
}

.btn-type {
  flex: 1;
  padding: 15px;
  border-radius: 12px;
  border: 2px solid #e0e0e0;
  background: white;
  cursor: pointer;
  transition: all 0.3s;
  font-weight: 600;
  font-size: 1.1rem;
}

.btn-type.active-income {
  background: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
  transform: scale(1.05);
}

.btn-type.active-expense {
  background: var(--secondary-color);
  color: white;
  border-color: var(--secondary-color);
  transform: scale(1.05);
}

.btn-submit {
  width: 100%;
  padding: 15px;
  border-radius: 12px;
  font-size: 1.2rem;
  font-weight: 700;
  border: none;
  color: white;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  transition: all 0.3s;
  margin-top: 20px;
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}


.btn-nav {
  width: 100%;
  padding: 12px;
  border-radius: 10px;
  border: 2px solid #667eea;
  background: white;
  color: #667eea;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-nav:hover {
  background: #667eea;
  color: white;
}


.alert {
  border-radius: 12px;
  border: none;
  margin-top: 20px;
}


.btn-back {
  padding: 12px 24px;
  border-radius: 12px;
  border: 2px solid #667eea;
  background: white;
  color: #667eea;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.3s;
  display: inline-block;
}

.btn-back:hover {
  background: #667eea;
  color: white;
}


/* 모바일 */
@media (max-width: 576px) {
  .card-body {
    padding: 20px;
  }
  .btn-type {
    font-size: 1rem;
    padding: 12px;
  }
}
</style>

<script>
function selectType(type) {
    document.getElementById("type").value = type;

    const incomeBtn = document.getElementById("incomeBtn");
    const expenseBtn = document.getElementById("expenseBtn");

    incomeBtn.classList.remove("active-income", "active-expense");
    expenseBtn.classList.remove("active-income", "active-expense");

    if (type === "income") {
        incomeBtn.classList.add("active-income");
    } else {
        expenseBtn.classList.add("active-expense");
    }
}
</script>

</head>
<body>
<div class="container mt-3">
  <div class="card">
    <div class="card-header">
      <h2>💰 사용내역서 입력</h2>
    </div>

    <div class="card-body">
      <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
          <?php echo $message; ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="type" id="type" value="<?php echo $type; ?>">

        <div class="btn-group-custom">
          <button type="button" id="incomeBtn"
            class="btn-type <?php echo ($type === 'income' ? 'active-income' : ''); ?>"
            onclick="selectType('income')">📉 수입</button>

          <button type="button" id="expenseBtn"
            class="btn-type <?php echo ($type === 'expense' ? 'active-expense' : ''); ?>"
            onclick="selectType('expense')">📈 지출</button>
        </div>

        <div class="mb-3">
          <label class="form-label">일자</label>
          <input type="date" name="date" class="form-control"
                 value="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">항목</label>
          <input type="text" name="category" class="form-control"
                 placeholder="예: 월회비,회식비,여행비,식사비,찬조금,이월금..."
                 value="<?php echo $category ?? ''; ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">비고</label>
          <input type="text" name="description" class="form-control"
                 placeholder="해당항목의 추가설명을 입력하세요"
                 value="<?php echo $description ?? ''; ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">금액 (원)</label>
          <input type="number" name="amount" class="form-control"
                 placeholder="0"
                 value="<?php echo $amount ?? ''; ?>" required>
        </div>

        <button type="submit" class="btn-submit">등록하기</button>
      </form>



      <!-- 돌아가기 버튼 -->
      <div class="text-center mt-5 mb-3">
        <a href="admin_member_1.php" class="btn-back">← 돌아가기</a>
      </div>

    </div>
  </div>
</div>

</body>
</html>
