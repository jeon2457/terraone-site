<?php

// 테스트용
error_reporting(E_ALL);
ini_set('display_errors', 1);


include "../php/db-connect.php";  // DB접속 연결
include "../php/member.php"; // 로그인 입력정보와 DB 데이타와 비교 유효성검사



$mem = new Member($db);  // Member 클래스, ../inc/member.php에서 클래스 정의했다

// 회원가입 입력 폼(각각의 "name=' '";값으로 지정 세팅) ==> 아래 53행 배열과 연계 $arr = [ ]
$id = (isset($_POST['id']) && $_POST['id'] != '' ) ? $_POST['id'] : '';
// 위 코드에서는, 조건 ($_POST['id'] 변수가 존재하고 빈 문자열이 아닌 경우)이 참이면 $_POST['id'] 변수 값을 $id 변수에 할당하고, 그렇지 않으면 $id 변수를 빈 문자열로 할당합니다.
$name = (isset($_POST['name']) && $_POST['name'] != '' ) ? $_POST['name'] : '';
$password = (isset($_POST['password']) && $_POST['password'] != '' ) ? $_POST['password'] : '';
$tel = (isset($_POST['tel']) && $_POST['tel'] != '' ) ? $_POST['tel'] : '';
$email = (isset($_POST['email']) && $_POST['email'] != '' ) ? $_POST['email'] : '';
$zipcode = (isset($_POST['zipcode']) && $_POST['zipcode'] != '' ) ? $_POST['zipcode'] : '';
$addr1 = (isset($_POST['addr1']) && $_POST['addr1'] != '' ) ? $_POST['addr1'] : '';
$addr2 = (isset($_POST['addr2']) && $_POST['addr2'] != '' ) ? $_POST['addr2'] : '';
$old_photo = (isset($_POST['old_photo']) && $_POST['old_photo'] != '' ) ? $_POST['old_photo'] : '';
$level = isset($_POST['level']) ? $_POST['level'] : 1;


// 아래 mode는 숨겨진 input태그를 사용했다.(mode가 id_chk인지, email_chk인지, input인지 찾기위함!)
$mode = (isset($_POST['mode']) && $_POST['mode'] != '' ) ? $_POST['mode'] : '';




// ?는 삼항 연산자(ternary operator)의 기호입니다. 삼항 연산자는 조건에 따라 두 가지 값 중 하나를 선택하는 연산자입니다.
//위 코드에서는, 조건 ($_POST['mode'] 변수가 존재하고 빈 문자열이 아닌 경우)이 참이면 $_POST['mode'] 변수 값을 $mode 변수에 할당하고, 그렇지 않으면 $mode 변수를 빈 문자열로 할당합니다. 이때 ?: 기호는 참과 거짓의 값을 구분해주는 역할을 합니다.





// 아이디 중복여부 체크($mode 의 value값으로 구분)
if($mode == 'id_chk') {
  if($id == '') {
    die(json_encode(['result' => 'empty_id']));  // 배열을 json타입으로 인코더 변환시킨다
  } 


  if($mem->id_exists($id)) {
    // [[ 아이디 중복체크 ]]
  // id_exists(); 아이디 중복체크 ~ 멤버 함수, 메소드
  // (예); public function id_exists($id) { // $id 값이 데이터베이스의 member2 테이블에서 존재하는지 확인하는 함수 id_exists() 입니다.

    //echo '아이디가 중복됨';
    die(json_encode(['result' => 'fail']));  // json타입 {'result' => 'fail'} 아이디중복
    // 결과값을 member_input.js 파일의 xhr.onload로 넘겨준다
  } else {
    //echo '사용할수 있는 아이디입니다.';
    die(json_encode(['result' => 'success'])); // json타입 {'result' => 'success'} 사용가능 아이디
  }


// 이메일 중복여부 체크(name="$mode" 의 value값으로 구분)
} else if($mode == 'email_chk') {

  if($email == '') {
    die(json_encode(['result' => 'empty_email']));
  } 




  // 이메일 형식체크(@확인!)~ 메일 유효성검사/ 중복검사
  // 먼저, 첫 번째 if 문에서는 입력받은 이메일 주소가 유효한지를 $mem 객체의 email_format_check 메서드를 통해 확인합니다.만약 이메일 주소가 유효하지 않다면, 'email_format_wrong' 메시지와 함께 JSON 형태로 반환하고, 프로그램 실행을 종료합니다.

  if($mem->email_format_check($email) === false) { //유효성검사
    die(json_encode(['result' => 'email_format_wrong']));
  }

  // 유효성 검사를 통과한 이후, 두 번째 if 문에서 해당 이메일 주소가 기존에 존재하는지를 $mem 객체의 email_exists 메서드를 통해 확인합니다.만약 해당 이메일 주소가 이미 존재한다면, 'fail' 메시지와 함께 JSON 형태로 반환하고, 프로그램 실행을 종료합니다.

  if($mem->email_exists($email)) { //중복검사
    die(json_encode(['result' => 'fail'])); // 사용할수 없는 ID(중복)

  // 만약 기존에 존재하지 않는 이메일 주소라면, 'success' 메시지와 함께 JSON 형태로 반환하고, 프로그램 실행을 종료합니다.   
  } else {
    die(json_encode(['result' => 'success'])); // 사용할수 있는 ID
  }


// member_input.php파일 ==> <form>태그안에 <input type="hidden" name="mode" value="input">과 연결
}else if ($mode == 'input') {




// print_r($_FILES); // 테스트 끝나면 반드시 주석처리해야만 한다.
// exit;


// 아래코드는 프로필(Profile) 이미지파일의 파일명 이름을 변경 처리하고자 한다
// explode() 함수는 문자열을 구분자를 기준으로 나누어 배열로 반환하는 함수입니다. 이때 구분자로는 '.' 즉, 파일 이름과 확장자를 구분하는 .을 사용하고, 나누어진 배열은 $tmparr 변수에 저장됩니다.
// <input type="file" name="photo">와 같은 형태로 선언된 파일 업로드 입력 폼의 name 속성 값인 "photo"를 의미합니다. DB에 member2테이블 필드명이 photo로 되어있다.
$photo = ''; //photo 초기화
if(isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
    // 1단계 작업 예) ['2', 'jpg']
    $tmparr = explode('.', $_FILES['photo']['name']);

    // end() 함수는 배열의 마지막 값을 반환하는 함수입니다. 이때 $tmparr 배열의 마지막 값, 즉 파일 확장자를 변수 $ext에 저장합니다.
    $ext = end($tmparr); // 2단계 작업   예) jpg

    // $photo 변수에는 새로운 파일 이름이 저장됩니다. 기존의 $id 변수에는 회원의 아이디가 저장되어 있으며, 여기에 .과 $ext를 결합하여 새로운 파일 이름을 만듭니다.
    $photo = $id .'.'. $ext;  // 예) terraone.jpg

    // 이미지 파일명은 id값을 붙여서 날아간다
    // 받아오는 이미지파일을 저장하기위해서 data폴더와 그속에 profile폴더를 만든다.
    // [알림] 아래 저장경로인 data/profile폴더는 서버가동시 읽고/쓰기/편집 같은 권한을 줘야하기때문에 chmod 777 data/profile 파일 퍼미션 처리해줘야한다.(FTP)
    // $_FILES['photo']['tmp_name']에 있는 임시 파일을 ../data/profile/ 폴더에 $photo로 복사하는 역할

    copy($_FILES['photo']['tmp_name'], "../data/profile/". $photo); //저장경로
}



  // ==> 위 8행 isset 세팅과 연계
  $arr = [  
    'id' => $id,
    'name' => $name,
    'password' => $password,
    'tel' => $tel,
    'email' => $email,
    'zipcode' => $zipcode,
    'addr1' => $addr1,
    'addr2' => $addr2,
    'photo' => $photo,
    'level' => $level  // 추가분
  ];

  // 위 $arr 배열항목(9개)을 $mem으로 모두 담는다.
  $mem->input($arr);




  echo "
  <script>
    self.location.href='../login.php';
  </script>
  ";



} else if($mode == 'edit') {

  // print_r($_FILES);  //테스트
  // exit;


  //프로필 이미지 처리

  if(isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {

    $new_photo = $_FILES['photo'];
    $old_photo = $mem->profile_upload($id, $new_photo, $old_photo);
  }


  session_start();

  // ==> 위 8행 isset 세팅과 연계
  $arr = [  
    'id' => $_SESSION['ses_id'],
    'name' => $name,
    'password' => $password,
    'tel' => $tel,
    'email' => $email,
    'zipcode' => $zipcode,
    'addr1' => $addr1,
    'addr2' => $addr2,
    'photo' => $old_photo,
    'level' => $level  // 추가분
  ];
  // (예:) Array ( [id] => terraone [name] => 전상준 [password] => [tel] => 010-6666-6666 [email] => terraone@gmail.com [zipcode] => 25477 [addr1] => 강원 강릉시 가작로 267 (포남동, MBC강원영동) [addr2] => [photo] => terraone.png )

  // print_r($arr);  // 테스트 확인용
  // exit;

  // 위 $arr 배열항목(9개)을 $mem으로 모두 담는다.
  $mem->edit($arr);




  echo "
  <script>
    alert('수정되었습니다.');
    self.location.href='../index.php'
  </script>
  ";


}