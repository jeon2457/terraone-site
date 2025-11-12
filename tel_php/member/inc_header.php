
    <!DOCTYPE html>
    <html lang="ko">
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?= (isset($g_title) && $g_title != '') ? $g_title : 'Terraone'; ?></title>

      <!-- 부트스트랩 5.3 CDN -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
      crossorigin="anonymous"
    />
    <!-- 부트스트랩 5.3 스크립트 (defer: html을 모두 읽고나서 스크립트를 처리하라) -->
    <script
      defer
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
      crossorigin="anonymous">
    </script>

    <!-- 강력 세로고침 Ctrl+F5 -->
    <link rel="stylesheet" href="./css/common.css">


  </head>
  
  
    
    

    <?php
    if(isset($js_array)) { //세팅이 되어있을때만 실행하라!

        // $js_array 는 약관동의(stipulation.php)에 배열변수로 선언되어있다
        // foreach 반복문을 사용하여 $js_array 배열의 각 요소를 $var 변수에 대입하고,
        // 이후에 echo 함수를 사용하여 HTML 코드를 출력합니다.
        // 여기서 $var 는 js폴더내의 js파일중에서 선택하는 경로이다.
        foreach($js_array AS $var) {
            echo '<script src="'.$var.'?v='. date('YmdHis') .'"></script>'.PHP_EOL;
        }
      }

    ?>

  <body>    
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg bg-light">
          <div class="container-fluid"> <!-- fluid: 전체너비를 사용 -->
            <a href="./index.php" class="d-flex align-items-center mb-2 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
              <img src="./images/logo.svg" class="me-2 mt-4 mb-4 jikji35_logo" alt="logo image" />
              <!-- // 마진 me; margin-end , ms; margin-start 의미! -->
              <!-- <a class="navbar-brand fs-4" href="#">Simple header</a> -->
            </a>
            <span>Terraone</span>
        <!-- <a class="navbar-brand" href="#">Navbar</a> -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> -->
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <ul class="navbar-nav">

              <!-- <ul class="nav nav-pills "> -->
              <!-- 네비바 메뉴항목 active클래스 부여 활성화   -->
              <!-- 로그인 전과 후의 변화를 주기위해 여기서 작업했다  -->
                <li class="nav nav-item nav-pills ms-1">
                <?php if(isset($ses_id) && $ses_id != '') {

                  // 로그인 상태
                ?>
                  <li class="nav-item"><a href="./index.php" class="nav-link <?=  ($menu_code == 'home') ? 'active': ''; ?>">Home</a></li>
                  <li class="nav-item"><a href="./member/company.php" class="nav-link <?=  ($menu_code == 'company') ? 'active': ''; ?>">회사소개</a></li>

                  <!-- 로그인한 사람의 레벨등급이 10이라면! Admin페이지를 추가로 메뉴에 보여준다 -->                  
                  <?php if($ses_level == 10) { 
                  ?>  
                  <li class="nav-item"><a href="./admin/index.php" class="nav-link <?=  ($menu_code == 'member') ? 'active': ''; ?>">Admin</a></li>
                  <?php
                  } else { ?>  <!-- level 10이 아닌경우 -->

                    <li class="nav-item"><a href="./mypage.php" class="nav-link <?=  ($menu_code == 'member') ? 'active': ''; ?>">My Page</a></li>
                  <?php } ?>


                  <?php
                    foreach($boardArr AS $row) {
                      echo '<li class="nav-item"><a href="./board.php?bcode='.$row['bcode'].'" class="nav-link';
                      if(isset($_GET['bcode']) && $_GET['bcode'] == $row['bcode']) {
                        echo 'active';
                      }
                      echo '">'.$row['name'].'</a></li>';
                    }
                  ?>

                  

                  <li class="nav-item"><a href="./pg/logout.php" class="nav-link <?=  ($menu_code == 'logout') ? 'active': ''; ?>">로그아웃</a></li>
                    

                <?php
                } else {
                  // 로그인이 안된상태
                ?>
                  <li class="nav-item"><a href="./index.php" class="nav-link <?=  ($menu_code == 'home') ? 'active': ''; ?>">Home</a></li>
                  <li class="nav-item">
                  <a href="./member/company.php" class="nav-link <?=  ($menu_code == 'company') ? 'active': ''; ?>">회사소개</a></li>
                  <li class="nav-item">
                  <a href="./member/stipulation.php" class="nav-link <?=  ($menu_code == 'member') ? 'active': ''; ?>">회원가입</a></li>
                  <li class="nav-item">
                  <a href="./board/board.php" class="nav-link <?=  ($menu_code == 'board') ? 'active': ''; ?>">게시판</a></li>
                  <li class="nav-item">
                    <a href="./member/login.php" class="nav-link <?=  ($menu_code == 'login') ? 'active': ''; ?>">로그인</a></li>
                <?php  
                } 
                ?>
                  
                
              </ul>
            </div>
          </div>
        </nav>
  </div>




