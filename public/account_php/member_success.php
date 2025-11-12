

<?php

$g_title = "회원가입을 축하드립니다!";
$js_array = [ '../js/member_success.js' ];

$menu_code = 'member'; // 네비바 항목별색상 active 를 부여하기 위함!

include "./inc_header2.php";

?>


<main class="w-75 mx-auto border rounded-5 p-5 d-flex gap-5" style="height: calc(100vh - 248px)">

  <img src="../images/logo.svg" class="w-50" alt="로고이미지">
  <div>
    <h3>회원 가입을 축하드립니다!</h3>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Enim, libero!</p>

    <button class="btn btn-primary mt-5" id="btn_login">로그인 하기</button>
    <!-- 로그인 버튼은 js/member_success.js와 연결 -->
  </div>
</main>






<?php

include 'inc_footer.php';  // header부분 불러와서 추가!
?>