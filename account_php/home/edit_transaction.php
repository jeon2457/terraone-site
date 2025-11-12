<?php
session_start(); // 로그인 정보나 사용자 데이터를 유지하는 데 사용.

  
require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
//include 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-2)  
  



$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // 현재 요청이 POST 방식인지 확인합니다.
    $id = $_POST['id']; //수정할 거래의 고유 ID
    $date = $_POST['date']; //거래 날짜
    $type = $_POST['type']; //거래 유형 (수입 or 지출)
    $category = $_POST['category']; //거래 카테고리 (예: 월급, 식비 등)
    $description = $_POST['description']; //거래 설명 (예: "편의점에서 물건 구매")
    $amount = $_POST['amount']; //거래 금액

    if ($type === '수입') {
        $table = 'income_table';
    } else if ($type === '지출') {
        $table = 'expense_table';
    }

  
    // SQL UPDATE 문을 사용하여 거래 정보를 업데이트합니다.
    // $table → 업데이트할 테이블 (income_table 또는 expense_table)
    // SET → 수정할 컬럼 지정 (date, category, description, amount)
    // WHERE id = ? → 특정 id에 해당하는 데이터를 수정
    $stmt = $pdo->prepare("UPDATE $table SET date = ?, category = ?, description = ?, amount = ? WHERE id = ?");
    $stmt->execute([$date, $category, $description, $amount, $id]); // id 값이 정확히 일치하는 데이터만 업데이트됩니다.

    header("Location: account_view.php");
    exit;
}
?>
