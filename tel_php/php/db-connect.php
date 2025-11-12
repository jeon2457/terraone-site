



<?php
//  ✅✅ 이곳은 접속방식이 PDO가 아닌 mysqli 방식을 사용하므로 접속코드와
// 데이타베이스(DB) 전송방식까지 완전히 틀리므로 주의한다! 

$host = "localhost";       // dothome에서는 localhost 그대로
$user = "jikji35";         // DB 아이디
$pass = "jeonsj84325285#";        // DB 비밀번호
$dbname = "jikji35";       // DB 이름

$connect = mysqli_connect($host, $user, $pass, $dbname);
if (!$connect) {
    die("DB 연결 실패: " . mysqli_connect_error());
}

mysqli_set_charset($connect, "utf8");
?>


