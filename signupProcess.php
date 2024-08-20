<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
?>
<?php
    include 'user_DB.php';
	
	$name = $_POST['name'];
	$id = $_POST['id'];
	$pw = $_POST['pwd'];

	$db_conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);
	
	if($_POST['name']=="" || $_POST['id']=="" || $_POST['pwd']=="") {
		echo '<script>alert("정보를 올바르게 입력해 주세요");
		window.history.back(); </script>';
		exit;	
	}

	$check = " SELECT * FROM user WHERE id = '{$id}'";
	$ret = mysqli_query($db_conn, $check);
	$exist = mysqli_fetch_array($ret);

	if ($exist) {
		echo '<script>alert("아이디 중복입니다.");
		window.history.back(); </script>';
	} else {
		$sql = " INSERT INTO user (idx, name, id, pass) 
		VALUES(NULL,'{$name}', '{$id}', '{$pw}')";
		$result = mysqli_query($db_conn, $sql);

		if($result) {
			echo '<script>alert("회원가입이 완료되었습니다.");
			window.location = "login.php"; </script>';
		}
		else {
			echo '<script>alert("회원가입 오류");
			window.history.back(); </script>';
		}
	}	
?>