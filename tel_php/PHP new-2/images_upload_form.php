<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>이미지 업로드</title>
</head>
<body>

<h2>이미지 업로드</h2>

<form action="images_upload.php" method="POST" enctype="multipart/form-data">
    <label>이미지 선택:</label><br>
    <input type="file" name="image" accept="image/*" required><br><br>

    <label>비고(Notice):</label><br>
    <textarea name="notice" rows="3" cols="40"></textarea><br><br>

    <button type="submit">업로드</button>
</form>

</body>
</html>
