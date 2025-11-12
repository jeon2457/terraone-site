// 이페이지는 회원가입 약관동의에 관한 동의체크를 제어하는 스크립트 페이지이다.

// document.addEventListener('DOMContentLoaded', () => {
//   //alert(1); // test

//   const btn_member = document.querySelector('#btn_member'); //약관에서 "회원가입" 버튼

//   btn_member.addEventListener('click', () => {
//     //클릭했을때 할일(체크여부 확인!)
//     const chk_member1 = document.querySelector('#chk_member1'); //회원약관
//     if (chk_member1.checked !== true) {
//       alert('회원 약관에 동의해 주셔야 가입이 가능합니다.');
//       return false;
//     }

//     const chk_member2 = document.querySelector('#chk_member2'); //개인정보 취급방침
//     if (chk_member2.checked !== true) {
//       alert('개인정보 취급방침에 동의해 주셔야 가입이 가능합니다.');
//       return false;
//     }


//     // stipulation_form는 숨겨진 <form>태그의 name="stipulation_form"값이다.
//     const f = document.stipulation_form; // <form>태그의 name값이 stipulation_form 으로 정의!


//     f.chk.value = 1; // 숨겨져있는 <input>태그의 name값이 name="chk" 으로 정의!

//     f.submit(); // form name=stipulation_form 인곳(stipulation.php)을 찾는다. 그곳에서 action="member_input.php"을 만나서 이동시킨다.
//   });



// });



document.addEventListener('DOMContentLoaded', function() {
  
  // 회원가입 버튼 클릭 이벤트
  const btnMember = document.getElementById('btn_member');
  if (btnMember) {
    btnMember.addEventListener('click', function(e) {
      e.preventDefault();
      
      // 체크박스 확인
      const chk1 = document.getElementById('chk_member1');
      const chk2 = document.getElementById('chk_member2');
      
      if (!chk1.checked) {
        alert('회원 약관에 동의해주세요.');
        chk1.focus();
        return false;
      }
      
      if (!chk2.checked) {
        alert('개인정보 취급방침에 동의해주세요.');
        chk2.focus();
        return false;
      }
      
      // 모두 동의한 경우
      const form = document.stipulation_form;
      form.chk.value = '1'; // 약관 동의 완료 표시
      form.submit();
    });
  }
  
  // 가입취소 버튼 클릭 이벤트
  const btnMemberDel = document.getElementById('btn_member_del');
  if (btnMemberDel) {
    btnMemberDel.addEventListener('click', function(e) {
      e.preventDefault();
      
      if (confirm('회원가입을 취소하시겠습니까?')) {
        location.href = './login.php';  // ⭐ 이 부분 수정
      }
    });
  }
  
});