<?php
session_start();
  
  
require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
  

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // 삭제할 거래의 id 값을 받아옴.
    $type = $_POST['type']; // 거래 유형(수입 or 지출)을 받아옴.

    if ($type === '수입') { // type 값이 "수입"이면 $table = 'income_table'; → 수입 테이블에서 삭제
        $table = 'income_table';
    } else if ($type === '지출') { // type 값이 "지출"이면 $table = 'expense_table'; → 지출 테이블에서 삭제
        $table = 'expense_table';
    }

    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?"); // $table 은 위에서 결정한 테이블 (income_table 또는 expense_table).
    // id 가 일치하는 데이터만 삭제하도록 조건을 추가.
    $stmt->execute([$id]); // (플레이스홀더) 자리에 $id 값을 넣고 실행합니다.

    header("Location: account_view.php"); // 삭제 후 가계부 페이지(account_book.php)로 리디렉션합니다.
    exit; // 스크립트 실행을 즉시 종료하여 불필요한 코드 실행을 방지합니다.
}
?>
