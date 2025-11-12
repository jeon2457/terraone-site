// pass.js - 비밀번호 검증 스크립트
function checkPassword() {
  const correctPassword = '77777'; // ✅ 설정한 비밀번호
  const userInput = prompt('비밀번호를 입력하세요:'); // 사용자 입력 받기

  if (userInput === correctPassword) {
    //alert('비밀번호가 맞습니다. 확인버튼을 클릭하세요. ^^');
    window.location.href =
      'https://docs.google.com/spreadsheets/d/e/2PACX-1vTGhqELrLq35IHnMsXOk5QiEB3j9vQc7mJCSycngGTSYzZbQJ4M9RkMupUPUaTzS8ut2HsdC3IjEhhx/pubhtml';
    return true; // ✅ 이동 허용
  } else {
    alert('비밀번호가 틀렸습니다. 다시 시도하세요.');
    return false; // ❌ 이동 차단
  }
}
