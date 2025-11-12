// firebase-db.js

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-app.js";
import { getDatabase, ref, set, get, child }
  from "https://www.gstatic.com/firebasejs/10.6.0/firebase-database.js";

// Firebase 설정 정보 (아까 복사한 부분)
const firebaseConfig = {
  apiKey: "AIzaSyAF7AD1d54k21-stmb0Hpg9OMEECvzFHpQ",
  authDomain: "terraone-d0318.firebaseapp.com",
  databaseURL: "https://terraone-d0318-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "terraone-d0318",
  storageBucket: "terraone-d0318.firebasestorage.app",
  messagingSenderId: "1082807340877",
  appId: "1:1082807340877:web:6e2b49c04562d800e87104",
  measurementId: "G-7HMJEV832S"
};

// Firebase 초기화
const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

// 🔹 테스트용 데이터 쓰기
set(ref(db, "test/hello"), { message: "안녕하세요" })
  .then(() => {
    console.log("✅ 데이터 저장 성공");
  })
  .catch((error) => {
    console.error("❌ 저장 실패:", error);
  });

// 🔹 데이터 읽기
get(child(ref(db), "test/hello"))
  .then((snapshot) => {
    if (snapshot.exists()) {
      console.log("📦 데이터:", snapshot.val());
    } else {
      console.log("⚠️ 데이터가 없습니다.");
    }
  })
  .catch((error) => {
    console.error("❌ 읽기 오류:", error);
  });



// 1️⃣ firebase-config.js에서 app 가져오기
import { app } from "./firebase-config.js";
// 2️⃣ Firebase Database 관련 함수 import
import { getDatabase, ref, set, push } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-database.js";



//const db = getDatabase(app);
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