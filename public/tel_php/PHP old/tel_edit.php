<?php
session_start();
require './php/auth_check.php';
require './php/db-connect-pdo.php';

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>회원편집</title>
  <link rel="manifest" href="manifest.json">
  <meta name="msapplication-config" content="/browserconfig.xml">
    
  <!-- 파비콘 아이콘들 -->
  <link rel="icon" href="/favicon.png?v=2" />
  <link rel="icon" type="image/png" sizes="36x36" href="./favicons/2/android-icon-36x36.png" />
  <link rel="icon" type="image/png" sizes="48x48" href="./favicons/2/android-icon-48x48.png" />
  <link rel="icon" type="image/png" sizes="72x72" href="./favicons/2/android-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="32x32" href="./favicons/2/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="./favicons/2/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="./favicons/2/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="./favicons/2/apple-icon-72x72.png">

  <!-- 부트스트랩 5.3.3  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .section-title {
    text-align:center; 
    color:#007bff; 
    font-weight:700; 
    margin-bottom:30px; 
    padding:10px; 
    background:#e9f3ff; 
    border-radius:10px;
    border:1px solid #c9e3ff;
    }

  </style>

</head>

<body>
<div class="container mt-4 mb-2">
  <h3 class="section-title mb-4">📋 회원편집 / 삭제</h3>

  <form action="tel_update.php" method="post">
    <table class="table table-bordered table-hover text-center align-middle">
      <thead class="table-light">
        <tr>
          <th>선택</th>
          <!-- <th>아이디</th> -->
          <th>이름</th>
          <th>전화번호</th>
          <th>주소</th>
          <!-- <th>비고</th> -->
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM tel ORDER BY name ASC");
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td><input type='radio' name='edit_id' value='{$row['idx']}'></td>";
            // echo "<td>{$row['id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['tel']}</td>";
            echo "<td>{$row['addr']}</td>";
            // echo "<td>{$row['remark']}</td>";
            echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <div class="text-center mt-4 mb-5">
      <button type="submit" formaction="tel_update.php" class="btn btn-warning">수정하기</button>
      <button type="submit" formaction="tel_delete.php" class="btn btn-danger">삭제하기</button>
      <a href="tel_select_1.php" class="btn btn-secondary">돌아가기</a>
    </div>
  </form>
</div>
</body>
</html>
