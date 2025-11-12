// 이페이지는 회원가입 입력폼 member_input.php페이지를 제어하기위해서(중복검사 및 유효성검사) 스크립트를 사용하는 페이지를 담당한다.

document.addEventListener('DOMContentLoaded', () => {
  // 회원가입 아이디 입력란에 중복체크 확인!
  // 회원가입 입력폼(member_input.php)에서 아이디 중복확인버튼의 아이디값이다 id="btn_id_check"
  const btn_id_check = document.querySelector('#btn_id_check'); // 중복확인버튼 <button>태그의 id='btn_id_check' 로 정의!

  // 자바스크립트에서 입력폼으로 접근하는방법으로 2가지가있다.
  // 1. form과 name 값으로 접근하기 예) document.input_form.id.value;
  // 2. querySelector와 id 값으로 접근하기 예) document.querySelector('#input_id').value;

  btn_id_check.addEventListener('click', () => {
    const f_id = document.querySelector('#f_id'); 
    if (f_id.value == '') {
      alert('아이디를 입력해 주세요.');
      return false;  
    }

    const f1 = new FormData();
    f1.append('id', f_id.value);
    f1.append('mode', 'id_chk');  

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../pg/member_process.php', 'true');
    xhr.send(f1); 

    xhr.onload = () => {
      if (xhr.status == 200) {
        const data = JSON.parse(xhr.responseText);
        if (data.result == 'success') {
          alert('사용이 가능한 아이디입니다.');
          document.input_form.id_chk.value = '1';
          f_name.focus();
        } else if (data.result == 'fail') {
          document.input_form.id_chk.value = '0';
          alert('이미 사용중인 아이디입니다. 다른 아이디를 입력해주세요.');
          f_id.value = '';
          f_id.focus();
        } else if (data.result == 'empty_id') {
          alert('아이디가 비어 있습니다.');
          f_id.focus();
        }
      }
    };
  });

  // 이메일 중복확인 체크 및 이메일 형식체크 구간
  const btn_email_check = document.querySelector('#btn_email_check');
  btn_email_check.addEventListener('click', () => {
    const f_email = document.querySelector('#f_email'); 
    if (f_email.value == '') {
      alert('이메일을 입력해 주세요.');
      f_email.focus();
      return false;
    }

    const f1 = new FormData();
    f1.append('email', f_email.value);
    f1.append('mode', 'email_chk');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../pg/member_process.php', 'true');
    xhr.send(f1); 

    xhr.onload = () => {
      if (xhr.status == 200) {
        const data = JSON.parse(xhr.responseText);
        if (data.result == 'success') {
          alert('사용이 가능한 이메일입니다.');
          document.input_form.email_chk.value = '1';
        } else if (data.result == 'fail') {
          document.input_form.email_chk.value = '0';
          alert('이미 사용중인 이메일입니다. 다른 이메일 입력해주세요.');
          f_email.value = '';
          f_email.focus();
        } else if (data.result == 'empty_email') {
          alert('이메일이 비어 있습니다.');
          f_email.focus();
        } else if (data.result == 'email_format_wrong') {
          alert('이메일이 형식에 맞지 않습니다.');
          f_email.value = '';
          f_email.focus();
        }
      }
    };
  });

  // 가입 버튼 클릭시
  const btn_submit = document.querySelector('#btn_submit'); 
  btn_submit.addEventListener('click', () => {
    const f = document.input_form;

    if (f.id.value == '') {
      alert('아이디를 입력해 주세요.');
      f.id.focus();
      return false;
    }

    if (f.id_chk.value == 0) {
      alert('아이디 중복확인을 해주시기 바랍니다.');
      return false;
    }

    if (f.name.value == '') {
      alert('이름을 입력해 주세요.');
      f.name.focus();
      return false;
    }

    if (f.password.value == '') {
      alert('비밀번호를 입력해 주세요.');
      f.password.focus();
      return false;
    }

    if (f.password2.value == '') {
      alert('확인용 비밀번호를 입력해 주세요.');
      f.password2.focus();
      return false;
    }

    if (f.password.value != f.password2.value) {
      alert('비밀번호가 서로 일치하지 않습니다.');
      f.password.value = '';
      f.password2.value = '';
      f.password.focus();
      return false;
    }

    if (f.tel.value == '') {
      alert('전화번호를 입력해 주세요.');
      f.tel.focus();
      return false;
    }

    if (f.email.value == '') {
      alert('이메일을 입력해 주세요.');
      f.email.focus();
      return false;
    }

    if (f.email_chk.value == 0) {
      alert('이메일 중복확인을 해주세요.');
      return false;
    }

    // ✅ [수정] 우편번호는 선택 입력으로 변경
    // if (f.zipcode.value == '') {
    //   alert('우편번호를 입력해 주세요.');
    //   f.zipcode.focus();
    //   return false;
    // }

    // 주소입력 확인
    if (f.addr1.value == '') {
      alert('주소를 입력해 주세요.');
      f.addr1.focus();
      return false;
    }

    f.submit(); 
  });

  // ✅ 우편번호 찾기 버튼(다음 kakao에서 코드 가져옴!)
  const btn_zipcode = document.querySelector('#btn_zipcode');
  btn_zipcode.addEventListener('click', () => {

    if (typeof daum === 'undefined' || !daum.Postcode) {
      alert('주소 검색 서비스를 불러올 수 없습니다.\n잠시 후 다시 시도해 주세요.');
      console.error('⚠️ Daum Postcode script not loaded.');
      return;
    }

    const postcode = new daum.Postcode({
      oncomplete: function (data) {
        let addr = '';
        let extra_addr = '';

        if (data.userSelectedType == 'J') {
          addr = data.jibunAddress;
        } else if (data.userSelectedType == 'R') {
          addr = data.roadAddress;
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
        f_zipcode.value = data.zonecode;

        const f_addr2 = document.querySelector('#f_addr2');
        f_addr2.focus();
      },
    });

    postcode.open(); 
  });

  // 프로필 이미지 선택입력
  const f_photo = document.querySelector('#f_photo');
  f_photo.addEventListener('change', (e) => {
    const reader = new FileReader();
    reader.readAsDataURL(e.target.files[0]);
    reader.onload = function (event) {
      const f_preview = document.querySelector('#f_preview');
      f_preview.setAttribute('src', event.target.result);
    };
  });

  // 전화번호 입력값 바꿔주는 함수
  function oninputPhone(tel) {
    tel.value = tel.value
      .replace(/[^0-9]/g, '')
      .replace(
        /(^02.{0}|^01.{1}|[0-9]{3,4})([0-9]{3,4})([0-9]{4})/g,
        '$1-$2-$3'
      );
  }
});
