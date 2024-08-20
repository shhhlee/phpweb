<?php
session_start(); // 세션 시작

if(!isset($_SESSION['login_user'])) {
    // 사용자가 로그인하지 않은 경우 로그인 페이지로 리디렉션합니다.
    header("Location: login.php");
    exit();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시글 작성</title>
    <link rel="stylesheet" href="css/post.css?3">
</head>
<body>
    <div class="container">
        <h2 class="title">게시글 작성</h2>
        <!-- enctype="multipart/form-data"는 파일 업로드를 지원합니다 -->
        <form id="frm" class="post-form" action="postProcess.php" method="POST" enctype="multipart/form-data">
            <label for="title">제목</label><br>
            <input type="text" name="title" required><br>

            <label for="content">내용</label><br>
            <textarea name="content" required></textarea><br>

            <label for="file">파일 업로드</label><br>
            <input type="file" name="file"><br><br>

            <div style="text-align: right;">
            <a href="#" onclick="document.getElementById('frm').submit();" class="button">작성</a>
            <a href="../boardList.php" class="button">취소</a>
            </div>
        </form>
    </div>
</body>
</html>
