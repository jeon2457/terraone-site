// 📁 public/js/index-firebase.js
// index.html용 Firebase 데이터 연동 (버전 10.9.0)
// 번호, 이름, 전화번호, 주소, 비고, SMS 데이터만 표시

import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.9.0/firebase-database.js";
import { app } from "./firebase-config.js";

const db = getDatabase(app);
const membersRef = ref(db, "terraone/tel");
const tbody = document.getElementById("memberTableBody");

console.log("🚀 [Debug] index-firebase.js 시작 (버전 10.9.0)");

// 🔹 SMS 전송 함수 (iOS/Android 호환)
window.sendSMS = function(event, phoneNumbers) {
  event.preventDefault(); // 기본 링크 이동 막기
  
  if (!phoneNumbers || String(phoneNumbers).trim() === "") {
    alert("전송할 전화번호가 없습니다.");
    return;
  }

  // iOS와 Android 구분 처리
  const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
  const smsUrl = isIOS ? `sms:${phoneNumbers}&body=` : `sms:${phoneNumbers}?body=`;
  
  window.location.href = smsUrl;
};

// 🔹 데이터 불러오기 (실시간 감지)
onValue(membersRef, (snapshot) => {
  tbody.innerHTML = ""; // 기존 목록 초기화

  if (snapshot.exists()) {
    const data = snapshot.val();
    const totalCount = Object.keys(data).length;
    console.log(`📦 [Debug] 데이터 수신 성공: 총 ${totalCount}명`);

    // 1. 객체를 배열로 변환
    let membersList = [];
    Object.keys(data).forEach((key) => {
      membersList.push({
        key: key,
        ...data[key]
      });
    });

    // 2. 이름 기준 가나다순 정렬
    membersList.sort((a, b) => {
      const nameA = a.name ? String(a.name) : "";
      const nameB = b.name ? String(b.name) : "";
      return nameA.localeCompare(nameB, "ko-KR");
    });

    // 3. 화면에 출력
    membersList.forEach((member, index) => {
      const tr = document.createElement("tr");
      
      // ✅ [수정 핵심] sms_2 데이터를 안전하게 문자열로 변환
      // DB에 숫자로 저장되어 있어도 에러가 나지 않게 String()으로 감쌈
      const sms2Raw = member.sms_2 ? String(member.sms_2) : "";
      const sms2Value = sms2Raw.trim(); 

      // 회장/총무 여부
      const isPresidentOrTreasurer = (member.remark === "회장" || member.remark === "총무");
      
      // 단체 문자 대상 번호 결정 (sms_2가 있으면 그것, 없으면 본인 번호)
      const bulkSmsTarget = (sms2Value !== "") ? sms2Value : member.tel;

      // 주소/비고란 링크 처리
      const addressContent = isPresidentOrTreasurer 
        ? `<a href="#" onclick="sendSMS(event, '${bulkSmsTarget}')" style="color: inherit; text-decoration: none;">${member.addr || ""}</a>`
        : `<span>${member.addr || ""}</span>`;

      const remarkContent = isPresidentOrTreasurer
        ? `<a href="#" onclick="sendSMS(event, '${bulkSmsTarget}')" style="color: inherit; text-decoration: none;">${member.remark || "&nbsp;"}</a>`
        : `<span>${member.remark || "&nbsp;"}</span>`;

      // SMS 아이콘 타겟
      const smsTarget = (sms2Value !== "") ? sms2Value : member.tel;

      tr.innerHTML = `
        <td class="no_1">${index + 1}</td>
        <td class="name_1">
          <a href="tel:${member.tel || ''}"><span>${member.name || ""}</span></a>
        </td>
        <td class="tel_1">
          <a href="tel:${member.tel || ''}"><span>${member.tel || ""}</span></a>
        </td>
        <td class="address_1">
          ${addressContent}
        </td>
        <td class="remark_1">
          ${remarkContent}
        </td>
        <td class="sms_1">
          <a href="#" onclick="sendSMS(event, '${smsTarget}')">
            <span><img class="max-small" src="image/sms-4.png" alt="문자" /></span>
          </a>
        </td>
      `;
      
      tbody.appendChild(tr);
    });

    console.log("✅ 모든 회원 렌더링 완료");

  } else {
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4">등록된 회원이 없습니다.</td></tr>`;
  }
}, (error) => {
  console.error("❌ 데이터 읽기 오류:", error);
  tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">데이터 로드 실패: ${error.message}</td></tr>`;
});



// 🔥 실시간 업데이트 옵션 (선택사항)
// members.html에서 수정/삭제 시 자동으로 index.html 업데이트
// 아래 주석을 해제하면 실시간 동기화 활성화됩니다.
/*
import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-database.js";
import { app } from "./firebase-config.js";

const db = getDatabase(app);
const membersRef = ref(db, "terraone/tel");

onValue(membersRef, (snapshot) => {
  console.log("🔥 Firebase 데이터 변경 감지! 자동 새로고침...");
  renderMembers();
});
*/

// 🔥 실시간 업데이트 옵션 (선택사항)
// members.html에서 수정/삭제 시 자동으로 index.html 업데이트
// 아래 주석을 해제하면 실시간 동기화 활성화됩니다.
/*
import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-database.js";
import { app } from "./firebase-config.js";

const db = getDatabase(app);
const membersRef = ref(db, "terraone/tel");

onValue(membersRef, (snapshot) => {
  console.log("🔥 Firebase 데이터 변경 감지! 자동 새로고침...");
  renderMembers();
});
*/