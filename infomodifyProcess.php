<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
?>
<?php
    session_start();

    if (!isset($_SESSION['login_user'])) {
        // 로그인되어 있지 않으면 로그인 페이지로 리다이렉트
        header("Location: login.php");
        exit();
    }

    include 'user_DB.php'; // 데이터베이스 연결 정보

    // POST로 전송된 사용자 정보 가져오기
    $user_id = $_SESSION['login_user'];
    $post_name = $_POST['name'];
    $post_pwd = $_POST['pwd'];
    $post_pwdConfirm = $_POST['pwd_confirm'];

    // 비밀번호 일치 여부 확인
    if ($post_pwd !== $post_pwdConfirm) {
        echo '<script>alert("비밀번호가 일치하지 않습니다."); window.location = "myinfoModify.php";</script>';
        exit();
    }

    // 데이터베이스 연결
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

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

            // 사용자 정보 업데이트 쿼리 실행
            $sql = "UPDATE user SET name = '$post_name', pass = '$post_pwd' WHERE id = '$user_id'";
            if ($conn->query($sql) === TRUE) {
                echo '<script>alert("개인정보가 성공적으로 업데이트되었습니다. 다시 로그인해주세요"); window.location = "login.php";</script>';
            } else {
                echo '<script>alert("개인정보 업데이트 오류: ' . $conn->error . '"); window.location = "myinfoModify.php";</script>';
            }
        }

    // 데이터베이스 연결 종료
    $conn->close();
?>