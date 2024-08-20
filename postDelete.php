<?php

    session_start(); // 세션 시작

    if(!isset($_SESSION['login_user'])) {
        // 사용자가 로그인한 경우에만 환영 페이지를 표시합니다.
        header("Location: login.php");
        exit();
    }
    $user_name = $_SESSION['login_user'];

    include 'board_DB.php';

    // 삭제할 게시물의 ID를 가져옴
    $post_id = $_GET["index"];

    // 데이터베이스 연결
    $conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

    // 연결 오류 확인
    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }

    if ($row['id'] == $user_name) {
        // 파일 경로 확인 및 파일 삭제
        if (!empty($row['file_path'])) {
            $file_path = $row['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path); // 파일 삭제
            }
        }
    }
    
    // 게시물 삭제 쿼리
    $sql = "SELECT * FROM Board_List WHERE idx = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if($row['id'] == $user_name) {
        $sql2 = "DELETE FROM Board_List WHERE idx = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $post_id);
        $stmt2->execute();
    }

    // 삭제 결과 확인
    if ($stmt2->affected_rows > 0) {
        echo '<script>alert("게시글이 삭제되었습니다."); window.location = "boardList.php";</script>';
    } else {
        echo '<script>alert("게시글 삭제 오류"); window.location = "boardList.php";</script>';
    }

    // 데이터베이스 연결 종료
    $stmt->close();
    $conn->close();
?>
