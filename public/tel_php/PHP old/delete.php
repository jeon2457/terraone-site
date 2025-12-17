<!-- ✅ 이페이지는 tel_edit.php(연락망 수정/삭제) 에서 삭제버튼을 클릭하면 정말로 삭제할것인지 확인을 묻고나면  여기서 데이타베이스(DB)로 넘겨서 삭제 처리된다.-->
  
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="직지35회" />
    <meta name="format-detection" content="telephone=no">
    <title>UPDATE</title> 
</head>

<body>

<?php
// ✅ 파일명 주의!  
require './php/auth_check.php';
require './php/db-connect-pdo.php'; 

try {  // ⭐ try 블록 추가
    // 전달된 id 값 확인
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // SQL 문장 작성
        $sql = "DELETE FROM member WHERE id = :id";

        // 준비된 문장을 실행하여 레코드 삭제
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // 삭제 완료 후 메시지 출력 및 페이지 이동
        echo "레코드가 삭제되었습니다.";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<a href='./tel_edit.php'>목록으로 돌아가기</a>";
        exit;
    } else {
        echo "삭제할 레코드를 찾을 수 없습니다.";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<a href='./tel_edit.php'>목록으로 돌아가기</a>";
        exit;
    }
} catch (PDOException $e) {
    echo "오류: " . $e->getMessage();
    exit;
}
?>

</body>
</html>