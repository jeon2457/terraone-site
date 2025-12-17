// edit-member.js
import { getDatabase, ref, get, update } from "https://www.gstatic.com/firebasejs/10.9.0/firebase-database.js";
import { app } from "./firebase-config.js";
import { requireAdmin } from "./auth.js";

/* 관리자 권한 체크 */
requireAdmin();

const db = getDatabase(app);

/* URL 파라미터 */
const urlParams = new URLSearchParams(window.location.search);
const memberKey = urlParams.get("key");

if (!memberKey) {
  alert("잘못된 접근입니다.");
  location.href = "members.html";
}

/* 폼 요소 */
const idInput = document.getElementById("userId");
const passwordInput = document.getElementById("password");
const nameInput = document.getElementById("name");
const telInput = document.getElementById("tel");
const addrInput = document.getElementById("addr");
const remarkInput = document.getElementById("remark");
const smsInput = document.getElementById("sms");
const sms2Input = document.getElementById("sms2");
const levelSelect = document.getElementById("level");
const form = document.getElementById("editForm");
const messageDiv = document.getElementById("message");

/* 기존 데이터 로드 */
async function loadMember() {
  const memberRef = ref(db, `terraone/tel/${memberKey}`);
  const snapshot = await get(memberRef);

  if (!snapshot.exists()) {
    alert("회원 정보를 찾을 수 없습니다.");
    location.href = "members.html";
    return;
  }

  const m = snapshot.val();
  idInput.value = m.id || "";
  nameInput.value = m.name || "";
  telInput.value = m.tel || "";
  addrInput.value = m.addr || "";
  remarkInput.value = m.remark || "";
  smsInput.value = m.sms || "";
  sms2Input.value = m.sms_2 || "";
  levelSelect.value = m.level || "1";
}

/* 저장 */
form.addEventListener("submit", async (e) => {
  e.preventDefault();
  messageDiv.innerHTML = `<div class="alert alert-info">저장 중...</div>`;

  const currentRemark = remarkInput.value.trim();

  // 🔥 핵심: 무조건 초기화
  let newSms2Value = "";

  try {
    if (currentRemark === "회장" || currentRemark === "총무") {
      const allRef = ref(db, "terraone/tel");
      const allSnap = await get(allRef);

      if (allSnap.exists()) {
        const allData = allSnap.val();
        const phoneList = [];

        Object.keys(allData).forEach(key => {
          if (key === memberKey) return;

          const m = allData[key];
          if (m.tel && m.tel.trim() !== "") {
            phoneList.push(m.tel.trim());
          }
        });

        newSms2Value = phoneList.join(",");
        sms2Input.value = newSms2Value;
      }
    } else {
      // 일반회원 → 완전 초기화
      sms2Input.value = "";
    }

  } catch (err) {
    messageDiv.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
    return;
  }

  const updateData = {
    name: nameInput.value,
    tel: telInput.value,
    addr: addrInput.value,
    remark: currentRemark,
    sms: smsInput.value,
    sms_2: newSms2Value,
    level: parseInt(levelSelect.value, 10)
  };

  if (passwordInput.value.trim() !== "") {
    updateData.password = passwordInput.value;
  }

  try {
    await update(ref(db, `terraone/tel/${memberKey}`), updateData);
    messageDiv.innerHTML = `<div class="alert alert-success">저장 완료</div>`;
    setTimeout(() => location.href = "members.html", 1200);
  } catch (err) {
    messageDiv.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
  }
});

/* 시작 */
loadMember();





// 👉 sms_2를 아예 없애고 “실시간 계산” 방식으로 바꾸는 구조가 더 
// 간결할수도 있다.

// 🔥 아주 중요한 팁 (강력 추천)

// 장기적으로는 더 안전한 구조:

// ❌ sms_2에 전화번호를 저장

// ✅ tel_sms_send.html에서 그때그때 전체 회원 조회

