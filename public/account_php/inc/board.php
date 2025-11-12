<?php
// 게시판 클래스

class BoardManage {  // 데이터베이스 연결을 담당
  
  private $conn; // private; 외부에서 다이렉트로 접근이 불가!

  // 생성자(첫번째 무조건 호출되는 함수)
  // public; 외부에서 접근이 가능하다.
  public function __construct($db) {  //PDO 인스턴스를 받는다($db)
    $this->conn = $db;
  }
}
