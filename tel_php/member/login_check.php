<?php
// login_check.php는 login_form.php에서 제출된 로그인 폼 데이터를 처리하고,
// 데이터베이스에서 사용자 정보를 확인하여 세션을 설정하는 역할을 합니다.

session_start();

require '../php/db-connect.php'; // DB 접속 정보 불러오기

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결 실패: " . $e->getMessage());
}

// 폼 데이터 가져오기
$id = $_POST['id'] ?? '';
$password_input = $_POST['password'] ?? '';

// 입력값 검증
if (empty($id) || empty($password_input)) {
    echo "<script>
        alert('아이디와 비밀번호를 모두 입력해주세요.');
        history.back();
    </script>";
    exit;
}

try {
    // 사용자 정보 조회
    $stmt = $pdo->prepare("SELECT * FROM member WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);




    if ($user && password_verify($password_input, $user['password'])) {
        // 로그인 성공
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_level'] = $user['level'];
        $_SESSION['user_idx'] = $user['idx'];

        // 로그인 후 리다이렉트
        if (isset($_SESSION['redirect_url'])) {
            $redirect = $_SESSION['redirect_url'];
            unset($_SESSION['redirect_url']);
            
            echo "<script>
                alert('" . htmlspecialchars($user['name']) . "님 환영합니다!');
                location.href='" . $redirect . "';
            </script>";
            exit;
        } else {
            // 기본 페이지로 이동
            echo "<script>
                alert('" . htmlspecialchars($user['name']) . "님 환영합니다!');
                location.href='../home/account_input.php';  // 이동경로
            </script>";
            exit;
        }
    } else {
        // 로그인 실패
        echo "<script>
            alert('아이디 또는 비밀번호가 일치하지 않습니다.');
            history.back();
        </script>";
        exit;
    }
} catch (PDOException $e) {
    die("로그인 처리 중 오류 발생: " . $e->getMessage());
}
?>