<?php

// 이곳은 DB 테이블 member를 사용한다.
// 바인딩(binding); SQL 구문과 파라미터(parameter) 값을 연결하는 것
// 파라미터(parameter); 함수나 메소드 등에서 입력받는 값을 의미합니다. 



// (아래) "Member" 클래스 정의! 여러함수들을 이 속에 포함시켰다.
// 클래스명은 대문자로 시작한다.
 
class Member {  // 데이터베이스 연결을 담당
  // 멤버 변수, 프로퍼티
  private $conn; // private; 외부에서 다이렉트로 접근이 불가!

  // 생성자(첫번째 무조건 호출되는 함수)
  // public; 외부에서 접근이 가능하다.
  public function __construct($db) {  //PDO 인스턴스를 받는다($db)
    $this->conn = $db;
  }
  // 위의 멤버 클래스는 데이터베이스 연결을 담당하는 클래스입니다. $conn은 private 변수로 선언되어 있으며, 생성자를 통해 $db라는 PDO 인스턴스를 받아 $conn에 할당합니다. 이후 $conn 변수를 사용하여 데이터베이스에 쿼리를 실행하는 등의 작업을 수행합니다. 이렇게 멤버 변수($conn)와 생성자($db)를 활용하여 데이터베이스 연결 코드를 캡슐화함으로써, 코드의 가독성과 유지보수성을 향상시킬 수 있습니다.
  // $db 객체를 Member 클래스 생성자의 매개변수로 전달받아서 $conn 멤버 변수에 할당합니다. $conn 멤버 변수는 이후에 Member 클래스에서 데이터베이스 연결을 사용하는 모든 함수에서 사용됩니다. 이렇게 하는 이유는 객체를 생성할 때마다 데이터베이스 연결을 매번 새로 만드는 것보다는 한 번만 만들어놓고 공유하여 사용하는 것이 효율적이기 때문입니다.



  // [[ 아이디 중복체크 ]]
  // id_exists(); 아이디 중복체크 ~ 멤버 함수, 메소드
  // 테이블명:  member

  public function id_exists($id) { // $id 값이 데이터베이스의 member 테이블에서 존재하는지 확인하는 함수 id_exists() 입니다.

    $sql = "SELECT * FROM member WHERE id=:id"; // DB에있는 id와 :id(입력한 id값)같은 레코드를 검색
    $stmt = $this->conn->prepare($sql); // 데이터베이스 연결 객체에 접근하고 객체를 변수에 할당,SQL 쿼리를 실행할 준비
    $stmt->bindParam(':id', $id); // DB에있는 $id 값을 :id(입력한 id값) 파라미터에 바인딩
    $stmt->execute(); //쿼리 실행

    return $stmt->rowCount() ? true : false;  // 집합의 행 수를 가져와서 0보다 큰지 여부(id가 존재)에 따라 true 또는 false 값을 반환($id와 일치하는 레코드의 존재 유무확인)

  }








  // [[ 이메일 형식 유효성검사 테스트 ]]   ==> @ 삽입 유무확인!
  // email_format_check()는 이메일을 검증해주는 메서드이다.
  // 함수명은 "email_format_check"이고, 입력값으로 이메일 주소를 받습니다.
  public function email_format_check($email) { 

    // 내장 함수인 'filter_var' 함수를 사용. 이 함수는 입력값으로 받은 값이 해당 필터 조건을 만족하는지 확인합니다.FILTER_VALIDATE_EMAIL 옵션을 사용해서, 이메일 주소의 형식이 올바른지 확인합니다
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  // (예) $email = 'ddd' 로 입력한다면 ; // 결과값: bool(false) 검증실패!
  // (예) $email = 'ddd@naver.com' 로 입력한다면 ; // 결과값: string(11) "ddd@naver.com" 검증통과!




  // [[ 이메일 중복체크 ]]
  public function email_exists($email) {
    $sql = "SELECT * FROM member WHERE email=:email";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute(); //실행
    
    return $stmt->rowCount() ? true : false;  // 데이타가 생성된 행의갯수! 집합의 행 수를 가져와서 0보다 큰지 여부에 따라 true 또는 false 값을 반환($id와 일치하는 레코드의 존재 유무확인)

}



  // [[ 회원정보 가입입력 - 암호화로 만듬! ]]
  // PDO를 이용한 데이터베이스 입력(insert) 작업을 수행하는 함수!
 
    public function input($marr) { //배열로 처리

  // 패스워드(password) 단방향 암호화 사용!
  // 똑같은 비밀번호를 사용해도 코드값이 다르다!
    $new_hash_password = password_hash($marr['password'], PASSWORD_DEFAULT);  // 암호화!

  // 예) 아래의 id는 데이타베이스(DB)의 필드이고, VALUES쪽의 :id는 사용자쪽에서 입력하는 데이타이다. :는 바인딩, 연결해주는(넘겨준다) 의미라고보면된다.
    $sql = "INSERT INTO member(id, name, password, tel, email, zipcode, addr1, addr2, photo, create_at, ip) VALUES (:id, :name, :password, :tel, :email, :zipcode, :addr1, :addr2, :photo, NOW(), :ip)";

    $stmt = $this->conn->prepare($sql);  // [주의!] 접속인자 db,conn 반드시 확인(구분할것!)

    // stmt객체가 bindParam메소드를 호출,접근한다는 의미이고, $marr['name'] 배열의 'name' 키에 해당하는 값이 :name Placeholder에 바인딩(연결)되어 실행
    $stmt->bindParam(':id', $marr['id']);
    $stmt->bindParam(':name', $marr['name']);
    $stmt->bindParam(':password', $new_hash_password); //비밀번호 암호화!
    $stmt->bindParam(':tel', $marr['tel']);
    $stmt->bindParam(':email', $marr['email']);
    $stmt->bindParam(':zipcode', $marr['zipcode']);
    $stmt->bindParam(':addr1', $marr['addr1']);
    $stmt->bindParam(':addr2', $marr['addr2']);
    $stmt->bindParam(':photo', $marr['photo']);
    $stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
    $stmt->execute();

    // bindParam() 메서드는 SQL 쿼리문에 들어갈 값들을 바인딩합니다. :컬럼이름에 해당하는 부분에 $marr 배열에서 각각의 값을 가져와서 바인딩해줍니다.
    // var_dump($marr);


  }






  // 로그인 가입확인($id, $pw로 인자를 2개 받는형식으로 사용!)
  public function login($id, $pw) {
    // password_verify($password, $new_password)

    // 로그인 암호화로 회원정보 비교검증을 하는데 암호화된것이라 아이디 필드를 불러와 id를 서로 비교한다
    $sql = "SELECT password FROM member WHERE id=:id"; //(코드풀이)~ id 필드의 값이 :id 변수와 일치하는 데이터를 가져오겠다는 의미(앞쪽에 있는 id는 DB 필드명)

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id); // 패스워드는 암호화처리된 상태라 불러올수가 없고, 입력한 아이디와 같은 아이디인지를 확인한다 
   
    $stmt->execute();

    if ($stmt->rowCount()) { // id가 존재한다면
      $row = $stmt->fetch();

      // password_verify PHP 함수는 암호화된 패스워드를 검증하는 역할을 합니다.
      if (password_verify($pw, $row['password'])) { //입력한 패스워드($pw) 인자와, DB패스워드(password) 인자를 서로 비교
        $sql = "UPDATE member SET login_dt=NOW() WHERE id=:id" ;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id); 
        $stmt->execute();

      return true; //일치! 로그인성공, ../pg/login_process.png로 넘어간다
      
      } else {
        return false; //불일치! 로그인실패
      }

    } else {
      return false;
    }

    //return $stmt->rowCount() ? true : false; // 값이 1이면 true, 0이면 false

  }



  public function logout() {
    session_start();
    session_destroy(); //로그아웃 시키기

    die('<script>self.location.href="../index.php";</script>');
  }



  public function getInfoFormIdx($idx) {
    $sql = "SELECT * FROM member WHERE idx=:idx";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":idx", $idx);
    $stmt->setFetchMode(PDO::FETCH_ASSOC); //중복 재처리! 예) Array([idx] => 11 [id] => terraone     [name] => 홍길동 [password] => 0$OMpmptiI7QWOHnOGQhh0IuimkNTeL1xS9mfW1cKKujgwuJc9CcL/a [tel] => 010-2544-6666 [email] => rraone@naver.com  [zipcode] => 15292 [addr1] => 경기 안산시 상록구 성포동 591-1 (성포동, 경일초등학교)       [addr2] => 12  [photo] => terraone.png       [create_at] => 2023-05-13 22:35:56        [login_dt] => 2023-05-15 10:02:33 [ip] => ::1)
    
    $stmt->execute();
    return $stmt->fetch();
  }




  public function getInfo($id) {
    $sql = "SELECT * FROM member WHERE id=:id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->setFetchMode(PDO::FETCH_ASSOC); //중복 재처리! 예) Array([idx] => 11 [id] => terraone     [name] => 홍길동 [password] => 0$OMpmptiI7QWOHnOGQhh0IuimkNTeL1xS9mfW1cKKujgwuJc9CcL/a [tel] => 010-2544-6666 [email] => rraone@naver.com  [zipcode] => 15292 [addr1] => 경기 안산시 상록구 성포동 591-1 (성포동, 경일초등학교)       [addr2] => 12  [photo] => terraone.png       [create_at] => 2023-05-13 22:35:56        [login_dt] => 2023-05-15 10:02:33 [ip] => ::1)
    
    $stmt->execute();
    return $stmt->fetch();
  }



  // 회원정보 수정
  public function edit($marr) {
    $sql = "UPDATE member SET name=:name, tel=:tel, email=:email, zipcode=:zipcode, addr1=:addr1, addr2=:addr2, photo=:photo ";
    $params = [
      ':name' => $marr['name'],
      ':tel' => $marr['tel'],
      ':email' => $marr['email'],
      ':zipcode' => $marr['zipcode'],
      ':addr1' => $marr['addr1'],
      ':addr2' => $marr['addr2'],
      ':photo' => $marr['photo']
    ];

    if($marr['password'] != '') {
      // 패스워드(password) 단방향 암호화 사용!
      $new_hash_password = password_hash($marr['password'], PASSWORD_DEFAULT);  // 암호화!
      $params[':password'] = $new_hash_password;

      $sql .= ", password=:password";
    }



    if($_SESSION['ses_level'] == 10 && isset($marr['idx']) && $marr['idx'] != '') {
      $params[':level'] = $marr['level']; //레벨변경
      $params[':idx'] = $marr['idx']; //판단
      $sql .= ", level=:level";
      $sql .= " WHERE idx=:idx";

    } else {
      $params[':id'] = $marr['id']; //일반회원이라서 id값이 필요
      $sql .= " WHERE id=:id";  // [중요!]: " WHERE 절 앞에 반드시 공백 추가! "(공백) WHE... 그렇지않으면 패스워드 변경시에 에러가 난다.
    }


    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

  }




    // 회원목록 관리(게시판)
    // 번호, 아이디, 이름, 이메일, 등록일시 꼭 필요한것만 가져온다.
    // 아래 쿼리문에서 date출력을 할수도있다. (방법-2) $sql = "SELECT idx, id, name, email, DATE_FORMAT(create_at,'%Y-%m-%d %H:%i') AS create_at FROM member ORDER BY idx DESC";   ====> 2023-03-15 15:58


    public function list($page, $limit, $paramArr) { //DESC; 데이타 역순으로 정렬
      $start = ($page - 1) * $limit; // 1페이지라면 0,5/ 2페이지라면 5,5/ 3페이지라면 10,5/15,5...

      $where = "";
      if($paramArr['sn'] != '' && $paramArr['sf'] != '' ) {
        switch($paramArr['sn']) {
          case 1 : $sn_str = 'name'; break;
          case 2 : $sn_str = 'id'; break;
          case 3 : $sn_str = 'email'; break;
        }

        $where = " WHERE ". $sn_str."=:sf "; //띄워쓰기 주의!
      }

      $sql = "SELECT idx, id, name, email, DATE_FORMAT(create_at, '%Y-%m-%d %H:%i') AS create_at FROM member ". $where ." ORDER BY idx DESC LIMIT ".$start.",". $limit;  //1페이지면 0,5,  2페이지면 5,5 /10, 5/ 10, 5..
    // DATE_FORMAT(create_at, '%Y-%m-%d %H:%i')는 날짜에서 마지막 "초"를 생략하기위함!
    // ORDER BY idx DESC LIMIT는 출력물을 내림차순(최신게시물이 제일위로 가게끔 하기위함!)
     
      $stmt = $this->conn->prepare($sql);

      if($where != '') {
        $stmt->bindParam(':sf', $paramArr['sf']);
      }

      $stmt->setFetchMode(PDO::FETCH_ASSOC); //중복 재처리! , ASSOC는 필드명만 가져온다.
      $stmt->execute();
      return $stmt->fetchAll(); //모두 가져온다() , fetch()는 한개만 가져온다.
    }




    // 게시판목록 갯수구하기
    public function total($paramArr) {

      $where = "";
      if($paramArr['sn'] != '' && $paramArr['sf'] != '' ) {
        switch($paramArr['sn']) {
          case 1 : $sn_str = 'name'; break;
          case 2 : $sn_str = 'id'; break;
          case 3 : $sn_str = 'email'; break;
        }

        $where = " WHERE ". $sn_str."=:sf "; // "(공백처리)WHERE" => 띄워쓰기 주의!
      }


      $sql = "SELECT COUNT(*) cnt FROM member ". $where;  // "member(공백처리)" => 띄워쓰기 주의!
      // echo $sql; // 테스트 확인해볼것!
      // exit;

      $stmt = $this->conn->prepare($sql);

      if($where != '') {
        $stmt->bindParam(':sf', $paramArr['sf']);
      }

      $stmt->setFetchMode(PDO::FETCH_ASSOC); //중복 재처리! , ASSOC는 필드명만 가져온다.
      $stmt->execute();
      $row = $stmt->fetch(); 
      return $row['cnt'];  // row에 cnt값을 던져준다

    }



    // 회원목록 관리(데이타를 엑셀에 저장하기위한 작업)

    public function getAllData() { 

      $sql = "SELECT * FROM member  ORDER BY idx ASC";  
      $stmt = $this->conn->prepare($sql);
      $stmt->setFetchMode(PDO::FETCH_ASSOC); //중복 재처리! , ASSOC는 필드명만 가져온다.
      $stmt->execute();
      return $stmt->fetchAll(); //모두 가져온다()
    }



    


    // 회원삭제

    public function member_del($idx) {
      // (삭제방법-1)
      // $sql = "delete from member where idx=". $idx;

      // (삭제방법-2)
      $sql = "DELETE FROM member WHERE idx=:idx";  // 테이블명; member
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':idx', $idx);
      $stmt->execute();

    }





    // 프로필 이미지 업로드==> public; 외부접근 가능!
    public function profile_upload($id, $new_photo, $old_photo = '') {

      if($old_photo != '') { //기존 이미지파일이 들어있다면!
        unlink(PROFILE_DIR. $old_photo); //삭제한다. PROFILE_DIR는 dbconfig.php에서 경로를 정의한것이다.
      }
      //exit;   // 이미지 삭제되는지 테스트
  
      $tmparr = explode('.', $new_photo['name']); // 1단계 작업 예) ['2', 'jpg']
      $ext = end($tmparr); // 2단계 작업;확장자 추출함   예) jpg
      $photo = $id .'.'. $ext;  // 새로운 파일명을 만듬. 예) terraone.jpg
  
      copy($new_photo['tmp_name'],  PROFILE_DIR."/". $photo); //업로드된 이미지 저장경로
  
      return $photo;
    }




} // Member Class 끝부분






