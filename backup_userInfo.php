<?php // 유저 정보 페이지
	header('Content-Type: text/html; charset=UTF-8');
	include './WriteLog.php';
	session_start();
	$session_id = $_SESSION['session_id'];
	$message = "";
	log_write($session_id, "사용자(타인) 정보 화면 접속");
	$get_user = $_POST['user_info'];
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
			}
		</script>
		<meta charset="utf-8"/>
		<title>Epula v0.1 - 사용자 정보</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>

	<!--메뉴바-->
	<nav class="menubar">
		<ul class="menu">
			<li><a href="MapMain.php"><h1 class="logo">Epula</h1></a></li>
			<li style="margin-left: 680px;"><h1 class="logo">메인 화면</a></li>
		</ul>
		<ul class="mydata">
			<li><a href="add.php">맛집 추가</a></li>
			<li><a href="delete.php">맛집 삭제</a></li>
			<li><a href="logout.php">로그아웃</a></li>
		</ul>
	</nav>

        <section>
          <div id="loginbox" style="height:auto;">
        <?php
	/* POST로 넘겨받은 user_id로 DB에서 회원 정보 받아오기 */ 
	// MySQL 드라이버 연결
	include '/SQLconstants.php';
	$conn = mysqli_connect($mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database);

	// 전달 받은 메시지 확인
	$message = $_POST['message'];
	$message = ((($message == null) || ($message == "")) ? "_%" : $message);

	// MySQL 검색 실행 및 결과 출력
	log_write($_SESSION['session_id'], "받아온 유저 아이디: ".$_POST['user_info']);
	$user_info_name = $_POST['user_info'];
	echo "<br><br>'$user_info_name'";
	$query = "SELECT * FROM Users WHERE user_id=".$user_info_name."";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_array($result);
	$message = "의 정보 조회";
	log_write($_SESSION['session_id'], "정보 조회");
	echo "<br><br> 쌉가능";
	/* 회원 정보가 없을 경우 빈 페이지 띄우기 */
	if ($row['user_id']== NULL) {
		$message = $row['user_id']."회원 정보 조회 오류";
		log_write(session_id(), $message);

		echo "<div style='height: 383px;'>";
		echo "<h1 style='font-size: 300px; margin: 50px 0px 0px 0px;'>엥</h1>";
		echo "<span style='color: rgb(180, 180, 180); position: relative; bottom: 30px; font-size: 20px;'>오류: 회원 정보가 없습니다. 관리자에게 문의하세요.</span>";
		echo "</div>";
	} else {
		$message = $row['user_id']."회원 정보 조회 성공";
		log_write(session_id(), $message);	
		/* 식객지수 표시 */
		echo "<div id='info' style='height:500px; text-align: center'>";
		echo "<div id='gourmet' style='width:400px;height:500px; display: inline-block; bottom : 150px; margin: 0px 100px 100px 70px;'>";
		echo "<img src='https://cdn-icons-png.flaticon.com/512/6522/6522516.png' style='display: inline-block; position: relative; width: 100px; right: 166px; top: 70px;'></img>";
		echo "<h1 style='position: relative; font-size: 45px; right: 60px; bottom: 37px;'>".$row['name']."</h1>";
		echo "<h1 style='font-size: 40px;position:relative; right:140px;'>식객지수</h1>";
		echo "<h1 style='font-size: 30px; position: relative; top: 20px; left:150px;'>".$row['gourmet_score']."점</h1>";
		echo "<progress value='".$row['gourmet_score']."' max='100' style='width:400px; height:50px;'></progress>";
		echo "</div>";
		/* 이외 정보 표시 */
		// 선호 카테고리 시각화
		$favorite_food = "없음";
		if ($row['favorite_food'] == 'korean') {
			$favorite_food = "한식";
		} else if ($row['favorite_food'] == 'asian') {
			$favorite_food = "아시안/양식";
		} else if ($row['favorite_food'] == 'japanese') {
			$favorite_food = "돈까스/회/일식";
		} else if ($row['favorite_food'] == 'dessert') {
			$favorite_food = "카페/디저트";
		} else if ($row['favorite_food'] == 'fastfood') {
			$favorite_food = "패스트푸드";
		} else if ($row['favorite_food'] == 'steamed') {
			$favorite_food = "찜/탕";
		} else if ($row['favorite_food'] == 'chicken') {
			$favorite_food = "치킨";
		} else if ($row['favorite_food'] == 'pizza') {
			$favorite_food = "피자";
		} else if ($row['favorite_food'] == 'casualfood') {
			$favorite_food = "분식";
		} else {
			$favorite_food = "없음";
		}
		echo "<div id='my_info' style='display: inline-block; bottom: 150px; margin: 0px 100px 100px 70px;'>";
		echo "<form style='font-size: 35px; width: 700px; display: inline-block; text-align: left; position:relative; left: 300px; bottom: 32px;'>";
		echo "<br><br> I &nbsp; &nbsp; D &nbsp;: ".$row['user_id'];
		echo "<br><br> 나 &nbsp; 이: ".$row['age'];
		echo "<br><br> 성 &nbsp; 별: ".$row['gender'];
		echo "<br><br> 선호 카테고리: ".$favorite_food;
		echo "</div>";
	}

	// MySQL 드라이버 연결 해제
	mysqli_free_result( $result );
	mysqli_close( $conn );
?>
         </div>
       </section>
</body>

