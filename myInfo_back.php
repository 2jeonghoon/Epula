<?php // 내 정보 페이지
	header('Content-Type: text/html; charset=UTF-8');
	include './WriteLog.php';
	session_start();
	$session_id = $_SESSION['session_id'];
	// 로그인했는지 검사
	if ($_SESSION['isLogin'] != true) {
		// 로그인하지 않았다면 로그인 화면으로 이동
		header('Location: login.php');
	}
	$message = "";
	log_write($session_id, "내 정보 화면 접속");
?>

<html>
	<head>
		<script type="text/javascript">
			function showMessage( message ) {
				if ((message != null) && (message != "") && (message.substring(0, 3) == " * ")) {
				alert( message );
				}
			}
			// 지정한 url로 이동하는 함수
			function move( url ) {
				document.formm.action = url;
				document.formm.submit();
		</script>
		<meta charset="utf-8"/>
		<title>Epula v0.1 - 내 정보</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body onLoad="showMessage( '<?php echo $_POST['message'];?>' );" >

	<!--메뉴바-->
	<nav class="menubar">
		<ul class="menu">
			<li><a href="MapMain.php"><h1 class="logo">Epula</h1></a></li>
			<li style="margin-left: 680px;"><h1 class="logo">메인 화면</a></li>
		</ul>
		<ul class="mydata">
			<li><a href="add.php">맛집 추가</a></li>
			<li><a href="delete.php">맛집 삭제</a><li>
			<li><a href="logout.php">로그아웃</a><li>
		</ul>
	</nav>

	<?php
	// 회원의 정보는 로그인 시 SESSION에 저장되므로 DB에 접근하지 않음 
	
	// SESSION에 회원 정보가 없을 경우 빈 페이지 띄우기
	if ($_SESSION['user_id']== NULL) {
		log_write($_SESSION['session_id'], "[ ".$_SESSION['user_id']." ] 회원 정보 조회 오류");
		log_write($_SESSION['session_id'], "이름: ".$_SESSION['name']);
		log_write($_SESSION['session_id'], "나이: ".$_SESSION['age']);
		log_write($_SESSION['session_id'], "성별: ".$_SESSION['gender']);
		log_write($_SESSION['session_id'], "좋아하는 음식: ".$_SESSION['favorite_food']);
		log_write($_SESSION['session_id'], "식객지수: ".$_SESSION['gourmet_score']);
		echo "<div style='height: 383px;'>";
		echo "<h1 style='font-size: 300px; margin: 50px 0px 0px 0px;'>엥</h1>";
		echo "<span style='color: rgb(180, 180, 180); position: relative; bottom: 30px; font-size: 20px;'>오류: 회원 정보가 없습니다. 관리자에게 문의하세요.</span>";
		echo "</div>";
	} else {
		log_write($_SESSION['session_id'], $_SESSION['user_id'].": 회원 정보 조회 성공");
		/* 식객지수 표시 */
		echo "<div id='info' style='height:500px; text-align: center'>";
		echo "<div id='gourmet' style='width:400px;height:500px; display: inline-block; bottom : 150px; margain: 0px 100px 100px 70px;'>";
		echo "<h1 style='font-size: 100px;'>식객지수</h1>";
		echo "<h1 style='font-size: 100px;'>".$_SESSION['gourmet_score']."점</h1>";
		echo "</div>";
		/* 이외 정보 표시 */
		// 선호 카테고리 시각화
		$favorite_food = "없음";
		if ($_SESSION['favorite_food'] == 'korean') {
			$favorite_food = "한식";
		} else if ($_SESSION['favorite_food'] == 'asian') {
			$favorite_food = "아시안/양식";
		} else if ($_SESSION['favorite_food'] == 'japanese') {
			$favorite_food = "돈까스/회/일식";
		} else if ($_SESSION['favorite_food'] == 'dessert') {
			$favorite_food = "카페/디저트";
		} else if ($_SESSION['favorite_food'] == 'fastfood') {
			$favorite_food = "패스트푸드";
		} else if ($_SESSION['favorite_food'] == 'steamed') {
			$favorite_food = "찜/탕";
		} else if ($_SESSION['favorite_food'] == 'chicken') {
			$favorite_food = "치킨";
		} else if ($_SESSION['favorite_food'] == 'pizza') {
			$favorite_food = "피자";
		} else if ($_SESSION['favorite_food'] == 'casualfood') {
			$favorite_food = "분식";
		} else {
			$favorite_food = "없음";
		}
		echo "<div id='my_info' style='display: inline-block; bottom: 150px; margain: 0px 100px 100px 70px;'>";
		echo "<form style='font-size: 30px; width: 700px; display: inline-block;'>";
		echo "<br><br> I &nbsp; &nbsp; D &nbsp;: ".$_SESSION['user_id'];
		echo "<br><br> 이 &nbsp; 름: ".$_SESSION['name'];
		echo "<br><br> 나 &nbsp; 이: ".$_SESSION['age'];
		echo "<br><br> 성 &nbsp; 별: ".$_SESSION['gender'];
		echo "<br><br> 선호 카테고리: ".$favorite_food;
		echo "</div>";
	}

	// MySQL 드라이버 연결 해제
	mysqli_free_result( $result );
	mysqli_close( $conn );
	?>
</body>

