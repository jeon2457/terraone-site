// 📁 js/image-upload.js
import { storage, ref, uploadBytes, getDownloadURL } from "./firebase-config.js";

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
