// 📁 public/js/auth.js
// 권한 체크 공통 모듈

// ✅ 로그인 여부 확인
export function isLoggedIn() {
  const user = sessionStorage.getItem("loggedInUser");
  return user !== null;
}

// ✅ 현재 로그인한 사용자 정보 가져오기
export function getCurrentUser() {
  const user = sessionStorage.getItem("loggedInUser");
  return user ? JSON.parse(user) : null;
}

// ✅ 관리자 권한 확인 (level 10)
export function isAdmin() {
  const user = getCurrentUser();
  return user && user.level === 10;
}

// ✅ 로그아웃
export function logout() {
  sessionStorage.removeItem("loggedInUser");
  window.location.href = "login.html";
}

// ✅ 관리자 페이지 접근 체크 (관리자가 아니면 로그인 페이지로 이동)
export function requireAdmin() {
  if (!isLoggedIn()) {
    // 🔥 현재 페이지 URL 저장
    sessionStorage.setItem("redirectAfterLogin", window.location.pathname + window.location.search);
    
    alert("❌ 로그인이 필요합니다.");
    window.location.href = "login.html";
    return false;
  }
  
  if (!isAdmin()) {
    // 🔥 현재 페이지 URL 저장
    sessionStorage.setItem("redirectAfterLogin", window.location.pathname + window.location.search);
    
    alert("❌ 관리자만 접근할 수 있습니다.");
    window.location.href = "login.html";
    return false;
  }
  
  return true;
}

// ✅ 로그인 페이지 접근 체크 (이미 로그인했으면 members.html로 이동)
export function requireGuest() {
  if (isLoggedIn() && isAdmin()) {
    window.location.href = "members.html";
    return false;
  }
  return true;
}