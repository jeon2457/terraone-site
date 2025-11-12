<!-- 이코드는 파이썬 스크립트 images_upload_dothome.py 파일을 GUI방식으로 닷홈 데이타베이스로 접속해서 
 이미지파일을 업로드 하려고했지만 외부차단으로 인해 현코드(python_image_upload.php)와
 서로 연계되어서 FTP로 접속해서(http://jikji35.dothome.co.kr/home/) 우회해서 DB로 입력데이타를
 images테이블로 입력된 이미지파일을 업로드 시킨다. 
 db_connect.php는 php폴더내에 있다. -->

<?php

require 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-1)
//include 'php/db-connect.php'; // DB 접속 정보 불러오기(방법-2)  

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["date"];
    $notice = $_POST["notice"];
    
    // 파일 업로드 처리
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $upload_dir = "data/profile/";  // 서버 내 저장 디렉토리
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);  // 디렉토리 없으면 생성
        }
        
        $file_name = basename($_FILES["photo"]["name"]);
        $unique_name = time() . "_" . $file_name;  // 중복 방지를 위해 타임스탬프 추가
        $target_path = $upload_dir . $unique_name;
        
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_path)) {
            $photo = $target_path;  // DB에 저장할 서버 경로
            $sql = "INSERT INTO images (photo, date, notice) VALUES ('$photo', '$date', '$notice')";
            if ($conn->query($sql) === TRUE) {
                echo "Success";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "File upload failed";
        }
    } else {
        echo "No file uploaded or upload error";
    }
}
$conn->close();
?>