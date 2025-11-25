
<?php

//[선택-1] dothome(닷홈) DB계정 사용!
//  $host = 'localhost';
//  $dbname = 'jikji35';
//  $username = 'jikji35';
//  $password = 'jeonsj84325285#'; 

 //[선택-3] 내컴 자체의 데이터베이스 접속정보
 $host = 'localhost';
 $dbname = 'terraone';
 $username = 'root';
 $password = '84325285';

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
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결 실패: " . $e->getMessage());
}


?>