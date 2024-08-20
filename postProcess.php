<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<?php
include 'board_DB.php';

session_start(); // 세션 시작

if (!isset($_SESSION['login_user'])) {
    // 사용자가 로그인한 경우에만 환영 페이지를 표시합니다.
    header("Location: login.php");
    exit();
}

// 데이터베이스 연결
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// 연결 오류 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 게시글 데이터 받아오기
$user_name = $_SESSION['login_user'];
$title = $_POST['title'];
$content = $_POST['content'];

// 파일 업로드 처리
$file_path = NULL;

if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
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

    // 파일을 서버에 저장
    if (move_uploaded_file($file_tmp_path, $file_dest_path)) {
        $file_path = $file_dest_path;
    } else {
        echo '<script>alert("파일 업로드 중 오류가 발생했습니다.");</script>';
        $file_path = NULL; // 오류 발생 시 파일 경로를 NULL로 설정
    }
}

// 게시글 데이터를 데이터베이스에 삽입하는 쿼리
$sql = "INSERT INTO Board_List (id, title, content, file_path, created, views) VALUES (?, ?, ?, ?, NOW(), 0)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $user_name, $title, $content, $file_path);

// 쿼리 실행 및 결과 확인
if ($stmt->execute()) {
    echo '<script>alert("게시글이 작성되었습니다."); window.location = "boardList.php"; </script>';
} else {
    echo '<script>alert("게시글 작성 오류"); window.location = "boardList.php"; </script>';
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>
