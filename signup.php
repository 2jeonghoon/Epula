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
	include("./WriteLog.php");
	session_start();

	/* 최초 접근 시 */
	if($_SESSION['session_id'] == NULL) {
		$_SESSION['session_id'] = session_id();
	}
	
	$user_id = $_POST[ 'user_id' ];
	$passwd = $_POST[ 'passwd' ];
	$name = $_POST[ 'name' ];
	$age = $_POST[ 'age' ];
	$gender = $_POST[ 'gender' ];
	$favorite = $_POST[ 'favorite' ];

	$passwd_confirm = $_POST[ 'passwd_confirm' ];

	if ( !is_null( $user_id ) ) {
		$jb_conn = mysqli_connect( $mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database ) or die("Can't access DB");
		$jb_sql = "SELECT user_id FROM Users WHERE user_id = '$user_id';";
		$jb_result = mysqli_query( $jb_conn, $jb_sql );

		while ( $jb_row = mysqli_fetch_array( $jb_result ) ) {
			$user_id_e = $jb_row[ 'user_id' ];
		}

		if ( $user_id == $user_id_e ) {
			$message = "중복되는 아이디";
			$isNameExist = true;
		} else if ( $passwd != $passwd_confirm ) {
			$message = "일치하지 않는 PW";
			$isPwInvalid = true;
		} else {
			$encrypted_passwd = password_hash( $passwd, PASSWORD_DEFAULT);
			$jb_sql_add_user = "INSERT INTO Users ( user_id, passwd, name, age, gender, favorite_food, isAdmin )
			       			VALUES ( '$user_id', '$encrypted_passwd', '$name', '$age', '$gender', '$favorite', '0' );";
			$result = mysqli_query( $jb_conn, $jb_sql_add_user );
			$message = "id: ".$user_id." pw: 암호화 name: ".$name." age: ".$age." gender: ".$gender." favorite: ".$favorite."";
			header( 'Location: login.php' );
		}
	}
	log_write(session_id(), $message);
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
	   #signio{
	      height: 40px;
              border-radius: 30px;
              padding-left: 15px;
	      font-size: 20px;
              border: 1px solid #bbb;
              padding : 10px 12px;
           } 
           select{
	      padding : 8px;
	      border: 1px solid #bbb;
	      position : relative;
	      width : 270px;
              right : 77px;
              color : rgb(85,85,85);
           }
        </style>
    <title>Epula v0.1 - 회원가입</title>
	<link rel="stylesheet" href="style.css">
  </head>
  <body>
	<nav class="menubar">
		<ul class="menu">
			<li><a href="MapMain.php"><h1 class="logo">Epula</h1></a></li>
                        <li style="margin-left: 700px;"><h1 class="logo">회원가입</a></li>
		</ul>
	</nav>
	<section>
	<div id="loginbox" style="height: 580px;">
		<form action="signup.php" method="POST">
			<p><input id="signio"type="text" name="user_id" placeholder="사용자 아이디" required></p>
			<p><input  id="signio"type="password" name="passwd" placeholder="비밀번호" required></p>
			<p><input  id="signio"type="password" name="passwd_confirm" placeholder="비밀번호 확인" required></p>
			<p><input  id="signio"type="text" name="name" placeholder="이름" required></p>
			<p><input  id="signio"type="number" name="age" style="position: relative; right:75px;"placeholder="나이" required></p>
			<p><select name="gender" >
				<option value="남자">성별을 선택하세요.</option>
				<option value="남자">남성</option>
				<option value="여자">여성</option>
			</select></p>
			<p><select name = "favorite" >
				<option value="korean">좋아하는 음식 카테고리를 선택하세요</option>
				<option value="korean">한식</option>
				<option value="asian">아시안/양식</option>
				<option value="japanese">돈까스/회/일식</option>
				<option value="dessert">카페/디저트</option>
				<option value="fastfood">패스트푸드</option>
				<option value="steamed">찜/탕</option>
				<option value="chicken">치킨</option>
				<option value="pizza">피자</option>
				<option value="casualfood">분식</option>
			</select></p>
			<p><input type="submit" value="회원가입" style="border-radius: 8px; background-color: rgb(255,145,70);color: white; width: 130px; font-size:30px; border-color:white; padding: 10px 12px; border-width: 0px;"></p>
<?php
if ( $isNameExist == true ) {
	echo "<p>사용자 이름이 중복되었습니다.</p>";
}
if ( $isPwInvalid == true ) {
	echo "<p>비밀번호가 일치하지 않습니다.</p>";
}
?>
		</form>
	</div>
	</section>
	</body>
</html>
