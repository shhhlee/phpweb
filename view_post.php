<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link rel="stylesheet" href="css/post.css?8">
</head>
<body>
    <div class="container">
        <?php
            session_start(); // 세션 시작

            if(!isset($_SESSION['login_user'])) {
                // 사용자가 로그인한 경우에만 환영 페이지를 표시합니다.
                header("Location: login.php");
                exit();
            }
            $user_name = $_SESSION['login_user'];

            // 게시글 ID를 가져옴
            $post_id = $_GET["index"];
            
            include 'board_DB.php';
            // 데이터베이스 연결
            $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

            // 연결 확인
            if ($conn->connect_error) {
                die("데이터베이스 연결 실패: " . $conn->connect_error);
            }

            // 조회수 업데이트 쿼리
            $update_sql = "UPDATE Board_List SET views = views + 1 WHERE idx = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $stmt->close();

            // 게시글 조회 쿼리
            $select_sql = "SELECT idx, title, id, content, created, views, file_path FROM Board_List WHERE idx = ?";
            $stmt = $conn->prepare($select_sql);
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
        
            if ($row) {
                echo "<div class='title'>" . $row["title"] . "</div>";
                echo "<div class='author-date'>작성자: " . $row["id"] . " | 조회수: " . $row["views"] . "</div>";
                echo "<hr>";
                echo "<div class='content'>" . $row["content"] . "</div>"; // 여기에 실제 내용 표시
                echo "<hr>";

                // 파일 다운로드 링크 추가
                if ($row['file_path']) {
                    $file_name = basename($row['file_path']); // 파일명 추출
                    echo "<div class='file-download'>첨부파일 : <a href='" . $row['file_path'] . "' download>" . htmlspecialchars($file_name) . "</a></div>";
                }
                
                echo "<div class='button-container'>";
                echo "<a href='javascript:window.history.back();' class='button'>목록</a>";
                if($row['id'] == $user_name) {
                    echo "<a href='postUpdate.php?index=" . $row['idx'] . "' class='button'>수정</a>";
                    echo "<a href='postDelete.php?index=" . $row['idx'] . "' class='button'>삭제</a>";
                }
                echo "</div>";
            } else {
                echo "게시글이 존재하지 않습니다.";
            }

            // 데이터베이스 연결 종료
            $conn->close();
        ?>
    </div>
</body>
</html>
