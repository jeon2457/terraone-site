// login.js
import { login } from "./firebase-db.js";

const form = document.getElementById("loginForm");
const messageDiv = document.getElementById("message");

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const id = document.getElementById("id").value;
  const password = document.getElementById("password").value;

  const member = await login(id, password);

  if (!member) {
    messageDiv.textContent = "❌ 아이디 또는 비밀번호가 잘못되었습니다.";
    return;
  }

  messageDiv.textContent = "✅ 로그인 성공!";

  // 🔥 세션에 로그인 정보 저장
  sessionStorage.setItem("loggedInUser", JSON.stringify({
    key: member.key,
    id: member.id,
    name: member.name,
    level: member.level
  }));

  // level 값에 따라 페이지 이동
  if (member.level === 10) {
    // 🔥 원래 가려던 페이지가 있으면 그곳으로, 없으면 members.html로
    const redirectUrl = sessionStorage.getItem("redirectAfterLogin");
    
    if (redirectUrl) {
      sessionStorage.removeItem("redirectAfterLogin"); // 사용 후 삭제
      window.location.href = redirectUrl;
    } else {
      // 직접 login.html에 접근한 경우
      window.location.href = "members.html";
    }
  } else {
    // 일반 회원은 접근 불가
    alert("❌ 관리자만 접근할 수 있습니다.");
    sessionStorage.removeItem("loggedInUser"); // 세션 삭제
  }
});