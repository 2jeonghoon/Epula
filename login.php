<?php	// 참고 사이트 :  https://www.codingfactory.net/12195

/*	로그인 기능 구현
 *	로그인 상태 확인 구현
 *	로그인시 DB에서 사용자 정보를 가져와서 세션에 저장
 *	
 *	로그인한 경우 내 정보 화면으로 이동 구현 필요
 *	현재는 로그인한 경우 다시 로그인 화면을 클릭했을 때 메인화면으로 이동하도록 구현되어 있음
 */
	header('Content-Type: text/html; charset=UTF-8');
	include("./SQLconstants.php");
	session_start();

	$user_id = $_POST['user_id'];
	$passwd = $_POST['passwd'];

	/* 최초 접근 시 */
	if($_SESSION['session_id'] == NULL) {
		echo '<script>alert("세션 id 초기화");</script>';
		$_SESSION['session_id'] = session_id();
	}

	/* 이미 로그인한 경우 */
	if($_SESSION['isLogin'] == true) {
		/* 내 정보 화면으로 이동 */
		header('Location: myInfo.php');
	
	}

	/* POST로 user_id를 받은 경우 */
	if ( !is_null( $user_id ) ) {
		$_SESSION['user_id'] = $user_id;
   		$conn = mysqli_connect( $mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database ) or die("Can't access DB");
		$stm = $conn->stmt_init();
		$stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
		$stmt->bind_param("s", $user_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$db_passwd = null;
		if (is_null($row['gourmet_score'])) {
			$row['gourmet_score'] = 0;
		}
		if($row = $result->fetch_assoc()) {
			$db_passwd = $row['passwd'];
			$_SESSION['name'] = $row['name'];
			$_SESSION['age'] = $row['age'];
			$_SESSION['gender'] = $row['gender'];
			$_SESSION['favorite_food'] = $row['favorite_food'];
			$_SESSION['gourmet_score'] = $row['gourmet_score'];
			$_SESSION['isAdmin'] = $row['isAdmin'];
		}
		if(is_null($db_passwd)) {
			$wu = 1;
		} else {
			$wu = 0;
			if(password_verify($passwd, $db_passwd)) {
				$wp = 0;
				$_SESSION['isLogin'] = true;
				header('Location: myInfo.php');
			} else {
				$wp = 1;
			}
		}
	}
?>
<!doctype html>
<html lang="ko">
  <head>

	<script type="text/javascript">
                        function showMessage( message )
                        {
                                if ( ( message != null ) && ( message != "" ) && ( message.substring( 0, 3 ) == " * " )  )
                                {
                                        alert( message );
                                }
                        }
                        // 지정한 url로 이동하는 함수
			function move( url )
			{
				document.formm.action = url;
				document.formm.submit();
			}
			</script>

    <meta charset="utf-8">
    		<style>
			input {
				width: 400px;
				border: 1px solid #bbb;
				border-radius: 8px;
				padding: 10px 12px;
				font-size: 14px;
			} 
                        section form input[type=text]{
                                margin: 0px 0px 5px 0px;
                        }
		</style>
    <title>Epula v0.1 - 로그인</title>
	<link rel="stylesheet" href="style.css">
  </head>
  <body>
	<nav class="menubar">
		<ul class="menu">
			<li><a href="MapMain.php"><h1 class="logo">Epula</h1></a></li>
 	                <li style="margin-left: 700px;"><h1 class="logo">로그인</a></li>
                </ul>
	</nav>
	<section>
	<div id="loginbox">
		<form action="login.php" method="POST">
			<?php
			// 아이디 틀림
                        if ( $wu == 1 ) {
				echo "<span>사용자이름이 존재하지 않습니다.</span>";
			}
			// 비밀번호 틀림
                        if ( $wp == 1 ) {
				echo "<span>비밀번호가 틀렸습니다.</span>";
			}
			?>
                        <br>
                        <p><input type="text" name="user_id" placeholder="사용자이름" style="height: 40px; border-radius:30px;padding-left: 15px; font-size: 20px;"required></p>
			<p><input type="password" name="passwd" placeholder="비밀번호" style="height: 40px; border-radius:30px;padding-left: 15px; font-size: 20px;"required></p>
			<div><input type="submit" value="로그인" style="border-radius: 8px; background-color: rgb(255,145,70);color: white; width: 100px; font-size:30px; border-color:white; padding: 10px 12px; position: relative; right: 100px;">

		</form>
		<form action="signup.php" method="POST" style="display:inline-block;">
			<input type="submit" value="회원가입" style="border-radius: 8px; background-color: rgb(255,145,70);color: white; width: 130px; font-size:30px; border-color:white; padding: 10px 12px; position: relative; left:100px;">
		</form>
                </div>
	</div>
	</section>
	</body>
</html>

