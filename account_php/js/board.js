function getUrlParams() {
  const params = {};

  window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str, key, value) {
    params[key] = value;
  }
  
  );

  return params;
}





document.addEventListener("DOMContentLoaded", () => {
  const btn_write = document.querySelector("#btn_write")
  btn_write.addEventListener("click", () => {
    //alert(getUrlParams()) // 글쓰기 버튼클릭 반응 확인용

    const params = getUrlParams();

    //alert(params["bcode"]);  // ex) rxzyic
    //console.log(params) 

    self.location.href='./board_write.php?bcode=' + params['bcode'];
    

  })
})