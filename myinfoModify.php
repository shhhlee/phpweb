<html>
	<head>
		<link rel="stylesheet" href="css/sign.css?10">
		<link rel="stylesheet" href="css/button.css?4">
	</head>
	<body>
		<div class="login-box">
        <?php
        include 'user_DB.php';

        session_start(); // 세션 시작

        if(!isset($_SESSION['login_user'])) {
            header("Location: login.php");
            exit();
        }
        $user_id = $_SESSION['login_user'];

        // 데이터베이스 연결
        $conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

        // 연결 오류 확인
        if ($conn->connect_error) {
            die("데이터베이스 연결 실패: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if($row['id'] == $user_id) {

        ?>
 			<h2>개인정보수정</h2>
			 <form method="POST" action="infomodifyProcess.php">
   				<div class="user-box">
                    <input type="hidden" name="post_id" value="<?php echo $user_id; ?>">
                    <label>ID</label><br>
                    <p><?php echo $user_id; ?></p>
                    <label>Name</label><br>
				    <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br>
                    <label>Password</label><br>
					<input type="password" name="pwd" placeholder="Password"required>
                    <label>Password Confirm</label><br>
					<input type="password" name="pwd_confirm" placeholder="Password Confirm"required>
   				</div>
				<button class="button2" type="submit">Submit</button>
                <button class="button2" type="button" onclick="location.href='../myInfo.php'">MyPage</button >
  			</form>
        <?php
        $conn->close();
        } else
        {
            echo $user_id;
            echo $row['id'];
        }
        ?>
		</div>
	</body>
</html>