<?php


//[선택-1] dothome(닷홈) DB계정 사용!
$host = 'localhost';
$dbname = 'jikji35';
$username = 'jikji35';
$password = 'jeonsj84325285#'; 

 //[선택-2] 내컴 자체의 데이터베이스 접속정보
//  $host = 'localhost';
//  $dbname = 'terraone';
//  $username = 'root';
//  $password = '84325285';

//[선택] ivyro(아이비로) DB계정 사용!
// $host = 'localhost';
// $dbname = 'jikji35';
// $username = 'jikji35';
// $password = 'jeon2457#';



// register_process.php는 register_form.php에서 전송된 회원 가입 폼 데이터를 처리하고, 데이터베이스에 회원 정보를 저장하는 역할을 합니다. 또한, 데이터 유효성 검사를 수행하고, 오류 발생 시 적절한 응답을 반환합니다.
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결 실패: " . $e->getMessage());
}

// 폼 데이터 가져오기
$id = $_POST['id'];
$password = $_POST['password'];
$username = $_POST['username'];
$tel = $_POST['tel'];
$email = $_POST['email'];
$addr = $_POST['addr'];

// 중복 검사 함수
function checkDuplicate($pdo, $type, $value) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM member WHERE `$type` = :value");
    $stmt->bindParam(':value', $value);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

// 중복 검사 및 오류 메시지 생성
$errors = array();

if (checkDuplicate($pdo, 'id', $id)) {
    $errors['id'] = '이미 사용 중인 아이디입니다. 다른 아이디를 입력해주세요.';
}

if (checkDuplicate($pdo, 'tel', $tel)) {
    $errors['tel'] = '이미 사용 중인 전화번호입니다.';
}

if (checkDuplicate($pdo, 'email', $email)) {
    $errors['email'] = '이미 사용 중인 이메일입니다.';
}

// 오류 메시지가 있으면 JSON 형식으로 반환
if (!empty($errors)) {
    header('Content-Type: application/json');
    echo json_encode($errors);
    exit; // 스크립트 실행을 중단
}

// 비밀번호 해싱(비밀번호를 암호화하고, 암호화된 비밀번호를 데이터베이스에 저장)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// (수정됨) 전화번호 형식 변경
$formattedTel = substr($tel, 0, 3) . '-' . substr($tel, 3, 4) . '-' . substr($tel, 7, 4);

// 회원 정보 저장
try {
    // INSERT 쿼리를 사용하여 member 테이블에 새로운 레코드를 추가
    $sql = "INSERT INTO member (id, password, username, tel, email, addr, date) VALUES (:id, :password, :username, :tel, :email, :addr, CURRENT_TIMESTAMP)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR); // bindParam() 함수를 사용하여 SQL Injection 공격을 방지
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':tel', $formattedTel, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':addr', $addr, PDO::PARAM_STR);
    // $stmt->bindParam(':date', $date, PDO::PARAM_STR); // 더 이상 필요 없음

    if ($stmt->execute()) {
        header('Content-Type: application/json'); //  응답 헤더를 JSON 형식으로 설정
        echo json_encode(array('success' => true)); // 회원 가입 성공 시 {"success": true} JSON 응답을 반환
        exit; // 스크립트 실행을 중단
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('error' => '회원가입 실패!', 'details' => $stmt->errorInfo()));
        exit; // 스크립트 실행을 중단
    }
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(array('error' => '회원가입 중 데이터베이스 오류 발생: ' . $e->getMessage()));
    exit; // 스크립트 실행을 중단
}
?>