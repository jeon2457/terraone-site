// members.js
import { getAllMembers, deleteMember } from "./firebase-db.js";
import { requireAdmin, getCurrentUser, logout } from "./auth.js";

// 🔥 관리자 권한 체크 (페이지 진입 시)
if (!requireAdmin()) {
  // requireAdmin()에서 이미 리다이렉트 처리됨
}

const tbody = document.querySelector("#membersTable tbody");
const selectAllCheckbox = document.querySelector("#selectAll");
const viewBtn = document.querySelector("#viewSelected");
const editBtn = document.querySelector("#editSelected");
const deleteBtn = document.querySelector("#deleteSelected");
const messageDiv = document.querySelector("#message");

// 🔹 요소 존재 확인
if (!tbody) {
  console.error("❌ tbody 요소를 찾을 수 없습니다!");
  alert("페이지 구조에 문제가 있습니다. HTML을 확인하세요.");
}

// 🔹 회원 목록 렌더링
async function renderMembers() {
  console.log("🔍 renderMembers 함수 시작");
  
  if (!tbody) {
    console.error("❌ tbody가 null입니다!");
    return;
  }
  
  tbody.innerHTML = "<tr><td colspan='10' class='text-center'>데이터 로딩 중...</td></tr>";
  
  try {
    const membersData = await getAllMembers();
    console.log("📦 불러온 데이터:", membersData);
    console.log("📊 데이터 개수:", Object.keys(membersData).length);

    if (!membersData || Object.keys(membersData).length === 0) {
      tbody.innerHTML = `<tr><td colspan="10" class="text-center">등록된 회원이 없습니다.</td></tr>`;
      return;
    }

    // 레벨 숫자를 문자열로 매핑
    const levelText = { 1: "회원", 2: "회원+", 10: "관리자" };

    tbody.innerHTML = ""; // 기존 내용 지우기
    let idx = 1;
    
    for (const key in membersData) {
      const m = membersData[key];
      console.log(`👤 회원 ${idx}:`, key, m);
      
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
    
    console.log("✅ 렌더링 완료!");
  } catch (error) {
    console.error("❌ 에러 발생:", error);
    tbody.innerHTML = `<tr><td colspan="10" class="text-center text-danger">데이터 로드 실패: ${error.message}</td></tr>`;
  }
}

// 🔹 전체 선택 체크박스
if (selectAllCheckbox) {
  selectAllCheckbox.addEventListener("change", () => {
    const checkboxes = document.querySelectorAll(".selectMember");
    checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
  });
}

// 🔹 선택 회원 수정
if (editBtn) {
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
}

// 🔹 선택 회원 삭제
if (deleteBtn) {
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

    if (messageDiv) {
      messageDiv.textContent = "✅ 선택 회원 삭제 완료!";
    }
    renderMembers();
  });
}

// 🔹 페이지 로드 시 회원 목록 표시
console.log("📄 페이지 로드됨");
window.addEventListener("DOMContentLoaded", () => {
  console.log("🚀 DOMContentLoaded 이벤트 발생");
  
  // 🔥 로그인 사용자 정보 표시
  const user = getCurrentUser();
  if (user) {
    console.log(`👋 ${user.name}님 환영합니다! (Level: ${user.level})`);
  }
  
  renderMembers();
});


// 멤버전체목록 페이지(members.html) 회원열람 클릭시 이동
viewBtn.addEventListener("click", () => {
  window.location.href = "index.html";
});

