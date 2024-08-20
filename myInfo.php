<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지</title>
    <link rel="stylesheet" href="css/sign.css?4">
	<link rel="stylesheet" href="css/button.css">
</head>
<body>
    <h1>마이페이지</h1>
    <?php
    session_start(); // 세션 시작
    if(isset($_SESSION['login_user'])) {

    include 'user_DB.php';

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
   
    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }

        $user_id = $_SESSION['login_user'];

        // 사용자 정보 조회 쿼리
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $user_name = $row['name'];
        $stmt->close();

        if($row['id'] == $user_id) {
    ?>
    <div class="login-box">
        <h2>개인 정보</h2>
        <p>ID: <?php echo $user_id; ?></p>
        <p>Name: <?php echo $user_name; ?></p>
        <button type="button" class="button1" onclick="location.href='../loginpage.php'">메인페이지</button >
        <button type="button" onclick="location.href='../myinfoModify.php'">개인정보수정</button >
    </div>
    <?php
        } else {
            echo '<script>alert("권한이 없습니다."); window.history.back();</script>';
        }
    }
        else {
        header("Location: login.php");
        exit();
    }

    // 데이터베이스 연결 종료
    $conn->close();
    ?>
</body>
</html>