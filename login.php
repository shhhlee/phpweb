<html>
	<head>
		<link rel="stylesheet" href="css/sign.css?after">
		<link rel="stylesheet" href="css/button.css?after">
	</head>
	<body>
		<div class="login-box">
 			<h2>Login</h2>
 			<form method="POST" action="login_ok.php">
   				<div class="user-box">
					<input type="text" name="id" placeholder="ID"required>
					<input type="password" name="pwd" placeholder="Password"required>
   				</div>
				<button type="submit">Submit</button>
                <button type="button" onclick="location.href='../sign_up.php'">Sign Up</button >
  			</form>
		</div>
	</body>
</html>