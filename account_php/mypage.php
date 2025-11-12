<!--  이페이지는 로그인 후에 접근가능하다. -->
<!-- $memArr는 현재 로그인한 회원정보를 담고있다! -->

<?php

session_start();

$ses_id = (isset($_SESSION['ses_id']) && $_SESSION['ses_id'] != '') ? $_SESSION['ses_id'] : ''; //로그인 확인!
$ses_level = (isset($_SESSION['ses_level']) && $_SESSION['ses_level'] != '') ? $_SESSION['ses_level'] : '';  // 관리자와 일반회원을 구분!

if($ses_id == '') {

  echo "
  <script>
    alert('로그인 후 접근이 가능한 메뉴입니다.')
    self.location.href='./index.php';
  </script>
  ";
  exit;
}



    include '../inc/dbconfig.php';
    include '../inc/member.php';
    // include '../home/php/db-connect.php';
    // include '../home/php/member.php';   // Member Class가 정의된파일

    

    $mem = new Member($db);
    $memArr = $mem->getInfo($ses_id);

    // print_r($memArr);
    // exit;

    $js_array = ['../js/mypage.js'];  // 중복검사 및 유효성검사를 하기위한 스크립트로 제어하는 페이지
    $g_title = '회원정보수정';

    // 헤드부분
    // CSS 연결된곳: <href="../css/style.css" >
    include './inc_header.php';  // header부분 불러와서 추가!


?>



<!-- 다음 kakao 우편번호 서비스 코드 가져오기(CDN) -->
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>



<main class="w-50 mx-auto border rounded-5 p-5">
  <h1 class="text-center">회원정보 수정</h1>

  <!-- (아래) enctype="multipart/form-data"는 파일업로드하기위해서 적용시킨다 -->
  <form name="input_form" method="post" enctype="multipart/form-data" autocomplete="off" action="./pg/member_process.php">
    
    <!--  mode는 숨겨진 input태그를 사용했다.(mode가 id_chk인지, email_chk인지, input인지 찾기위함!) -->
    <input type="hidden" name="mode" value="edit">
    <input type="hidden" name="email_chk" value="0">
    <input type="hidden" name="old_email" value="<?= $memArr['email']; ?>"> <!-- 이메일 수정 -->
    <input type="hidden" name="old_photo" value="<?= $memArr['photo']; ?>"> <!--프로필이미지 수정 -->


  <!-- 아이디 입력 -->
  <div class="d-flex gap-2 align-items-end"> <!-- align-items-end;아이디 중복확인 정렬 -->
    <div> 
      <label for="f_id" class="form-label">아이디</label>
      <input type="text" name="id" readonly class="form-control" id="f_id" value="<?= $memArr['id']; ?>"> <!-- $memArr는 현재 로그인한 회원정보! -->
  
    </div> 
  </div>


  <!-- 이름(name) 입력 -->
  <div class="d-flex mt-3 gap-2 align-items-end"> <!-- align-items-end;아이디 중복확인 정렬 -->
    <div> 
    <!-- asterisk_7 은 입력필수 *표시로 CSS style.css 가상클래스로 설정 -->
    <label for="f_id" class="form-label asterisk_7">이름</label>
    <input type="text" name="name" class="form-control" id="f_name" value="<?= $memArr['name']; ?>" placeholder="이름을 입력해 주세요.">
    </div> 
  </div>


  <!-- 비밀번호 입력 -->
  <div class="d-flex mt-3 gap-2 justify-content-between"> <!-- align-items-end;아이디 중복확인 정렬 -->
    <div class="w-50"> 
    <label for="f_password" class="form-label asterisk_7">비밀번호</label>
    <input type="password" name="password" class="form-control" id="f_password" placeholder="비밀번호를 입력해 주세요.">
    </div> 

  
    <div class="w-50 mt-0"> 
    <label for="f_password2" class="form-label asterisk_7">비밀번호 확인</label>
    <input type="password" name="password2" class="form-control" id="f_password2" placeholder="비밀번호를 입력해 주세요.">
    </div> 
  </div>

    <!-- 전화번호 입력[방법-2] 오류! 해결못함! -->
    <!-- (참고사이트) https://www.youtube.com/watch?v=xNYkm1UP0x4  -->
    <!-- <div  class="d-flex mt-3 gap-2 justify-content-start">
      <div class="w-25">
      <label for="f_tel" class="form-label asterisk_7">전화번호</label>
      <input type="text" id="tel1" maxLength="3" pattern="[0-9]{3}" onkeyup="next(this.value, 3, 'tel2');" onkeydown="checkFloat(event, this.value)" name="tel1" class="form-control">
      </div>
      <div class="w-25 mt-3">
      <label for="f_tel" class="form-label"></label>
        <input type="text" id="tel2" maxLength="4" pattern="[0-9]{4}" onkeyup="next(this.value, 4, 'tel3');" onkeydown="checkFloat(event, this.value)" name="tel2" class="form-control">
      </div>  
      <div class="w-25 mt-3">
      <label for="f_tel" class="form-label"></label>
        <input type="text" id="tel3" maxLength="4" pattern="[0-9]{4}" onkeydown="checkFloat(event, this.value)" name="tel3" class="form-control">
      </div> 
    </div> -->

    <!-- 전화번호 입력[방법-1] -->
    <div class="d-flex mt-3 gap-2 align-items-end">   <!-- // align-items-end;아이디 중복확인 정렬 -->
      <div> 
      <label for="f_tel" class="form-label asterisk_7">전화번호</label> 
      <!-- // 전화번호 입력값 바꿔주는 함수 oninput="oninputPhone(this)" -->
      <!-- (참고) https://stickode.tistory.com/495 -->
       <!-- input type="tel"; 이메일 주소 구문에 맞는지 검증을 거칩니다.-->
      <!-- <input type="text" name="tel" class="form-control" id="f_tel" pattern="[0-9]{11}" oninput="oninputPhone(this)" maxlength="11" placeholder="' - '없이 전화번호 입력..."> -->
      <input type="text" name="tel" class="form-control" id="f_tel" value="<?= $memArr['tel']; ?>" oninput="autoHyphen(this)" maxlength="13" placeholder="' - '없이 전화번호 입력..." autofocus>
      </div> 
    </div> 
    
    <!-- 이메일 입력 -->
    <div class="d-flex mt-3 gap-2 align-items-end"> <!-- align-items-end;아이디 중복확인 정렬 -->
      <div class="flex-grow-1"> 
      <label for="f_email" class="form-label asterisk_7">이메일</label>
      <!-- input type="email"; 이메일 주소 구문에 맞는지 검증을 거칩니다.-->
      <input type="email" name="email" value="<?= $memArr['email']; ?>" class="form-control" id="f_email" placeholder="이메일을 입력해 주세요.">
      </div> 
      <button type="button" id="btn_email_check" class="btn btn-secondary">중복확인</button>
    </div>

    <!-- 우편번호 입력 -->
    <div class="d-flex gap-2  mt-3 align-items-end">
      <div>
        <label for="f_zipcode" class="asterisk_7">우편번호</label>
        <!-- readonly는 우편번호 입력란에 입력을 못하게 막아놓는 기능 -->
        <input type="text" name="zipcode" id="f_zipcode" value="<?= $memArr['zipcode']; ?>" readonly class="form-control" maxlength="5" minlength="5" placeholder="우편번호 찾기 클릭!">
      </div>  
      <!-- 우편번호 서비스: https://postcode.map.daum.net/guide -->
        <button type="button" class="btn btn-secondary" id="btn_zipcode">우편번호 찾기</button>
      </div>

    <!-- 주소 입력 -->
    <div class="d-flex mt-3 gap-2 justify-content-between"> <!-- align-items-end;아이디 중복확인 정렬 -->
      <div class="w-50">
      <!-- asterisk_7 은 입력필수 *표시로 CSS style.css 가상클래스로 설정 -->   
      <label for="f_addr1" class="form-label asterisk_7">주소</label>
      <input type="text" name="addr1" value="<?= $memArr['addr1']; ?>" class="form-control" id="f_addr1" placeholder="">
      </div> 

    <!-- 상세주소 입력(선택) -->
      <div class="w-50 mt-0"> 
      <!-- 이곳은 입력을 생략해도 다음단계로 넘어갈 수있다. -->
      <label for="f_addr2" class="form-label">상세주소</label>
      <input type="text" name="addr2" value="<?= $memArr['addr2']; ?>"  class="form-control" id="f_addr2" placeholder="상세주소를 입력해 주세요.">
      </div> 
    </div>

    <!-- 프로필 이미지 -->
    <div class="mt-3 d-flex gap-5">
      <div>
        <label for="f_photo" class="form-label">프로필 이미지</label>
        <input type="file" name="photo" id="f_photo" class="form-control">
      </div> 
    <?php if($memArr['photo']) {
        echo '<img src="../data/profile/'.$memArr['photo'].'" id="f_preview" class="w-25" alt="profile image">';
      } else {
        echo '<img src="../images/profile.png" id="f_preview" class="w-25" alt="profile image">';
      }
    ?>  
      
    </div>

    <div class="mt-3 d-flex gap-2">
      <button type="button" class="btn btn-primary w-50" id="btn_submit">수정확인</button> <!-- 42행에 form action="" 이 있다 -->
      <button type="button" class="btn btn-secondary w-50">수정취소</button>
    </div>  
    </form> 
  
</main>
<!-- 입력폼 작성후에 가입확인 버튼을 누르면 member_process.php파일로 데이타를 넘겨주면
array(7) {   // 7개의 배열항목
  ["id"]=>
  string(5) "korea"
  ["password"]=>
  string(5) "dee33"
  ["tel"]=>
  string(11) "01055557777"
  ["email"]=>
  string(5) "korea"
  ["name"]=>
  string(9) "홍길동"
  ["zipcode"]=>
  string(5) "36541"
  ["addr1"]=>
  string(71) "경북 영양군 영양읍 서부리 301-1 (서부리, MBC가요주점)"
  ["addr2"]=>
  string(3) "433"(웹페이지 소스보기)
} 형태로 들어간다 -->




<!-- // 전화번호 입력란(3칸짜리) 입력을 채우면 자동으로 다음칸으로 이동시키기 -->
<script>
  function next(val, len, nextId) {
    if(val.length == len) {
      document.getElementById(nextId).focus();
    }
  }

  function checkNum(event) {
    var key = event.key;
    console.log(key);
    if ((key >= 0 && key < 10) || key == 'Backspace') {
      return true;
    } else {
      event.preventDefault();
    }
  }


  // 전화번호 "-"기호 자동입력 함수
  const autoHyphen = (target) => {
    target.value = target.value
      .replace(/[^0-9]/g, '')  //숫자만 입력
      .replace(/^(\d{2,3})(\d{3,4})(\d{4})$/, `$1-$2-$3`);

}

</script>




<?php
// 푸터부분
include './inc_footer.php';  // header부분 불러와서 추가!
?>

