<?php
	header('Content_Type: text/html; charset=UTF-8');
	include 'WriteLog.php';
	session_start();
	$session_id = $_SESSION['session_id'];
	if($session_id == NULL) {
		echo '<script>alert("세션 id 초기화");</script>';
		$session_id = session_id();
		$_SESSION['session_id'] = $session_id;
	}
	if($_SESSION['isLogin'] == true) {
		$login_text = '내 정보';
	} else {
		$login_text = '로그인';
	}
    	$message = "";
	log_write($session_id, "메인 화면 접속");
?>
<html>
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
                <style>
                   
                </style>
		<meta charset="utf-8"/>
		<title>Epula v0.1 - 식당 리스트</title>
                <link rel="stylesheet" href="style.css">
	</head>
<body onLoad="showMessage( '<?php echo $_POST['message'];?>' );">
	<!--메뉴바-->
        <nav class="menubar">
		<ul class="menu">
	         <li><a href="MapMain.php"><h1 class="logo">Epula</h1></a></li>
		 <li style="margin-left: 680px;"><h1 class="logo">메인 화면</a></li>
               </ul>
		<ul class="mydata">
                <li><a href="add.php">맛집 추가</a></li>
                <li><a href="delete.php">맛집 삭제</a></li>
		<li><a href="login.php"><?=$login_text?></a></li>
		</ul>
        </nav> 

        <!--검색창-->
	<section>
        	<div id="searchbox">
			<h2>오늘 뭐 먹지?</h2>
			<form name="formm" method="post">
				<input type="text" id="search" name="query" placeholder="지역, 식당, 음식 또는 태그">
              			<input type="image" id="searchicon" onClick="javascript:move('./add.php');"style="position: relative; width: 30px; height: 30px; top: 10px;" src="image/search_icon.png" alt="검색버튼">
	    		</form>               
	  	</div>
	</section>

        <!--맛집 나열창-->
	<section>
         	<div id="storebox">
		<div id = "categorybox">
	  		<br>
			<form action="MapMain.php" method="POST">
		  		<button id="category_img"type="submit" name="category" value="korean"><img src="image/category/korean.png" alt="한식" width="100" height="100">
				<button id="category_img" type="submit" name="category" value="asian"><img src="image/category/western.png" alt="아시안, 양식" width="100" height="100">
				<button  id="category_img" type="submit" name="category" value="japanese"><img src="image/category/japanese.png" alt="돈까스회일식" width="100" height="100">
				<button  id="category_img" type="submit" name="category" value="dessert"><img src="image/category/dessert.png" alt="카페디저트" width="100" height="100">
				<button  id="category_img" type="submit" name="category" value="fastfood"><img src="image/category/fastfood.png" alt="패스트푸드" width="100" height="100">
				<button  id="category_img" type="submit" name="category" value="steamed"><img src="image/category/steamed.png" alt="찜탕" width="100" height="100">
				<button  id="category_img" type="submit" name="category" value="chicken"><img src="image/category/chicken.png" alt="치킨" width="100" height="100">
				<button  id="category_img" type="submit" name="category" value="pizza"><img src="image/category/pizza.png" alt="피자" width="100" height="100">
				<button  id="category_img" type="submit" name="category" value="casualfood"><img src="image/category/casualfood.png" alt="분식" width="100" height="100">
			</form>
			<br>
		</div>
           <?php
	   // MySQL 드라이버 연결
	   include './SQLconstants.php';
	   $conn = mysqli_connect($mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database) or die ("Cannot access DB");
	   
	   // 전달 받은 메시지 확인
	   $message = $_POST['message'];
	   $category = $_POST['category'];
	   $message = ( ( ( $message == null) || ( $message == "" ) ) ? "_%" : $message );
	   // MySQL 검색 실행 및 결과 출력
	   if(isset($_POST['category'])) {
		$query = "SELECT * FROM Restaurants WHERE category = '$category'";
	   } else {
	   	$query = "select * from Restaurants";
	   }
	   $result = mysqli_query($conn, $query);

	   /*만약 퀴리에 맞는 가게가 한 곳도 없다면. 빈 페이지를 출력해줘야함. */
	   if(mysqli_num_rows($result)==0){
		   echo "<div style='height: 383px;'>";
		   echo "<h1 style='font-size: 300px; margin: 50px 0px 0px 0px;'>텅</h1>";
		   echo "<span style='color: rgb(180,180,180); position: relative; bottom: 30px; font-size: 20px;'>해당 카테고리에 등록된 맛집이 없어요</span>";
	           echo "</div>";
           }else
	   {  $cnt = 0;
	      while($row = mysqli_fetch_array($result)){
	       if($cnt>=4)
	       {   /*4개 기준으로 줄바꿈*/
	      	   echo "<br>";
		   $cnt =0;
	       }
	       /*div가 눌렸을 때 페이지가 바뀌고 식당 세부정보, 리뷰들이 나와야 함 */
	       /*일단 눌렸을 때 나오는 페이지 구현하고 리뷰 등록하는 페이지 구현해야 함*/
               echo "<div id='store' style ='display: inline-block'><BR><BR>";
	       echo "<BR><img src = '".$row['picture']."' height='280' width='180'>";
	       /*	       echo "<BR> ID : ".$row['restaurant_id']; */
	       echo "<BR>".$row['name'];
	       $review_sum = 0;	// 리뷰 합계
	       $review_cnt = 0;	// 리뷰 개수
	       $review_score_query = "select score from Reviews WHERE restaurant_id = ".$row['restaurant_id'];
        	$review_result = mysqli_query($conn, $review_score_query);

	       while($review_row = mysqli_fetch_array($review_result)) {
		       $review_sum += $review_row[0];
		     $review_cnt++;
	       }
	       $review_avg = $review_sum / $review_cnt;
	       echo "   별점".$review_avg;
	       /* echo "<BR> 메뉴 : ".$row['menu'];*/
	       /* echo "<BR> 주소 : ".$row['address'];*/
	       /* echo "<BR> 전화번호 : ".$row['phone'];*/
	       /*echo "<BR> 영업 시간 : ".$row['opening_hour'];*/
	       /*echo "<BR> 배달 : ".$row['delivery'];*/
	       /*echo "<BR> 포장 : ".$row['take_out'];*/
	       echo "<BR> 태그 : ".$row['tag'];
	       echo "<BR><BR></div>";
	       $cnt++;

	     }
	   }
	   // MySQL 드라이버 연결 해제
	   mysqli_free_result( $result );
	   mysqli_close( $conn );
?>
       </div>
       </section>  
</body>
