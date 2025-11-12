import { addMember } from "./firebase-db.js";

document.getElementById("signupForm").addEventListener("submit", (e) => {
  e.preventDefault();

  const member = {
    id: document.getElementById("id").value,
    password: document.getElementById("password").value,
    name: document.getElementById("name").value,
    tel: document.getElementById("tel").value,
    addr: document.getElementById("addr").value,
    remark: document.getElementById("remark").value,
    sms: document.getElementById("sms").value,
    sms_2: document.getElementById("sms_2").value.split(","),
    level: 1
  };

  addMember(member);
  alert("회원가입 완료!");
  e.target.reset();
});
