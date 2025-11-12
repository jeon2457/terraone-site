let touchStartX = 0;
let touchEndX = 0;

function handleTouchStart(event) {
  touchStartX = event.touches[0].clientX;
}

function handleTouchEnd(event) {
  touchEndX = event.changedTouches[0].clientX;
  handleSwipe();
}

function handleSwipe() {
  const swipeThreshold = 50; // 스와이프 감지 최소 거리

  const distance = touchEndX - touchStartX;

  if (distance > swipeThreshold) {
    // 오른쪽에서 왼쪽으로 스와이프 (이전 페이지)
    goBack();
  } else if (distance < -swipeThreshold) {
    // 왼쪽에서 오른쪽으로 스와이프 (다음 페이지)
    goForward();
  }
}

function goForward() {
  const currentPage = window.location.pathname;

  if (currentPage === '/index.html' || currentPage === '/') {
    window.location.href = 'page2.html';
  } else if (currentPage === '/page2.html') {
    window.location.href = 'page3.html';
  } else if (currentPage === '/page3.html') {
    window.location.href = 'page4.html';
  }
  // 모바일 우측에서 좌측으로 밀면 1~4페이지까지 이동
  // 마지막 페이지에서는 아무 동작도 하지 않음
}

function goBack() {
  const currentPage = window.location.pathname;

  if (currentPage === '/page4.html') {
    window.location.href = 'page3.html';
  } else if (currentPage === '/page3.html') {
    window.location.href = 'page2.html';
  } else if (currentPage === '/page2.html') {
    window.location.href = 'index.html';
  }
  // 모바일 좌측에서 우측으로 밀면 4~1페이지까지 이동
  // 첫 페이지에서는 아무 동작도 하지 않음
}

document.addEventListener('touchstart', handleTouchStart, false);
document.addEventListener('touchend', handleTouchEnd, false);
