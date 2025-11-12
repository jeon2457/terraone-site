<?php
// 게시판 관리 클래스
// DB에 terraone 데이타베이스명으로 만들고, 게시판을 활용하기위해서
// board_manage테이블명(idx,name,bcode,btype,cnt,create_at)을 만들었다.

class BoardManage {  // 데이터베이스 연결을 담당
  
  private $conn; // private; 외부에서 다이렉트로 접근이 불가!

  // 생성자(첫번째 무조건 호출되는 함수)
  // public; 외부에서 접근이 가능하다.
  public function __construct($db) {  //PDO 인스턴스를 받는다($db)
    $this->conn = $db;
  }



  // 게시판 목록
  public function list() {

    // $start = ($page - 1) * $limit; // 1페이지라면 0, 2페이지라면 1...

    // $where = "";
    // if($paramArr['sn'] != '' && $paramArr['sf'] != '' ) {
    //   switch($paramArr['sn']) {
    //     case 1 : $sn_str = 'name'; break;
    //     case 2 : $sn_str = 'id'; break;
    //     case 3 : $sn_str = 'email'; break;
    //   }

    //   $where = " WHERE ". $sn_str."=:sf "; //띄워쓰기 주의!
    // }

    $sql = "SELECT idx, name, bcode, btype, cnt, DATE_FORMAT(create_at, '%Y-%m-%d %H:%i') AS create_at FROM board_manage ORDER BY idx ASC ";

    $stmt = $this->conn->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC); //중복 재처리! , ASSOC는 필드명만 가져온다.
    $stmt->execute();
    return $stmt->fetchAll(); //모두 가져온다()
}

  // 게시판 생성
  public function create($arr) {
    $sql = "INSERT INTO board_manage(name, bcode, btype, create_at) values (:name, :bcode, :btype, NOW())";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':name', $arr['name']);
    $stmt->bindParam(':bcode', $arr['bcode']);
    $stmt->bindParam(':btype', $arr['btype']);
    $stmt->execute();

  }

  // 게시판 정보수정
  public function update($arr) {
    $sql = "UPDATE board_manage SET name=:name, btype=:btype WHERE idx=:idx";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':name', $arr['name']);
    $stmt->bindValue(':btype', $arr['btype']);
    $stmt->bindValue(':idx', $arr['idx']);
    $stmt->execute();
  }


  // 게시판 idx로 게시판정보 가져오기!
  public function getBcode($idx) {
    $sql = "SELECT bcode FROM board_manage WHERE idx=:idx";
    $stmt  = $this->conn->prepare($sql);
    $stmt->bindParam(':idx', $idx);
    $stmt->setFetchMode(PDO::FETCH_COLUMN, 0); // 필드명 값 하나만 가져온다.
    $stmt->execute();
    return $stmt->fetch(); //한개 가져온다()
  }



  // 게시판 삭제
  public function delete($idx) {
    // bcode
    $bcode = $this->getBcode($idx);

    $sql = "DELETE FROM board_manage WHERE idx=:idx";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':idx', $idx);
    $stmt->execute();

    
    //아래 테이블명을 board가 아닌 board_a로 만들었다.
    //아래 테이블명을 board가 아닌 board_a로 만들었다.
    $sql = "DELETE FROM board_a WHERE bcode=:bcode";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':bcode', $bcode);
    $stmt->execute();

  }

  // 게시판 코드 생성
  public function bcode_create() {
    $letter = range('a', 'z');
    $bcode = '';

    for($i = 0; $i < 6; $i++) {
      $r = rand(0, 25);
      $bcode .= $letter[$r];
      //echo $bcode ."<br>";
    }
    return $bcode;
  }


  // 게시판 정보 불러오기
  public function getInfo($idx) {
    $sql = "SELECT * FROM board_manage WHERE idx=:idx";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':idx', $idx);
    $stmt->setFetchMode(PDO::FETCH_ASSOC); // ASSOC; 필드명으로 가져온다.
    $stmt->execute();
    return $stmt->fetch(); //한개 가져온다()

  }




  // 게시판 코드로 게시판 명 가져오기
  public function getBoardName($bcode) {
    $sql = "SELECT name FROM board_manage WHERE bcode=:bcode";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':bcode', $bcode);
    $stmt->setFetchMode(PDO::FETCH_COLUMN, 0); 
    $stmt->execute();
    return $stmt->fetch(); //한개 가져온다()

  }

  }







