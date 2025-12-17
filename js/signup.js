// signup.js
import { addMember } from "./firebase-db.js";

/* =========================
   🔐 비밀번호 SHA-256 암호화
   ========================= */
async function hashPassword(password) {
  const encoder = new TextEncoder();
  const data = encoder.encode(password);
  const hashBuffer = await crypto.subtle.digest("SHA-256", data);
  const hashArray = Array.from(new Uint8Array(hashBuffer));
  return hashArray.map(b => b.toString(16).padStart(2, "0")).join("");
}

const form = document.getElementById("signupForm");
const messageDiv = document.getElementById("message");

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  /* =========================
     ✅ 필수 입력값 검증
     ========================= */
  const requiredFields = [
    "id",
    "password",
    "name",
    "tel",
    "addr",
    "remark",
    "level"
  ];

  for (const fieldId of requiredFields) {
    const field = document.getElementById(fieldId);
    if (!field || !field.value.trim()) {
      field.focus();
      messageDiv.textContent = "❌ 필수 항목을 모두 입력하세요.";
      messageDiv.className = "error";
      return; // 🔴 여기서 중단
    }
  }

  /* =========================
     📱 전화번호 검증
     ========================= */
  const telInput = document.getElementById("tel").value.trim();
  const telNumbers = telInput.replace(/\D/g, "");

  if (telNumbers.length !== 11) {
    messageDiv.textContent = "❌ 전화번호는 11자리 숫자여야 합니다.";
    messageDiv.className = "error";
    document.getElementById("tel").focus();
    return;
  }

  /* =========================
     🔐 비밀번호 암호화
     ========================= */
  const rawPassword = document.getElementById("password").value.trim();
  const hashedPassword = await hashPassword(rawPassword);

  /* =========================
     📦 회원 객체 생성
     ========================= */
  const member = {
    id: document.getElementById("id").value.trim(),
    password: hashedPassword, // 🔐 암호화된 비밀번호
    name: document.getElementById("name").value.trim(),
    tel: telInput,
    addr: document.getElementById("addr").value.trim(),
    remark: document.getElementById("remark").value.trim(),
    sms: document.getElementById("sms").value.trim(),
    sms_2: document.getElementById("sms_2").value
      ? document.getElementById("sms_2").value
          .split(",")
          .map(s => s.trim())
      : [],
    level: parseInt(document.getElementById("level").value, 10),
    createdAt: new Date().toISOString()
  };

  /* =========================
     🚀 Firebase DB 저장
     ========================= */
  try {
    await addMember(member);
    messageDiv.textContent = "✅ 회원가입 완료!";
    messageDiv.className = "success";
    form.reset();
  } catch (err) {
    console.error(err);
    messageDiv.textContent = "❌ 회원가입 중 오류가 발생했습니다.";
    messageDiv.className = "error";
  }
});
