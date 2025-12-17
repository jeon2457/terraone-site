<?php
session_start();
require './php/auth_check.php';   // 로그인 체크
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>메모전달 공간</title>

<!-- 파비콘 아이콘들 -->
<link rel="icon" href="/favicon.png?v=2" />
<link rel="icon" type="image/png" sizes="36x36" href="/favicons/android-icon-36x36.png" />
<link rel="icon" type="image/png" sizes="48x48" href="/favicons/android-icon-48x48.png" />
<link rel="icon" type="image/png" sizes="72x72" href="/favicons/android-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="32x32" href="/favicons/apple-icon-32x32.png">
<link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f4f6f9;
        font-size: 16px;
    }

    /* 🔹 메모 컨테이너 */
    .container {
        max-width: 700px;
        margin: 40px auto;
        padding: 30px;
        background: #fff;
        border-radius: 15px;
        border: 2px solid #007bff;
        box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
    }

    /* 🔹 메모 제목 */
    .memo-title {
        max-width: 450px;
        display: inline-block;
        background-color: #007bff;
        color: #fff;
        padding: 10px 26px;
        border-radius: 30px;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 25px;
    }

    /* 🔹 텍스트 영역 */
    textarea#memoBox {
        width: 100%;
        height: 600px;
        max-width: 100%;
        max-height: 600px;
        min-height: 400px;
        font-size: 15px;
        resize: none;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #ccc;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
    }

    /* 🔹 버튼 위치 */
    .tel_footer_btn{
        margin-top: 20px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    /* 🔹 팝업 배경 */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none; /* 기본 숨김 */
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    /* 🔹 팝업 박스 */
    .popup-box {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        max-width: 450px;
        width: 90%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        text-align: center;
    }

    .popup-box p {
        font-size: 16px;
        margin-bottom: 25px;
        color: #333;
    }
</style>
</head>
<body>

<div class="container text-center">

    <!-- 🔵 제목 라운드 버튼 -->
    <div class="memo-title mt-5">메모 전달 공간</div>

    
<!-- 📌[샘플용 메모] 비고(직책)란이 회장 또는 총무일경우에만  살고있는 '거주지(addr)'항목인  자신들의 자리에 sms_2에 저장된 데이타를 가지고있는데 링크가 걸려있고 클릭하면 tel_sms_send.php로 넘겨서 이곳에서 단체문자메세지를 전송할 수가있다.  -->


    <!-- 메모 입력창 tel_input.php 메모박스로 전송 -->
    <form id="memoForm" action="tel_memo_save.php" method="POST">
        <textarea name="memo" id="memoBox" placeholder='[주의!] 문자내용 없이 그냥 [보내기] 버튼을 누르면 tel_input.php 안내창의 문자가 다 날아가니 업데이트가 필요할때는 문자를 여기서 새로 입력한후에 [보내기] 버튼을 누르면 됩니다.'><?php echo htmlspecialchars($_SESSION['temp_memo'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

        <div class="tel_footer_btn">
            <!-- 보내기 버튼 -->
            <button type="submit" class="btn btn-primary px-4 py-2">보내기</button>
            <a href="tel_update.php" class="btn btn-secondary px-4">돌아가기</a>
        </div>
    </form>

</div>

<!-- 🔹 팝업 영역 -->
<div class="popup-overlay" id="popupOverlay">
    <div class="popup-box">
        <p>[알림!] 여기서 전송하면 기존의 문자는 삭제되고, 지금 입력한 문자로 업데이트 됩니다.</p>
        <button id="confirmSend" class="btn btn-primary px-4 me-2">전송</button>
        <button id="cancelSend" class="btn btn-secondary px-4">취소</button>
    </div>
</div>

<script>
const form = document.getElementById('memoForm');
const popup = document.getElementById('popupOverlay');
const confirmBtn = document.getElementById('confirmSend');
const cancelBtn = document.getElementById('cancelSend');

// 폼 제출 이벤트
form.addEventListener('submit', function(e) {
    e.preventDefault(); // 기본 제출 막기
    popup.style.display = 'flex'; // 팝업 표시
});

// 전송 버튼 클릭
confirmBtn.addEventListener('click', function() {
    form.submit(); // 실제 전송
});

// 취소 버튼 클릭
cancelBtn.addEventListener('click', function() {
    popup.style.display = 'none'; // 팝업 숨김
});
</script>

</body>
</html>



<!-- 
👉 간편한 이모지 아이콘 모음들

✅ 일반 강조 / 안내용
• 	👉 : 포인트 강조
• 	✅ : 완료, 승인
• 	📌 : 고정, 중요
• 	🔍 : 검색, 확인
• 	📝 : 작성, 기록
• 	📎 : 첨부, 연결

⚠️ 주의 / 경고 / 위험
• 	⚠️ : 일반적인 주의
• 	❗ : 강한 경고
• 	🚫 : 금지
• 	🔒 : 보안, 잠금
• 	🛑 : 정지
• 	🔥 : 긴급, 이슈

🌟 중요 / 추천 / 핵심
- ⭐ : 추천
- 📣 : 알림
- 💡 : 아이디어
- 🎯 : 목표
- 🏆 : 우수, 성과
- 🧭 : 방향, 가이드

🙂 친근함 / 감정 표현
- 🙂 : 기본 미소
- 😄 : 활짝 웃음
- 🤝 : 협력, 약속
- 🙌 : 환영, 축하
- 👋 : 인사
- 💬 : 대화, 코멘트

🎨 디자인 / 창의 / 작업
- 🎨 : 디자인
- 🧑‍💻 : 개발자
- 🛠️ : 설정, 수정
- 🧠 : 아이디어
- 📐 : 설계
- 🖌️ : 꾸미기

필요하신 테마나 상황에 맞춰 더 확장해드릴 수도 있어요.
예를 들어 "모임 공지용", "앱 알림용", "관리자 패널용" 등으로 맞춤 세트도 가능해요.

 -->
