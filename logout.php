<?php
	header('Content-Type: text/html; charset=UTF-8');
	include("./SQLconstants.php");
	session_start();

	/* 최초 접근 시 */
	if($_SESSION['session_id'] == NULL) {
		echo '<script>alert("세션 id 초기화");</script>';
		$_SESSION['session_id'] = session_id();
	}

	/* 세션에 저장된 회원 정보 초기화 */
	$_SESSION['user_id'] = NULL;
	$_SESSION['name'] = NULL;
	$_SESSION['age'] = NULL;
	$_SESSION['gender'] = NULL;
	$_SESSION['favorite_food'] = NULL;
	$_SESSION['gourmet_score'] = NULL;
	$_SESSION['isLoogin'] = false;
?>

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
	</style>
	<title>Epula v0.1 - 로그아웃</title>
	<link rel="stylesheet" href="style.css">
   </head>
   <body onLoad="showMessage( '<?php echo $_POST['message']?>' );">
	<!--메뉴바-->
	<nav class="menubar">
		<ul class="menu">
			<li><a href="MapMain.php"><h1 class="logo">Epula</h1></a></li>
 	                <li style="margin-left: 700px;"><h1 class="logo">로그아웃</a></li>
		</ul>
		<ul class="mydata">
			<li><a href="add.php">맛집 추가</a></li>
			<li><a href="delete.php">맛집 삭제</a></li>
			<li><a href="login.php">로그인</a></li>
		</ul>
	</nav>
	<section>
        <div id="loginbox">
	  <div id="logout_success">
		<form style='font-size: 40px; width: 700px; display: inline-block; position: relative; bottom: 30px;'>
			<?php
				/* 세션에 저장된 회원 정보 초기화 */
				$_SESSION['user_id'] = NULL;
				$_SESSION['name'] = NULL;
				$_SESSION['age'] = NULL;
				$_SESSION['gender'] = NULL;
				$_SESSION['favorite_food'] = NULL;
				$_SESSION['gourmet_score'] = NULL;
				$_SESSION['isLogin'] = false;

				if ($_SESSION['isLogin'] == true) {
					echo "<br><br>로그아웃에 실패했습니다.<br><br>";
				} else {
					echo "<br><br>로그아웃에 성공했습니다.<br><br>";
				}
			?>

		<input type="button" value="로그인 화면으로" style="border-radius:8px;background-color: rgb(255, 145, 70); color: white; width: 250px; font-size: 30px; border-color: white; padding: 10px 12px; position: relative;  right: 100px; border-width:0px;" onClick="location.href='login.php'">
		<input type="button" value="메인 화면으로" style="border-radius:8px;background-color: rgb(255, 145, 70); color: white; width: 250px; font-size: 30px; border-color: white; padding: 10px 12px; position: relative; left: 100px;border-width:0px;" onClick="location.href='MapMain.php'">
         	</div>
          </div>
	</section>
	</body>
</html>

