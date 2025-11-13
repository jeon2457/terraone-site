// members.js
// 🔹 Firebase DB 함수 import
import { getAllMembers, deleteMember } from "./firebase-db.js";

// 🔹 테이블 요소 및 버튼 선택
const tbody = document.querySelector("#membersTable tbody");
const selectAllCheckbox = document.querySelector("#selectAll");
const editBtn = document.querySelector("#editSelected");
const deleteBtn = document.querySelector("#deleteSelected");
const messageDiv = document.querySelector("#message");

// 🔹 회원 목록 렌더링
async function renderMembers() {
  tbody.innerHTML = "";
  const membersData = await getAllMembers();

  if (!membersData || Object.keys(membersData).length === 0) {
    tbody.innerHTML = `<tr><td colspan="10" class="text-center">등록된 회원이 없습니다.</td></tr>`;
    return;
  }

  // 레벨 숫자를 문자열로 매핑
  const levelText = { 1: "회원", 2: "회원+", 10: "관리자" };

  let idx = 1;
  for (const key in membersData) {
    const m = membersData[key];
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td><input type="checkbox" class="selectMember" data-key="${key}"></td>
      <td>${idx}</td>
      <td>${m.id || ""}</td>
      <td>${m.name || ""}</td>
      <td>${m.tel || ""}</td>
      <td>${m.addr || ""}</td>
      <td>${m.remark || ""}</td>
      <td>${m.sms || ""}</td>
      <td>${Array.isArray(m.sms_2) ? m.sms_2.join(", ") : m.sms_2 || ""}</td>
      <td>${levelText[m.level] || "회원"}</td>
    `;
    tbody.appendChild(tr);
    idx++;
  }
}

// 🔹 전체 선택 체크박스
selectAllCheckbox.addEventListener("change", () => {
  const checkboxes = document.querySelectorAll(".selectMember");
  checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
});

// 🔹 선택 회원 수정 (edit-member.html로 이동)
editBtn.addEventListener("click", () => {
  const selectedKeys = Array.from(document.querySelectorAll(".selectMember:checked"))
    .map(cb => cb.dataset.key);

  if (selectedKeys.length === 0) {
    alert("수정할 회원을 선택해주세요.");
    return;
  }
  if (selectedKeys.length > 1) {
    alert("한 번에 한 명만 수정 가능합니다.");
    return;
  }

  const key = selectedKeys[0];
  window.location.href = `edit-member.html?key=${key}`;
});

// 🔹 선택 회원 삭제
deleteBtn.addEventListener("click", async () => {
  const selectedKeys = Array.from(document.querySelectorAll(".selectMember:checked"))
    .map(cb => cb.dataset.key);

  if (selectedKeys.length === 0) {
    alert("삭제할 회원을 선택해주세요.");
    return;
  }

  if (!confirm("선택한 회원을 삭제하시겠습니까?")) return;

  for (const key of selectedKeys) {
    await deleteMember(key);
  }

  messageDiv.textContent = "✅ 선택 회원 삭제 완료!";
  renderMembers();
});

// 🔹 페이지 로드 시 회원 목록 표시
window.addEventListener("DOMContentLoaded", renderMembers);
