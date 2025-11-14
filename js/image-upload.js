// Firebase Storage 함수는 CDN에서 직접 가져와야 함
import {
  ref,
  uploadBytes,
  getDownloadURL
} from "https://www.gstatic.com/firebasejs/10.6.0/firebase-storage.js";

// firebase-config.js에서 storage 인스턴스만 받아옴
import { storage } from "./firebase-config.js";

const fileInput = document.getElementById("fileInput");
const uploadBtn = document.getElementById("uploadBtn");

uploadBtn.addEventListener("click", async () => {
  const file = fileInput.files[0];
  if (!file) {
    window.showUploadMessage("업로드할 파일을 선택하세요!", false);
    return;
  }

  const fileRef = ref(storage, "uploads/" + Date.now() + "_" + file.name);

  try {
    await uploadBytes(fileRef, file);
    const url = await getDownloadURL(fileRef);

    console.log("업로드 완료:", url);

    window.showUploadMessage("업로드 성공! 다운로드 URL: " + url, true);

  } catch (error) {
    console.error("업로드 실패:", error);
    window.showUploadMessage("업로드 중 오류 발생!", false);
  }
});
