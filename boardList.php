<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start(); // 세션 시작

if (!isset($_SESSION['login_user'])) {
    // 사용자가 로그인하지 않은 경우 로그인 페이지로 리디렉션합니다.
    header("Location: login.php");
    exit();
}

include 'board_DB.php';

// 데이터베이스 연결
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// 연결 오류 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 한 페이지에 보여줄 게시물의 수
$posts_per_page = 10;

// 현재 페이지 번호 가져오기 (기본값은 1)
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// 검색 기능을 위한 변수 초기화
$search_query = "";
$search_option = "";

// 검색 쿼리 처리
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query']) && isset($_POST['option'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['query']);
    $search_option = mysqli_real_escape_string($conn, $_POST['option']);
}

// 총 게시물의 개수 구하기
$sql_count = "SELECT COUNT(*) as total FROM Board_List";
if ($search_query !== "") {
    $sql_count .= " WHERE $search_option LIKE '%$search_query%'";
}
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_posts = $row_count['total'];

// 총 페이지 수 계산
$total_pages = ceil($total_posts / $posts_per_page);

// 현재 페이지의 첫 게시물 번호 계산
$offset = ($current_page - 1) * $posts_per_page;

// 현재 페이지의 게시물 가져오기
$sql = "SELECT idx, title, id, created, views FROM Board_List";
if ($search_query !== "") {
    $sql .= " WHERE $search_option LIKE '%$search_query%'";
}
$sql .= " ORDER BY created DESC LIMIT $posts_per_page OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>게시판</title>
    <link rel="stylesheet" href="css/board.css?2">
</head>
<body>

<!-- 검색 기능 추가 -->
<div class="search-container">
  <form action="boardList.php" method="POST">
      <select name="option">
          <option value="title" <?php if($search_option == 'title') echo 'selected'; ?>>제목</option>
          <option value="id" <?php if($search_option == 'id') echo 'selected'; ?>>작성자</option>
          <option value="content" <?php if($search_option == 'content') echo 'selected'; ?>>내용</option>
      </select>
      <input type="text" name="query" placeholder="검색어를 입력하세요" value="<?php echo $search_query; ?>">
      <button type="submit">검색</button>
  </form>
</div>

<div class="container">
  <!-- 게시글 작성 버튼 -->
    <div class="button-container">
        <a href="../postCreate.php" class="button">게시글 작성</a>
        <a href="../loginpage.php" class="button">메인페이지</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">UserID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Views</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 게시글 목록 표시
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='userId-column'>" . $row["id"] . "</td>";
                        echo "<td class='title-column'><a href='view_post.php?index=" . $row["idx"] . "'>" . $row["title"] . "</a></td>";
                        echo "<td class='view-column'>" . $row["views"] . "</td>";
                        echo "<td class='date-column'>" . $row["created"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>게시글이 없습니다.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- 페이지 네비게이션 -->
    <div class="pagination-container">
        <div class="pagination">
            <?php
            // 이전 페이지 링크
            if ($current_page > 1) {
                echo "<a href='boardList.php?page=" . ($current_page - 1) . "'>이전</a> ";
            }

            // 페이지 번호 링크
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $current_page) {
                    echo "<strong>$i</strong> "; // 현재 페이지는 굵게 표시
                } else {
                    echo "<a href='boardList.php?page=$i'>$i</a> ";
                }
            }

            // 다음 페이지 링크
            if ($current_page < $total_pages) {
                echo "<a href='boardList.php?page=" . ($current_page + 1) . "'>다음</a>";
            }
            ?>
        </div>
    </div>

    
</div>
</body>
</html>

<?php
// 데이터베이스 연결 종료
$conn->close();
?>
