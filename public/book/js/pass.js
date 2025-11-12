


function checkPassword() {
  const correctPassword = '77777'; // 설정된 비밀번호
  let userInput = '';

  while (userInput !== correctPassword) {
    userInput = prompt('비밀번호를 입력하세요:');
    if (userInput === null) {
      alert('취소되었습니다.');
      return false;
    } else if (userInput.trim() === '') {
      alert('비밀번호를 입력해야합니다.');
    } else if (userInput !== correctPassword) {
      alert('비밀번호가 틀렸습니다. 다시 시도하세요.');
    }
  }

  // 비밀번호 맞으면 새 창으로 열기
  window.open(
    'https://docs.google.com/spreadsheets/d/e/2PACX-1vSGfU7cZiYmJe_kHfmsdUu5EFlOJCF5BL5BcAsI0c8Xq8ei5gWxXLsWY6SB8qGBys3ve3pK4mmpXS7a/pubhtml',
    '_blank'
  );
  return false; // a태그 기본 동작 방지
}





