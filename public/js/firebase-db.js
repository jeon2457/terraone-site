// 🔹 firebase-db.js (수정 버전)
import { app } from "./firebase-config.js";
import { getDatabase, ref, set, push, get, child, update, remove } 
  from "https://www.gstatic.com/firebasejs/10.6.0/firebase-database.js";


const db = getDatabase(app);
const membersRef = ref(db, "terraone/tel");

// ✅ 회원 등록 함수
export function addMember(member) {
  push(membersRef, member)
    .then(() => console.log("✅ 새 회원 등록 완료"))
    .catch(err => console.error("❌ 등록 실패:", err));
}

// ✅ 전체 회원 가져오기
export async function getAllMembers() {
  const snapshot = await get(membersRef);
  if (snapshot.exists()) {
    return snapshot.val();
  } else {
    return {};
  }
}

// ✅ 회원 수정
export function updateMember(memberKey, updatedData) {
  const memberRef = ref(db, `terraone/tel/${memberKey}`);
  update(memberRef, updatedData)
    .then(() => console.log("✅ 회원 수정 완료"))
    .catch(err => console.error("❌ 수정 실패:", err));
}

// ✅ 회원 삭제
export function deleteMember(memberKey) {
  const memberRef = ref(db, `terraone/tel/${memberKey}`);
  remove(memberRef)
    .then(() => console.log("✅ 회원 삭제 완료"))
    .catch(err => console.error("❌ 삭제 실패:", err));
}

// ✅ 로그인 함수
export async function login(id, password) {
  const allMembers = await getAllMembers();
  for (const key in allMembers) {
    const member = allMembers[key];
    if (member.id === id && member.password === password) {
      return { ...member, key };
    }
  }
  return null; // 로그인 실패
}
