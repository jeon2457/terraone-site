<!--  jikji35연락망의 첫페이지 하단부 깃허브링크부분을 이곳으로 링크를 변경해서 로그인을 거쳐서 다음페이지로 진입하게 만들수있다.  예정!-->
<!-- login_form.php(로그인 입력폼)는 사용자에게 로그인 폼을 보여주는 역할을 합니다. 이 폼은 사용자가 서비스에 로그인하기 위해 필요한 정보(아이디, 비밀번호)를 입력할 수 있도록 설계되었습니다. 사용자가 폼에 정보를 입력하고 제출하면, 입력된 데이터는 login_check.php 파일로 전송되어 처리됩니다. -->

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-config" content="/browserconfig.xml">
    
    <!-- 파비콘 아이콘들 -->
    <link rel="icon" href="/favicon.ico?v=2" />

    <link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
    <link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
    <link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />

    <link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">
    
    <!-- 부트스트랩 5.3.3  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* 전체 화면에 대한 스타일 */
        html, body {
            height: 100%; /* 화면 높이 100% */
            margin: 0;
            display: flex; /* Flexbox 레이아웃 사용 */
            align-items: center; /* 수직 중앙 정렬 */
            justify-content: center; /* 수평 중앙 정렬 */
        }

        .login-container {
            width: 500px; /* 박스 크기를 500px로 설정 */
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center; /* 내용 중앙 정렬 */
        }

        p.login_text {
          font-size: 24px;
          color: #6c757d;
          text-align: center; /* 내용 중앙 정렬 */
        }

        .login-icon {
            width: 30px;
            height: 30px;
            margin: 0 auto 20px;
            border-radius: 50%;
            overflow: hidden; /* 이미지가 원 밖으로 나가지 않도록 */
        }

        .login-icon img {
            width: 100%;
            height: auto;
            display: block; /* 이미지 아래 여백 제거 */
        }

      
      
      
      
        /* 반응형 디자인: 480px 이하 화면에서 박스 크기 조정 */
        @media (max-width: 480px) {
            .login-container {
                width: 95%; /* 화면 너비의 95%로 설정 */
            }
        }
        
        p.login_text {
          font-size: 22px;
        }
      
        /* Bootstrap 스타일 재정의 (선택 사항) */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #545b62;
            border-color: #545b62;
        }
    </style>
</head>

<body>
    <div class="login-container">
      <p class="login_text">로그인 입력</p> 
        <div class="login-icon mt-4">
            <img src="./images/clova.jpg" alt="로그인 아이콘">
        </div>
        
    <!-- <form action="member/login_check.php" method="post"> -->
    <form action="./id_check.php" method="post">
        <div class="mb-3">
            <label for="id" class="form-label">아이디:</label>
            <input type="text" class="form-control" id="id" name="id" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">비밀번호:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="d-grid mt-5 gap-3">
            <button type="submit" class="btn btn-primary">관리자 로그인</button>
            <a href="./tel_input.php" class="btn btn-secondary">회원등록</a>
        </div>
    </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>