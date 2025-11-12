<?php

//[선택] dothome 의 데이터베이스 접속정보
$servername = "localhost"; 
$dbuser = "jikji35"; 
$dbpassword = "jeonsj8....#"; 
$dbname = "jikji35"; 

//[선택] 내컴 자체의 데이터베이스 접속정보
// $servername = "localhost"; // 기본 서버주소. $host로 정의할수도있다.
// $dbuser = "root"; // XAMPP설치후 초기값은 root 이다. $username으로 정의할수도있다.
// $dbpassword = "84325285"; // XAMPP설치후 초기값은 공백('')이다. $password로 정의할수도있다.
// $dbname = "terraone"; // DB접속후 데이타베이스명을 새로만들기 한것이다.


//[선택] infinityfree DB계정 사용!
  // $host = 'sql112.infinityfree.com';
  // $dbname = 'if0_38329280_jikji35';
  // $username = 'if0_38329280';
  // $password = 'Jel10hYSP4LK5D';


//(필독!) XAMPP를 처음설치했을때는 데이타베이스(DB)를 세팅하기위해서 브라우저 주소창에서
// http://localhost/phpmyadmin/index.php접속해서 데이터베이스명을 terraone로 만들고 그속에
// 테이블을 만들어넣는다. 예) tel,member,expense_table,income_table,images,jikji35,tel,member
// 더 쉽게 만들려면, xampp\htdocs\DB_backup폴더에 DB 테이블이 만들어져있다.(.sql 파일)
// DB 메뉴에서 데이타를 "가져오기"로 파일을 불러오면 바로 만들어넣을수있다.
// 패스워드 기본값이 공백이다. 만약에 패스워드를 추가한다면 아래와같이
// 다음 2가지의 작업을 해줘야한다.

// (작업1): MySQL root 비밀번호를 코드에 맞게 설정 (권장)
// phpMyAdmin 상단 메뉴에서 "사용자 계정" 또는 "User accounts" 클릭
// root 사용자 찾기 (Host가 localhost인 것)
// "권한 수정" 또는 "Edit privileges" 클릭
// "비밀번호 변경" 또는 "Change password" 클릭
// 비밀번호를 84325285로 설정
// "비밀번호" 입력란에: 84325285
// "비밀번호 확인" 입력란에: 84325285
// "실행" 또는 "Go" 클릭

// (작업2)
// 1. XAMPP 설치 폴더로 이동:
//C:\xampp\phpMyAdmin\config.inc.php
//2. 파일 열어서 수정
//메모장이나 텍스트 에디터로 config.inc.php 파일을 엽니다.
//3. 비밀번호 부분 찾아서 수정
//다음과 같은 부분을 찾으세요:
//php$cfg['Servers'][$i]['password'] = '84325285';
//4. 파일 저장 후 Apache 재시작
//5.파일을 저장합니다
//6.XAMPP Control Panel에서:
//Apache Stop 클릭
//Apache Start 클릭
//7. 다시 접속
//브라우저에서 http://localhost/phpmyadmin 접속하면 정상적으로 들어가집니다.




// [PDO방식] ==>> 보안: PDO는 SQL 인젝션과 같은 보안 취약점을 방지하기 위한 기능을 내장
//PDO(PHP Data Objects)는 여러 종류의 데이터베이스를 같은 방식으로 사용할 수 있게 해준다. 

try {
  $db = new PDO("mysql:host={$servername};dbname={$dbname}", $dbuser, $dbpassword);

  // Prepared Statement 를 지원하지 않는경우 DB의 기능을 사용하도록 해줌!
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); //쿼리 버퍼링을 활성화
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pdo 객체가 에러를 처리하는 방식, 에러가난다면 출력
  
  //echo "DB 연결에 성공"; // 정상가동일때는 주석처리한다.
  // [필독] echo "DB 연결에 성공"을 주석처리 하지않으면 JSON변환하면서 에러가 발생! 일부 기능이 마비된다.-주의!


} catch (PDOException $e) {    //에러가난다면.. 
  //echo "DB 연결에 실패했습니다.";
  echo $e->getMessage(); //에러가나면 에러를 뿌린다.

}



// 아래에 정의한것들은 admin폴더에서 사용하는 관리자의 파일들 상대경로가 (../../파일명.php)형태로 복잡하게 되어있는것을 단순화 시키기위해서 사용하려는것이다.
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/project/member');
define('ADMIN_DIR', DOCUMENT_ROOT .'/admin');
define('DATA_DIR', DOCUMENT_ROOT .'/data');
define('PROFILE_DIR', DATA_DIR .'/profile');