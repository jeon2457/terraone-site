// 🔹 firebase-db.js (완전 버전)
import { app } from "./firebase-config.js";
import { getDatabase, ref, set, push, get, child, update, remove } 
  from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";  // 10.6.0 → 10.7.1

const db = getDatabase(app);
const membersRef = ref(db, "terraone/tel");


// ✅ 회원 등록 함수
export function addMember(member) {
  return push(membersRef, member)
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

// ✅ 로그인 함수 (누락되어 있던 부분!)
export async function login(id, password) {
  const snapshot = await get(membersRef);
  if (!snapshot.exists()) {
    return null;
  }

  const members = snapshot.val();
  
  // 모든 회원을 순회하면서 id와 password 일치 확인
  for (const key in members) {
    const member = members[key];
    if (member.id === id && member.password === password) {
      return { ...member, key }; // 회원 정보와 key 반환
    }
  }
  
  return null; // 일치하는 회원 없음
}

// ✅ 회원 삭제 함수
export async function deleteMember(key) {
  const memberRef = ref(db, `terraone/tel/${key}`);
  return remove(memberRef)
    .then(() => console.log("✅ 회원 삭제 완료"))
    .catch(err => console.error("❌ 삭제 실패:", err));
}

// ✅ 회원 수정 함수
export async function updateMember(key, updatedData) {
  const memberRef = ref(db, `terraone/tel/${key}`);
  return update(memberRef, updatedData)
    .then(() => console.log("✅ 회원 수정 완료"))
    .catch(err => console.error("❌ 수정 실패:", err));
}



// 🔹 수입/지출 등록 함수
export function addTransaction(type, data) {
  const tableName = type === 'income' ? 'income_table' : 'expense_table';
  const tableRef = ref(db, tableName);
  return push(tableRef, {
    ...data,
    timestamp: Date.now()
  })
    .then(() => console.log(`✅ ${type} 등록 완료`))
    .catch(err => console.error(`❌ ${type} 등록 실패:`, err));
}