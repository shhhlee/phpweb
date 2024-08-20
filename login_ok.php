<?php
    session_start(); // 세션 시작

    include 'user_DB.php';

    $id = $_POST['id'];
    $pw = $_POST['pwd'];

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if(mysqli_connect_errno()) {
        die("데이터 베이스 오류: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT * FROM user WHERE id=? AND pass=?");
    $stmt->bind_param("ss", $id, $pw);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $_SESSION['login_user'] = $id; // 로그인 성공 시 세션 생성
        header("Location: loginpage.php");
        exit();
    } else {
        echo '<script>alert("아이디 또는 비밀번호가 틀렸습니다."); window.location = "login.php";</script>';
    }

    $stmt->close();
    $conn->close();
?>