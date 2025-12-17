// js/auth.js
// 📂 DB 기반 로그인 전용 권한 관리 모듈

// ✅ [1] 관리자 권한 체크
export function requireAdmin() {
  // 브라우저에 저장된 로그인 정보 가져오기
  const userJson = sessionStorage.getItem("currentUser");
  
  if (!userJson) {
    alert("❌ 로그인이 필요한 페이지입니다.");
    window.location.href = "login.html";
    return;
  }

  const user = JSON.parse(userJson);

  // 레벨 확인 (관리자는 10)
  if (parseInt(user.level) < 10) {
    alert("❌ 관리자만 접근할 수 있습니다.");
    window.location.href = "login.html"; // 또는 일반 회원 페이지
    return;
  }

  console.log(`✅ 인증 확인됨: ${user.name}(${user.id})`);
  
  // 상단 헤더에 정보 표시
  const userInfo = document.getElementById("userInfo");
  if (userInfo) {
    userInfo.textContent = `👋 ${user.name}님 (관리자)`;
  }
}

// ✅ [2] 로그인 페이지 접근 체크 (이미 로그인했으면 메인으로)
export function requireGuest() {
  const userJson = sessionStorage.getItem("currentUser");
  if (userJson) {
    // 이미 로그인 된 상태
    window.location.href = "members.html"; 
  }
}

// ✅ [3] 로그아웃
export function logout() {
  // 저장된 정보 삭제
  sessionStorage.removeItem("currentUser");
  alert("로그아웃 되었습니다.");
  window.location.href = "login.html";
}

// ✅ [4] 현재 사용자 정보 가져오기
export function getCurrentUser() {
  const userJson = sessionStorage.getItem("currentUser");
  return userJson ? JSON.parse(userJson) : null;
}