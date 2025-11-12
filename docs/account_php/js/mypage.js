// 이페이지는 회원가입 입력폼 member_input.php페이지를 제어하기위해서(중복검사 및 유효성검사) 스크립트를 사용하는 페이지를 담당한다.
// 이페이지는 member_input.js파일과 불필요한부분을 제거하고는 내용이 같다. 이곳을 사용하는이유는 로그인한 회원정보를 수정하는데 이미지(profile)부분을 수정할때 바뀌지않고 console 에러가 나는것을 해결하기위해서 활용하는 페이지이다.

document.addEventListener('DOMContentLoaded', () => {
 

  // 이메일 중복확인 체크 및 이메일 형식체크 구간
  const btn_email_check = document.querySelector('#btn_email_check');
  btn_email_check.addEventListener('click', () => {
    //alert(1);  // 중복확인 클릭했을때 1이 뜨면 정상!
    const f_email = document.querySelector('#f_email'); //id 입력값
    //alert(f_email.value); // id입력한값이 뜨는지 확인!
    if (f_email.value == '') {
      alert('이메일을 입력해 주세요.');
      f_email.focus();
      return false;
    }

    if(document.input_form.old_email.value == f_email.value) {
      alert('이메일을 변경하지 않았습니다.')
      return false;
    }

    // AJAX(비동기 자바스크립트 XML; Asynchronous JavaScript And XML)
    const f1 = new FormData(); //email 입력값을 f1으로 담는다
    f1.append('email', f_email.value);
    f1.append('mode', 'email_chk');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', './pg/member_process.php', 'true');  //상대경로 주의!
    xhr.send(f1); // f1: email입력값

    xhr.onload = () => {
      //입력한 email과 DB email을 비교검증후 팝업창으로 결과를 보여줌
      if (xhr.status == 200) {
        const data = JSON.parse(xhr.responseText); // JSON타입으로 변경
        if (data.result == 'success') {
          alert('사용이 가능한 이메일입니다.');
          document.input_form.email_chk.value = '1'; //중복확인 거쳤을경우
        } else if (data.result == 'fail') {
          document.input_form.email_chk.value = '0'; //중복확인 안거쳤을경우
          alert('이미 사용중인 이메일입니다. 다른 이메일 입력해주세요.');
          f_email.value = ''; // 입력란의 값을 자동으로 지운다.
          f_email.focus(); // 마우스커스를 자동으로 email 입력란으로 이동시킨다
        } else if (data.result == 'empty_email') {
          alert('이메일이 비어 있습니다.');
          f_email.focus();
        } else if (data.result == 'email_format_wrong') {
          alert('이메일이 형식에 맞지 않습니다.');
          f_email.value = ''; // 입력란의 값을 자동으로 지운다.
          f_email.focus();
        }
      }
    };
  });




  // 가입 버튼 클릭시
  const btn_submit = document.querySelector('#btn_submit'); //가입확인 버튼
  btn_submit.addEventListener('click', () => {
    //alert(1); // 가입확인버튼 작동하는지 테스트
    const f = document.input_form; // (회원가입 입력폼 이름) form name="input_form"데이타를 f변수에 담는다


 
    // 이름 입력 확인
    if (f.name.value == '') {
      //input태그의 name값
      alert('이름을 입력해 주세요.');
      f.name.focus();
      return false;
    }

    // 비밀번호 확인
    if (f.password.value != '' && f.password2.value == '') {
      //input태그의 name값
      alert('확인용 비밀번호를 입력해 주세요.');
      f.password2.focus();
      return false;
    }

 
    // 비밀번호 일치여부 확인!
    if (f.password.value != f.password2.value) {
      //input태그의 name값
      alert('비밀번호가 서로 일치하지 않습니다.');
      f.password.value = '';
      f.password2.value = '';
      f.password.focus();
      return false;
    }

    // 전화번호 확인
    if (f.tel.value == '') {
      //input태그의 name값
      alert('전화번호를 입력해 주세요.');
      f.tel.focus();
      return false;
    }

    // 이메일 입력부분 확인
    if (f.email.value == '') {
      //input태그의 name값
      alert('이메일을 입력해 주세요.');
      f.email.focus();
      return false;
    }

    // 이메일을 변경했다면
    if(f.old_email.value != f.email.value) {
      if (f.email_chk.value == 0) {
        alert('이메일 중복확인을 해주세요.');
        return false;
      }
    }
    

    // 우편번호 확인
    if (f.zipcode.value == '') {
      //input태그의 name값
      alert('우편번호를 입력해 주세요.');
      f.zipcode.focus();
      return false;
    }

    // 주소입력 확인
    if (f.addr1.value == '') {
      //input태그의 name값
      alert('주소를 입력해 주세요.');
      f.addr1.focus();
      return false;
    }

    // 상세주소 확인
    // if (f.addr2.value == '') {
    //   //input태그의 name값
    //   alert('상세주소를 입력해 주세요.');
    //   f.addr2.focus();
    //   return false;
    // }

    // 입력 데이타 전송하기!
    f.submit(); // ==> ../pg/member_process.php 파일로 입력데이타를 넘긴다
  });




  
  // 우편번호 찾기 버튼(다음 kakao에서 코드 가져옴!)
  const btn_zipcode = document.querySelector('#btn_zipcode');
  btn_zipcode.addEventListener('click', () => {

    new daum.Postcode({
      oncomplete: function (data) {
        // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분입니다.
        // (주소)도로명과 지번은 userSelectedType: "J"/"R"로 구분한다 - console에서 확인가능.
        // console.log(data) // {postcode: '', postcode1: '', postcode2: '', postcodeSeq: '', zonecode: '10403', …}

        let addr = '';
        let extra_addr = '';

        if (data.userSelectedType == 'J') {
          // 지번으로 검색했을때
          addr = data.jibunAddress; // kakao 코드
        } else if (data.userSelectedType == 'R') {
          // 도로명으로 검색했을때
          addr = data.roadAddress; // kakao 코드
        }


        if (data.bname != '') {
          extra_addr = data.bname;
        }

        if (data.buildingName != '') {
          if (extra_addr == '') {
            extra_addr = data.buildingName;
          } else {
            extra_addr += ', ' + data.buildingName;
          }
        }






        if (extra_addr != '') {
          extra_addr = ' (' + extra_addr + ')';
        }

        const f_addr1 = document.querySelector('#f_addr1');
        f_addr1.value = addr + extra_addr;

        const f_zipcode = document.querySelector('#f_zipcode');
        f_zipcode.value = data.zonecode; // zonecode: 우편번호(kakao 코드)

        const f_addr2 = document.querySelector('#f_addr2');
        f_addr2.focus(); //상세주소 커서이동



      },
    }).open();
  });






  // 이미지 선택 입력
  const f_photo = document.querySelector('#f_photo');
  f_photo.addEventListener('change', (e) => {
    // 이벤트 리스너는 change 이벤트가 발생했을 때 {...}부분을 실행, type: 'change'
    //alert("파일을 선택했네요.")
    //console.log(e);  // (Console출력)Event {isTrusted: true, type: 'change', target: input#f_photo.form-control, currentTarget: input#f_photo.form-control, eventPhase: 2, …}

    const reader = new FileReader();
    reader.readAsDataURL(e.target.files[0]); // [0];1번째 파일

    reader.onload = function (event) {
      //이벤트 로딩
      //console.log(event)

      const f_preview = document.querySelector('#f_preview');
      f_preview.setAttribute('src', event.target.result)
      
    }

  });




  // 전화번호 입력값 바꿔주는 함수
  //function oninputPhone(target) {
  function oninputPhone(tel) {
    //target.value = target.value
    tel.value = tel.value
      .replace(/[^0-9]/g, '')
      .replace(
        /(^02.{0}|^01.{1}|[0-9]{3,4})([0-9]{3,4})([0-9]{4})/g,
        '$1-$2-$3'
      );
  }
});
