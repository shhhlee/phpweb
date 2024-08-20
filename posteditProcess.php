<?php
include 'board_DB.php';

session_start(); // 세션 시작

if(!isset($_SESSION['login_user'])) {
    // 사용자가 로그인한 경우에만 환영 페이지를 표시합니다.
    header("Location: login.php");
    exit();
}
$user_name = $_SESSION['login_user'];

// 데이터베이스 연결
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// 연결 오류 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 게시글 데이터 받아오기
$post_id = $_POST['post_id'];
$title = $_POST['title'];
$content = $_POST['content'];

// 기존 게시글 데이터 가져오기
$sql = "SELECT * FROM Board_List WHERE idx = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['id'] == $user_name) {
    $file_path = $row['file_path']; // 기존 파일 경로

    // 파일 삭제를 요청한 경우
    if (isset($_POST['delete_file']) && $_POST['delete_file'] == '1') {
        if ($file_path && file_exists($file_path)) {
            unlink($file_path); // 서버에서 파일 삭제
            $file_path = NULL; // 파일 경로를 NULL로 설정
        }
    }

    // 새로운 파일이 업로드된 경우
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // 기존 파일 삭제
        if ($file_path && file_exists($file_path)) {
            unlink($file_path); // 서버에서 파일 삭제
        }

        // 새 파일 저장
        $file_tmp_path = $_FILES['file']['tmp_name'];
        $file_name = basename($_FILES['file']['name']);
        $upload_dir = 'uploads/';

        // 업로드 디렉토리가 없으면 생성
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // 파일 이름을 고유하게 만듦
        $unique_name = time() . '_' . uniqid() . '_' . $file_name;
        $file_dest_path = $upload_dir . $unique_name;

        if (move_uploaded_file($file_tmp_path, $file_dest_path)) {
            $file_path = $file_dest_path; // 새 파일 경로 저장
        } else {
            echo '<script>alert("파일 업로드 중 오류가 발생했습니다."); window.location = "postUpdate.php?index=' . $post_id . '"; </script>';
            exit();
        }
    }

    // 게시글 업데이트 쿼리
    $sql2 = "UPDATE Board_List SET title=?, content=?, file_path=? WHERE idx=?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("sssi", $title, $content, $file_path, $post_id);

    // 쿼리 실행 및 결과 확인
    if ($stmt2->execute()) {
        echo '<script>alert("게시글이 수정되었습니다."); window.location = "boardList.php"; </script>';
    } else {
        echo '<script>alert("게시글 수정 오류"); window.location = "postUpdate.php?index=' . $post_id . '"; </script>';
    }
} else {
    echo '<script>alert("권한이 없습니다."); window.location = "boardList.php"; </script>';
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>
