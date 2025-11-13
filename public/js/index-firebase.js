// 📁 public/js/index-firebase.js
// index.html에서 Firebase 데이터를 불러와 동적으로 테이블 생성

import { getAllMembers } from "./firebase-db.js";

const tbody = document.getElementById("memberTableBody");

// 🔹 SMS 전송 함수 (기존 방식 유지)
window.sendSMS = function(event, phoneNumbers) {
  event.preventDefault();
  
  // iOS와 Android에서 모두 작동하도록 처리
  const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
  const smsUrl = isIOS ? `sms:${phoneNumbers}&body=` : `sms:${phoneNumbers}?body=`;
  
  window.location.href = smsUrl;
};

// 🔹 회원 목록 렌더링
async function renderMembers() {
  console.log("🔍 Firebase에서 회원 데이터 로딩 시작...");
  
  try {
    const membersData = await getAllMembers();
    console.log("📦 불러온 데이터:", membersData);

    if (!membersData || Object.keys(membersData).length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="text-center">등록된 회원이 없습니다.</td>
        </tr>
      `;
      return;
    }

    // 🔥 회원 데이터를 배열로 변환하고 정렬
    const membersArray = Object.entries(membersData).map(([key, member]) => ({
      key,
      ...member
    }));

    // 이름순 정렬 (선택사항)
    membersArray.sort((a, b) => {
      if (a.name < b.name) return -1;
      if (a.name > b.name) return 1;
      return 0;
    });

    // 🔥 전체 전화번호 목록 생성 (회장/총무 SMS 발송용)
    const allPhoneNumbers = membersArray
      .filter(m => m.tel)
      .map(m => m.tel)
      .join(',');

    // 🔥 회장과 총무 찾기
    const president = membersArray.find(m => m.remark === "회장");
    const treasurer = membersArray.find(m => m.remark === "총무");

    tbody.innerHTML = ""; // 기존 내용 지우기

    // 🔥 회원 목록 생성
    membersArray.forEach((member, index) => {
      const tr = document.createElement("tr");
      
      // 회장이나 총무인 경우 특별 처리
      const isPresident = member.remark === "회장";
      const isTreasurer = member.remark === "총무";
      
      tr.innerHTML = `
        <td class="no_1">${index + 1}</td>
        <td class="name_1">
          <a href="tel:${member.tel}"><span>${member.name || ""}</span></a>
        </td>
        <td class="tel_1">
          <a href="tel:${member.tel}"><span>${member.tel || ""}</span></a>
        </td>
        <td class="address_1">
          ${(isPresident || isTreasurer) 
            ? `<a href="sms:${allPhoneNumbers}" onclick="sendSMS(event,'${allPhoneNumbers}')">
                 <span>${member.addr || ""}</span>
               </a>`
            : `<span>${member.addr || ""}</span>`
          }
        </td>
        <td class="remark_1">
          ${(isPresident || isTreasurer) 
            ? `<a href="sms:${allPhoneNumbers}" onclick="sendSMS(event,'${allPhoneNumbers}')">
                 <span>${member.remark || "&nbsp;"}</span>
               </a>`
            : `<span>${member.remark || "&nbsp;"}</span>`
          }
        </td>
        <td class="sms_1">
          <a href="sms:${member.tel}" onclick="sendSMS(event,'${member.tel}')">
            <span><img class="max-small" src="image/sms-4.png" /></span>
          </a>
        </td>
      `;
      
      tbody.appendChild(tr);
    });

    console.log("✅ 회원 목록 렌더링 완료!");
  } catch (error) {
    console.error("❌ 데이터 로드 실패:", error);
    tbody.innerHTML = `
      <tr>
        <td colspan="6" class="text-center text-danger">
          데이터 로드 실패: ${error.message}
        </td>
      </tr>
    `;
  }
}

// 🔹 페이지 로드 시 회원 목록 표시
console.log("📄 index.html 페이지 로드됨");
window.addEventListener("DOMContentLoaded", () => {
  console.log("🚀 DOMContentLoaded 이벤트 발생");
  renderMembers();
});

// 🔥 실시간 업데이트를 위한 추가 옵션 (선택사항)
// Firebase Realtime Database의 변경사항을 실시간으로 감지하려면:
/*
import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-database.js";
import { app } from "./firebase-config.js";

const db = getDatabase(app);
const membersRef = ref(db, "terraone/tel");

onValue(membersRef, (snapshot) => {
  console.log("🔥 Firebase 데이터 변경 감지!");
  renderMembers();
});
*/