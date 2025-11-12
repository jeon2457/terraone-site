document.addEventListener('DOMContentLoaded', () => {
  // 아이디 입력란에 입력여부 체크확인!
  const btn_login = document.querySelector('#btn_login');
  btn_login.addEventListener('click', () => {
    //alert("버튼이 클릭되었습니다.")

    // 입력폼을 순차적으로 데이타입력을 했는지 검증한다
    // 아이디 입력여부 확인!
    const f_id = document.querySelector('#f_id');
    if (f_id.value == '') {
      alert('아이디를 입력해 주세요!');
      f_id.focus();
      return false;
    }

    // 비밀번호 입력여부 확인!
    const f_pw = document.querySelector('#f_pw');
    if (f_pw.value == '') {
      alert('비밀번호를 입력해 주세요!');
      f_pw.focus();
      return false;
    }




    // Ajax 방식으로 처리한다.
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../pg/login_process.php', 'true'); // true; 비동기 방식

    // form객체
    const f1 = new FormData();
    f1.append('id', f_id.value);
    f1.append('pw', f_pw.value);

    // 전송
    xhr.send(f1);

    // 통신상태 체크(200: 정상상태)
    xhr.onload = () => {
      if (xhr.status == 200) { //통신에 성공한다면...
        //alert(xhr.responseText); //테스트
        const data = JSON.parse(xhr.responseText); //json방식으로 변환
        console.log(data); // 예) {result: 'login_fail'}

        if (data.result == 'login_fail') {
          alert('해당 정보는 존재하지 않습니다.');
          f_id.value == ''; // 입력값 초기화
          f_pw.value == ''; // 입력값 초기화
          f_id.focus(); // 포커스 이동
          return false;
        } else if (data.result == 'login_success') {
          alert('로그인에 성공했습니다.');
          self.location.href = '../index.php'; //로그인에 성공했을때 이동하는 URL
        }
      } else {
        alert('통신에 실패했습니다. 다음에 다시 시도해 주시기바랍니다.');
      }
    };
  });





  // 로그인 입력폼(member/login.php)에서 사용하기위함!
  // 로그인 마지막 비밀번호 입력란에서 그냥 엔터키만 치더라도 마우스로 확인버튼을 누른것과 같은 효과를 주기위해서 만든것이다.
  //  패스워드 입력란 요소를 가져옵니다.
  var passwordInput = document.getElementById("f_pw");

  // 엔터 키 이벤트를 처리하는 함수 
  function handleEnterKey(event) {
    if (event.keyCode === 13) { // Enter 키의 keyCode는 13입니다.
      event.preventDefault(); // 기본 동작 방지
      document.getElementById("btn_login").click(); // 전송 버튼 클릭
    }
  }

  // 패스워드 입력란에 이벤트 리스너를 추가합니다.
  passwordInput.addEventListener("keydown", handleEnterKey);



});



