// js/firebase-config.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.9.0/firebase-app.js";

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

// 앱 초기화 및 내보내기
export const app = initializeApp(firebaseConfig);