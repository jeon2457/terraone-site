
<?php


//[선택-1] dothome(닷홈) DB계정 사용!
$host = 'localhost';
$dbname = 'jikji35';
$username = 'jikji35';
$password = 'jeonsj84325285#'; 

//[선택-2] 내컴 자체의 데이터베이스 접속정보
//  $host = 'localhost';
//  $dbname = 'terraone';  // DB접속후 데이타베이스명을 새로만들기 한것이다.
//  $username = 'root';  // XAMPP설치후 초기값은 root 이다.
//  $password = '84325285';  // XAMPP설치후 초기값은 공백('')이다.
//  $charset = 'utf8mb4';


//[선택-3] ivyro(아이비로) DB계정 사용!
// $host = 'localhost';
// $dbname = 'jikji35';
// $username = 'jikji35';
// $password = 'jeon2457#'; 



// try {
//     // PDO 객체 생성
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("데이터베이스 접속 오류: " . $e->getMessage());
// }


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}

?>