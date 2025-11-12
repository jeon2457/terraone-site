<?php

// dothome(닷홈) DB계정 사용 연결설정
$host = 'localhost';
$dbname = 'jikji35';
$username = 'jikji35';
$password = 'jeonsj84325285#';


//[선택-1] 자체 나의컴퓨터 DB계정 사용!
// $host = 'localhost';
// $dbname = 'terraone';
// $username = 'root';
// $password = '84325285';

//[선택-2] ivyro(아이비로) DB계정 사용!
// $host = 'localhost';
// $dbname = 'jikji35';
// $username = 'jikji35';
// $password = 'jeon2457#'; 

// check_duplicate.php는 데이터베이스에서 아이디 또는 이메일의 중복 여부를 확인하고, 결과를 JSON 형식으로 반환하는 역할을 합니다. 이 파일은 register_form.php(회원가입폼) 에서 Ajax 요청을 통해 호출되어 실시간으로 중복 여부를 확인하는 데 사용됩니다.
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(array('error' => '데이터베이스 연결 실패: ' . $e->getMessage()));
    exit; // 스크립트의 실행을 즉시 중단
}

// 사용자가 회원가입 입력폼에서 입력한 데이터 가져오기
$type = $_POST['type']; // id, password, tel, email 중 하나
$value = $_POST['value'];

// 허용되는 타입 목록
$allowedTypes = array('id', 'tel', 'email');

// 데이터 타입 및 값 검증
if (!in_array($type, $allowedTypes)) {
    echo json_encode(array('error' => '잘못된 타입입니다.'));
    exit; // 스크립트의 실행을 즉시 중단
}

// 중복 검사
// register_form.php(회원가입) 페이지가 열려 있는 동안, 사용자가 아이디 또는 이메일 입력 필드에 값을 입력하면 실시간으로 check_duplicate.php 파일에 Ajax 요청이 전송되어 중복 여부를 확인
try {
    // SQL Injection 방지
    $sql = "SELECT COUNT(*) FROM member WHERE `$type` = :value"; // 백틱 사용
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':value', $value);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    // 결과 반환
    if ($count > 0) {
        echo json_encode(array('duplicate' => true));
        exit; // 스크립트의 실행을 즉시 중단
    } else {
        echo json_encode(array('duplicate' => false));
        exit; // 스크립트의 실행을 즉시 중단
    }
} catch (PDOException $e) {
    echo json_encode(array('error' => $e->getMessage())); // 오류 메시지 반환
    exit; // 스크립트의 실행을 즉시 중단
} catch (Exception $e) {
    echo json_encode(array('error' => $e->getMessage())); // 예외 메시지 반환
    exit; // 스크립트의 실행을 즉시 중단
}
?>