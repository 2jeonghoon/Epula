<?php
    header('Content_Type: text/html; charset=UTF-8');
	include 'WriteLog.php';
	// MySQL 드라이버 연결
       include './SQLconstants.php';
       $conn = mysqli_connect($mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database) or die ("Cannot access DB");
	   
    session_start();



    log_write(session_id(), "제거 화면 접속");
?>
<html>
	<head>
		<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=807aeebbd40af5de924249a4a806e30d&libraries=services"></script>
		<style>
			.search {
				position: relative;
				width: 300px;
			}

			input {
				width: 400px;
				border: 1px solid #bbb;
				border-radius: 8px;
				padding: 10px 12px;
				font-size: 14px;
			}

			img #searchicon {
				position: absolute;
				width: 17px;
				top: 10px;
				right: 12px;
				margin: 0;
			}
                        
                        section form input[type=text]{
                                margin: 0px 0px 5px 0px;
                        }
		</style>
		<title>Epula v0.1 - 식당삭제</title>
                <link rel="stylesheet" href="style.css">
	</head>
	<body>
	<!--메뉴바--!>
         <nav class="menubar">
	     <ul class="menu">
	       <li><a href="MapMain.php"><h1 class="logo">Epula</h1></a></li>
               <li style="margin-left: 650px;"><h1 class="logo">맛집 삭제</a></li>
             </ul>
             <ul class="mydata"> 
               <li><a href="add.php">맛집 추가</a></li>
               <li><a href="delete.php">맛집 삭제</a></li>
               <li><a href="login.php">내 정보</a></li>
             </ul>
	  </nav>

        <section>
          <div id="searchbox" style="height: auto;">
		<div id="map" style="width:400px;height:300px; display: inline-block; top:50px; right: 450px;"></div>
                 <div style="display : block; position: relative; width:400px; left: 100px; top: 60px; right:0px;">
		  <input style="display:inline-block;"type="text" id="val" placeholder="장소 입력" onkeypress="Enter()">
		  <img id="searchicon" style="position: absolute; right: 12px; width: 17px; top: 10px; margin: 0;"src="https://s3.ap-northeast-2.amazonaws.com/cdn.wecode.co.kr/icon/search.png" onmousedown="printPlace()">
                </div>

		<div class="search" style="display : inline-block;">
		<br>
		<!-- 화면 구성 -->
		<form name = "formm" method = "post" action = "./deleteSQL.php" style = "width: 700px; position: relative; bottom: 305px;">

			삭제할 식당 이름 : <input type="text" id = "name" name = "name" size="60">
                        <br>
			삭제할 식당 ID : <input type = "text" id = "id" name = "id" size="60" required>
			<br>삭제하려는 이유: <input type = "text" name = "text" size = "60" required>
		</form>
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

		<INPUT TYPE = "button" value = "삭제" onClick="javascript:document.formm.submit();"style="border-radius : 8px; background-color : rgb(255,145,70); color: white; width: 100px; font-size: 30px; border-color:white;position: relative; bottom: 110px; left: 400px;"> &nbsp;
		<BR><BR>
	
		</div>
	   </div>
         </section>
		<script>	
			var mapContainer = document.getElementById("map");
			var options = {
				center: new kakao.maps.LatLng(37.602322, 126.955350),
				level: 3
                        };

			var map = new kakao.maps.Map(mapContainer, options);
			var markers = [];

			marker = new kakao.maps.Marker({
    			map: map,
    	    	position: new kakao.maps.LatLng(37.6023222243288, 126.955350026719)
    		});
    		marker.setMap(map);
    		markers.push(marker);
			
			var callback = function(result, status) {
				if(status === kakao.maps.services.Status.OK) {
					hideMarkers();
					marker = new kakao.maps.Marker({
				   		map: map,
				   		position: new kakao.maps.LatLng(result[0].y, result[0].x)
				   	});
				   	map.setCenter(new kakao.maps.LatLng(result[0].y, result[0].x));
					marker.setMap(map);
					markers.push(marker);
					document.getElementById('id').value = result[0]['id'];
					document.getElementById('name').value = result[0]['place_name'];
					document.getElementById('address').value = result[0]['address_name'];
					document.getElementById('pn').value = result[0]['phone'];
				}
			};
				
			function setMarkers(map) {
				   for (var i = 0; i < markers.length; i++) {
				   	markers[i].setMap(map);
				   }
			}

			function hideMarkers() {
				   setMarkers(null);
			}

			var places = new kakao.maps.services.Places(map);

			function printPlace() {
				   places.keywordSearch(document.getElementById('val').value, callback, 'FD6');
			}

			function Enter() {
				   if(event.keyCode == 13) {
				   	printPlace();
				   }
			}
		</script>
		<section>
		<div id="adminbox">
<?php
	if($_SESSION['isAdmin'] == true) {
		$query = "select * from RestaurantsToDelete";
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result)){
			echo "<BR> Restaurant ID : ".$row['restaurant_id'];
			$query = "SELECT name FROM Restaurants WHERE restaurant_id = ".$row['restaurant_id'];
			$result2 = mysqli_query($conn, $query);
			$row2 = mysqli_fetch_assoc($result2);
			echo "<BR> Restaurant Name : ".$row2['name'];
			echo "<BR> 삭제하려는 이유 : ".$row['text'];
			echo '
			<form action="deleteSQL.php" method="POST">
				<input type="hidden" name="delete_restaurant" value="'.$row['restaurant_id'].'">
				<input type="submit" value="Allow">
			</form>
			<form action="deleteSQL.php" method="POST">
				<input type="hidden" name="delete_request" value="'.$row['restaurant_id'].'">
				<input type="submit" value="Deny">';
			echo "<BR><BR>";
		}
	}

?>    
		</div>
		</section>
              <section>
               <div id="storebox">
               <?php
	      	       // 전달 받은 메시지 확인
	       $message = $_POST['message'];
	       $message = ( ( ( $message == null) || ( $message == "" ) ) ? "_%" : $message );
	   
	       // MySQL 검색 실행 및 결과 출력
	       $query = "select * from Restaurants";
	       $result = mysqli_query($conn, $query);
	       while($row = mysqli_fetch_array($result)){
	           echo "<BR><BR>";
	           echo "<BR><img src = '".$row['picture']."' height='280' width='180'>";
	           echo "<BR> ID : ".$row['restaurant_id'];
	           echo "<BR> 식당 이름 : ".$row['name'];
	           echo "<BR> 메뉴 : ".$row['menu'];
	           echo "<BR> 주소 : ".$row['address'];
	           echo "<BR> 전화번호 : ".$row['phone'];
	           echo "<BR> 영업 시간 : ".$row['opening_hour'];
	           echo "<BR> 배달 : ".$row['delivery'];
	           echo "<BR> 포장 : ".$row['take_out'];
	           echo "<BR> 태그 : ".$row['tag'];
	           echo "<BR><BR>";
	       }
	   
	       // MySQL 드라이버 연결 해제
	       mysqli_free_result( $result );
	       mysqli_close( $conn );
?> 
	     </div>
            </section>
	</body>
</html>
