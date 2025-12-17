<?php

// 이곳은 DB연결방식을 PDO방식을 사용함!(mysqli 방식이 아니므로 주의!)
// 🔥 한국 시간대 설정 (프로젝트 전역 적용)
date_default_timezone_set('Asia/Seoul');

//[선택] 내컴퓨터 Xampp DB계정 사용!
$host = 'localhost';
$dbname = 'terraone';
$username = 'root';
$password = '84325285';

//[선택] dothome(닷홈) DB계정 사용!
 // $host = 'localhost';
 // $dbname = 'jikji35';
 // $username = 'jikji35';
 // $password = 'jeonsj84325285#'; 


//[선택] infinityfree DB계정 사용!
//$host = 'sql112.infinityfree.com';
//$dbname = 'if0_38329280_jikji35';
//$username = 'if0_38329280';
//$password = 'Jel10hYSP4LK5D';


//[선택-2] ivyro(아이비로) DB계정 사용!
// $host = 'localhost';
// $dbname = 'jikji35';
// $username = 'jikji35';
// $password = 'jeon2457#'; 




try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}
?>