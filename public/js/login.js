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

  // level 값에 따라 페이지 이동
  if (member.level === 10) {
    // 관리자 페이지
    window.location.href = "admin.html";
  } else {
    // 일반 회원 페이지
    window.location.href = "members.html";
  }
});
