// 📁 public/js/firebase-config.js
// Firebase 기본 설정 파일

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-analytics.js";
import { getDatabase, connectDatabaseEmulator } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-database.js";
import { getStorage } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-storage.js";

// Firebase 프로젝트 설정 정보
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

// Firebase 앱 초기화
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const db = getDatabase(app);
const storage = getStorage(app);

// 🔥 로컬 환경에서만 에뮬레이터 연결
if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
  console.log("🔧 로컬 환경 감지 - Firebase Emulator에 연결합니다.");
  connectDatabaseEmulator(db, "localhost", 9000);
} else {
  console.log("🌐 프로덕션 환경 - 실제 Firebase에 연결합니다.");
}

// export
export { app, db, storage };
