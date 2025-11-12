// signup.js
import { addMember } from "./firebase-db.js"; // DB 함수 import

const form = document.getElementById("signupForm");
const messageDiv = document.getElementById("message");

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const member = {
    id: document.getElementById("id").value.trim(),
    password: document.getElementById("password").value.trim(),
    name: document.getElementById("name").value.trim(),
    tel: document.getElementById("tel").value.trim(),
    addr: document.getElementById("addr").value.trim(),
    remark: document.getElementById("remark").value.trim(),
    sms: document.getElementById("sms").value.trim(),
    sms_2: document.getElementById("sms_2").value
      ? document.getElementById("sms_2").value.split(",").map(s => s.trim())
      : [],
    level: parseInt(document.getElementById("level").value, 10)
  };

  try {
    await addMember(member); // DB에 추가
    messageDiv.textContent = "✅ 회원가입 완료!";
    form.reset();
  } catch (err) {
    console.error(err);
    messageDiv.textContent = "❌ 회원가입 중 오류가 발생했습니다.";
  }
});
