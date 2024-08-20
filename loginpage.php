<?php
    session_start(); // 세션 시작

    if(!isset($_SESSION['login_user'])) {
        // 사용자가 로그인한 경우에만 환영 페이지를 표시합니다.
        header("Location: login.php");
        exit();
    }
    $user_name = $_SESSION['login_user'];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/sign.css?after">
        <link rel="stylesheet" href="css/button.css?after">
    </head>
    <body>
        <div class="login-box">
            <h2>Welcome, <?php echo $user_name; ?>!</h2>
            <button class="button1" onclick="location.href='../myInfo.php'">마이페이지</button>
            <button class="button1" onclick="location.href='../boardList.php'">게 시 판</button>
            <button class="button1" onclick="location.href='../logout.php'">로그 아웃</button>
        </div>
    </body>
</html>

