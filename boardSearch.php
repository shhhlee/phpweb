<?php
    session_start(); // 세션 시작

    if(!isset($_SESSION['login_user'])) {
      header("Location: login.php");
      exit();
    }
?>
<html>
<head>
<title>게시판</title>
<link rel="stylesheet" href="css/board.css?after">
</head>

<body>
<div class="search-container">
  <form action="boardSearch.php" method="POST">
      <select name="option">
          <option value="title">제목</option>
          <option value="id">작성자</option>
          <option value="content">내용</option>
      </select>
      <input type="text" name="query" placeholder="검색어를 입력하세요">
      <button type="submit">검색</button>
  </form>
</div>

<div class="container">
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
            include 'board_DB.php';
        
            // 데이터베이스 연결
            $conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

            // 연결 오류 확인
            if ($conn->connect_error) {
                die("데이터베이스 연결 실패: " . $conn->connect_error);
            }

            // 사용자가 선택한 검색 옵션과 검색어를 가져옴
            $option = $_POST["option"];
            $query = $_POST["query"];

            // 게시글 검색 쿼리
            $sql = "SELECT idx, title, id, created, views FROM Board_List WHERE $option LIKE '%$query%' ORDER BY created DESC";
            $result = $conn->query($sql);

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

            // 데이터베이스 연결 종료
            $conn->close();
        ?>
      </tbody>
    </table>
  </div>
  <?php
  echo "<div class='button-container'>";
  echo "<a href='../postCreate.php' class='button'>게시글작성</a>";
  echo "<a href='../loginpage.php' class='button'>메인페이지</a>";
  echo "</div>";
  ?>
</div>

</body>
</html>