<?php
// main.php는 로그인된 사용자에게 메인 페이지를 보여주는 역할을 합니다. 이 페이지는 로그인한 사용자만 접근할 수 있도록 보호되어 있으며, 사용자 정보를 표시하거나, 사용자별로 다른 기능을 제공할 수 있습니다.

session_start(); // 함수를 호출하여 세션을 시작,

if (!isset($_SESSION['id'])) { // $_SESSION 배열에서 사용자 ID를 가져와 로그인 여부를 확인
    header("Location: login_form.php"); // 로그인되지 않은 사용자가 페이지에 접근하려고 하면, 로그인 폼 (login_form.php) 으로 리디렉션
    exit();
}

$loggedInUserId = $_SESSION['id'];
$loggedInUsername = $_SESSION['username'];

echo "<h1>Welcome, " . htmlspecialchars($loggedInUsername) . "!</h1>";
echo "<p>Your user ID is: " . htmlspecialchars($loggedInUserId) . "</p>"; // 로그인된 사용자 정보 표시 (선택 사항):

// ... (나머지 main.php 내용) ...
?>