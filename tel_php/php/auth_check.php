

<?php


if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('관리자만 접근할 수 있습니다.');
            location.href = './login.php';
          </script>";
    exit;
}

// 관리자 권한 확인
if ($_SESSION['level'] != 10) {
    echo "<script>
            alert('접근 권한이 없습니다. 관리자만 이용 가능합니다.');
            history.back();
          </script>";
    exit;
}
?>
