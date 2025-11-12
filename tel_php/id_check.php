

<?php
session_start();
include("php/db-connect.php"); // DB 연결

$id = trim($_POST['id'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($id) || empty($password)) {
    echo "<script>alert('아이디와 비밀번호를 모두 입력해주세요.'); history.back();</script>";
    exit;
}

// 아이디 존재 여부 확인
$sql = "SELECT * FROM tel WHERE id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row) {
    // 비밀번호 검증
    if (password_verify($password, $row['password'])) {
        // 로그인 성공 시 세션 저장
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['level'] = $row['level'];

        // 관리자 레벨 확인
        if ($row['level'] == 10) {
            echo "<script>
                    alert('관리자 로그인 성공');
                    // location.href = './tel_select.php';
                    location.href = './tel_select.php';
                  </script>";
        } else {
            echo "<script>
                    alert('관리자 권한이 없습니다. 접근이 제한됩니다.');
                    location.href = './login.php';
                  </script>";
            session_destroy();
        }
    } else {
        echo "<script>alert('계정이 올바르지 않습니다.'); history.back();</script>";
    }
} else {
    echo "<script>alert('존재하지 않는 아이디입니다.'); history.back();</script>";
}
?>
