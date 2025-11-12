// 📁 public/js/firebase-config.js
// Firebase 기본 설정 파일

// Firebase SDK에서 필요한 함수 import
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-analytics.js";

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

// 다른 파일에서 Firebase 앱을 쓸 수 있도록 export
export { app };
