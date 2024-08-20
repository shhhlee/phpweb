<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/post.css">
</head>
<body>
    <div class="container">
        <?php
        include 'board_DB.php';

        session_start(); // 세션 시작

        if(!isset($_SESSION['login_user'])) {
            header("Location: login.php");
            exit();
        }
        $user_name = $_SESSION['login_user'];

        // 데이터베이스 연결
        $conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

        // 연결 오류 확인
        if ($conn->connect_error) {
            die("데이터베이스 연결 실패: " . $conn->connect_error);
        }

        // 게시글 ID 가져오기
        $post_id = $_GET["index"];

        // 해당 게시글 데이터 가져오기
        $sql = "SELECT * FROM Board_List WHERE idx = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        // 연결 종료
        $conn->close();

        if($row['id'] == $user_name) {
        ?>

        <h2 class="title">게시글 수정</h2>
        <form id="frm" class="post-form" action="posteditProcess.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            
            <label for="title">제목</label><br>
            <input type="text" name="title" value="<?php echo $row['title']; ?>" required><br>

            <label for="content">내용</label><br>
            <textarea name="content" required><?php echo $row['content']; ?></textarea><br>

            <?php if ($row['file_path']) { ?>
                <label for="existing_file">첨부된 파일: </label><br>
                <a href="<?php echo $row['file_path']; ?>" download><?php echo basename($row['file_path']); ?></a><br><br>

                <input type="checkbox" name="delete_file" value="1"> 기존 파일 삭제<br><br>
            <?php } ?>

            <label for="file">새로운 파일 업로드</label><br>
            <input type="file" name="file"><br><br>

            <div style="text-align: right;">
                <a href="#" onclick="document.getElementById('frm').submit();" class="button">수정</a>
                <a href="../boardList.php" class="button">취소</a>
            </div>
        </form>
        <?php
        } else {
            echo '<script>alert("권한이 없습니다"); window.location = "boardList.php";</script>';
        }
        ?>
    </div>
</body>
</html>
