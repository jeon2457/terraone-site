// 📁 public/js/firebase-config.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-analytics.js";
import { getDatabase, connectDatabaseEmulator } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-database.js";

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

const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

// 🔥 Emulator 연결 (로컬 테스트용)
const db = getDatabase(app);
if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
  connectDatabaseEmulator(db, "localhost", 9000);
  console.log("🔧 Firebase Emulator에 연결됨");
}

export { app };