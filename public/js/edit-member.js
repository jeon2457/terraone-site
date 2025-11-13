// edit-member.js
import { getDatabase, ref, get, update } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-database.js";
import { app } from "./firebase-config.js";
import { requireAdmin } from "./auth.js";

// 🔥 관리자 권한 체크
if (!requireAdmin()) {
  // requireAdmin()에서 이미 리다이렉트 처리됨
}

const db = getDatabase(app);

// URL에서 key 가져오기
const urlParams = new URLSearchParams(window.location.search);
const memberKey = urlParams.get("key");

// 폼 요소
const nameInput = document.getElementById("name");
const telInput = document.getElementById("tel");
const addrInput = document.getElementById("addr");
const remarkInput = document.getElementById("remark");
const smsInput = document.getElementById("sms");
const sms2Input = document.getElementById("sms2");
const levelSelect = document.getElementById("level");
const form = document.getElementById("editForm");
const messageDiv = document.getElementById("message");

// 🔹 기존 회원 데이터 불러오기
async function loadMember() {
  const memberRef = ref(db, `terraone/tel/${memberKey}`);
  const snapshot = await get(memberRef);
  if (snapshot.exists()) {
    const m = snapshot.val();
    nameInput.value = m.name || "";
    telInput.value = m.tel || "";
    addrInput.value = m.addr || "";
    remarkInput.value = m.remark || "";
    smsInput.value = m.sms || "";
    sms2Input.value = m.sms_2 || "";
    levelSelect.value = m.level || "1";
  } else {
    messageDiv.textContent = "⚠️ 회원 데이터를 찾을 수 없습니다.";
  }
}

// 🔹 폼 제출 시 업데이트
form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const updatedData = {
    name: nameInput.value,
    tel: telInput.value,
    addr: addrInput.value,
    remark: remarkInput.value,
    sms: smsInput.value,
    sms_2: sms2Input.value,
    level: parseInt(levelSelect.value)
  };

  const memberRef = ref(db, `terraone/tel/${memberKey}`);
  try {
    await update(memberRef, updatedData);
    messageDiv.textContent = "✅ 회원 수정 완료!";
    setTimeout(() => {
      window.location.href = "members.html";
    }, 1500);
  } catch (err) {
    console.error(err);
    messageDiv.textContent = "❌ 수정 실패!";
  }
});

loadMember();