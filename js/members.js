// js/members.js
// 버전 통일: 10.9.0
import { getDatabase, ref, onValue, remove } from "https://www.gstatic.com/firebasejs/10.9.0/firebase-database.js";
import { app } from "./firebase-config.js";
import { requireAdmin } from "./auth.js";

console.log("🚀 [Debug] members.js 시작 (버전 10.9.0)");

// 1. 관리자 권한 체크
requireAdmin();

const db = getDatabase(app);
const membersRef = ref(db, "terraone/tel");

const tbody = document.querySelector("#membersTable tbody");
const selectAllCheckbox = document.getElementById("selectAll");
const messageDiv = document.getElementById("message");

// ============================================================
// 🔥 데이터 불러오기
// ============================================================
onValue(membersRef, (snapshot) => {
  tbody.innerHTML = "";
  selectAllCheckbox.checked = false;

  if (snapshot.exists()) {
    const data = snapshot.val();
    console.log("📦 [Debug] 데이터 수신 성공:", Object.keys(data).length + "명");

    let membersList = [];
    Object.keys(data).forEach((key) => {
      membersList.push({
        key: key,
        ...data[key]
      });
    });

    // 가나다순 정렬
    membersList.sort((a, b) => {
      const nameA = a.name ? String(a.name) : "";
      const nameB = b.name ? String(b.name) : "";
      return nameA.localeCompare(nameB, "ko-KR");
    });

    // 화면 출력
    membersList.forEach((member, index) => {
      const tr = document.createElement("tr");
      const sms2Value = member.sms_2 ? member.sms_2 : "-";
      
      tr.innerHTML = `
        <td><input type="checkbox" class="member-check" value="${member.key}"></td>
        <td>${index + 1}</td>
        <td>${member.id || "-"}</td>
        <td class="fw-bold">${member.name || "-"}</td>
        <td>${member.tel || "-"}</td>
        <td>${member.addr || "-"}</td>
        <td>${member.remark || "-"}</td>
        <td>${member.sms || "-"}</td>
        <td style="font-size: 0.85rem; color: #666; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${sms2Value}">
            ${sms2Value}
        </td>
        <td>
          <span class="badge ${getBadgeClass(member.level)}">
            ${member.level || "1"}
          </span>
        </td>
      `;
      tbody.appendChild(tr);
    });

  } else {
    tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4">등록된 회원이 없습니다.</td></tr>`;
  }
}, (error) => {
  console.error("❌ 데이터 읽기 오류:", error);
  messageDiv.innerHTML = `<div class="alert alert-danger">데이터 로드 실패: ${error.message}</div>`;
});

function getBadgeClass(level) {
  if (level == 10) return "text-bg-danger";
  if (level == 2) return "text-bg-success";
  return "text-bg-secondary";
}

// ============================================================
// ✅ 버튼 기능
// ============================================================
selectAllCheckbox.addEventListener("change", (e) => {
  document.querySelectorAll(".member-check").forEach((cb) => (cb.checked = e.target.checked));
});

// 2. 회원가입 버튼
document.getElementById("signupSelected").addEventListener("click", () => window.location.href = "signup.html");

// 3. [수정됨] 회원열람 버튼 (체크박스 상관없이 index.html로 이동)
document.getElementById("viewSelected").addEventListener("click", () => {
  window.location.href = "index.html";
});

// 4. 선택회원 수정 버튼
document.getElementById("editSelected").addEventListener("click", () => {
  const checked = document.querySelectorAll(".member-check:checked");
  if (checked.length !== 1) { alert("한 명만 선택해주세요."); return; }
  window.location.href = `edit-member.html?key=${checked[0].value}`;
});

// 5. 선택회원 삭제 버튼
document.getElementById("deleteSelected").addEventListener("click", async () => {
  const checked = document.querySelectorAll(".member-check:checked");
  if (checked.length === 0) { alert("삭제할 회원을 선택하세요."); return; }
  if (!confirm(`${checked.length}명을 삭제하시겠습니까?`)) return;

  try {
    const promises = [];
    checked.forEach((cb) => promises.push(remove(ref(db, `terraone/tel/${cb.value}`))));
    await Promise.all(promises);
    alert("✅ 삭제 완료!");
  } catch (err) {
    alert("오류: " + err.message);
  }
});