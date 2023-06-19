<?php
    header('Content_Type: text/html; charset=UTF-8');
    include 'WriteLog.php';
    session_start();
    log_write($user_id, "추가 화면 접속");
    
?>
<html>
	<head>
		<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=cafa9f820b44317cbf315014d8afa64d&libraries=services"></script>
		<style>
			input {
  				
  				border: 1px solid #bbb;
  				border-radius: 8px;
  				padding: 10px 12px;
  				font-size: 14px;
			}

			img {
  				position : absolute;
  				width: 17px;
  				top: 10px;
  				right: 12px;
  				margin: 0;
			}
                        section form input[type=text]{
			        margin: 0px 0px 5px 0px;
                        }
		</style>
		<title>Epula v0.1 - 식당추가</title>
	        <link rel="stylesheet" href="style.css">
        </head>
	<body onload="printPlace()">
           <!--메뉴바--!>
           <nav class="menubar">
	     <ul class="menu">
	       <li><a href="MapMain.php"><h1 class="logo">Epula</h1></a></li>
               <li style="margin-left: 650px;"><h1 class="logo">맛집 추가</a></li>
             </ul>
             <ul class="mydata">
                <li><a href="add.php">맛집 추가</a></li>
                <li><a href="delete.php">맛집 삭제</a></li>     
                <li><a href="login.php">내 정보</a></li>
             </ul>
	  </nav>

          <section>
             <div id="searchbox" style="height:500px; text-align: center" > 
		<div id="map" style="width:400px;height:300px; display: inline-block; bottom : 150px;margain: 0px 100px 100px 70px;"></div>
		
		<div class="search" style="display: inline-block;">
		<form name = "formm" method = "post" action = "./addSQL.php" style="width: 700px; display: inline-block;">
			<br> I &nbsp; &nbsp; D &nbsp;:  <input TYPE = "text" id = "id" NAME = "id" required>
			<br> 이 &nbsp; 름 : <input TYPE = "text" id = "name" NAME = "name" required>
                      <div style = "text-align: left; margin-left: 120px;">			
			<br> 카테고리: <select name="category">
				<option value="korean">카테고리를 선택해주세요.</option>
				<option value="korean">한식</option>
				<option value="asian">아시안/양식</option>
				<option value="japanese">돈까스/회/일식</option>
				<option value="dessert">카페/디저트</option>
				<option value="fastfood">패스트푸드</option>
				<option value="steamed">찜/탕</option>
				<option value="chicken">치킨</option>
				<option value="pizza">피자</option>
				<option value="casualfood">분식</option>
			</select>
		      </div>
			<br> 주 &nbsp; 소 : <input TYPE = "text" id = "address" NAME = "address" required>
			<br> 전화번호 : <input TYPE = "text" id = "pn" NAME = "pn" required>
			<br> 영업시간 : <input TYPE = "text" id = "time" NAME = "time" >
			<div style="text-align: left; margin-left: 120px;">
                        <br> 배 &nbsp; 달 : <input TYPE = "checkbox" id = "deliver" value="가능" NAME = "deliver" >
			<br> 포 &nbsp; 장 : <input TYPE = "checkbox" id = "takeout" value="가능" NAME = "takeout" >
                        </div>
			<br> 이미지 : <input TYPE = "text" id = "image" NAME = "image" >
			<br> 태 &nbsp; 그 : <input TYPE = "text" id = "tag" NAME = "tag" >
		</form>
		<INPUT TYPE="button" value="등록" onClick="javascript:document.formm.submit();" style="border-radius : 8px; background-color: rgb(255,145,70); color: white; width: 100px; font-size: 30px; border-color: white;">
                <div style="display : block;position: relative; width: 400px; left: -405px;bottom:108px;right:0px;">
                <input type="text" id="val" placeholder="장소 입력" value="<?php if($_POST['query']) echo $_POST['query'];?>" onkeypress="Enter()" style = "width:400px;">
		<img src="https://s3.ap-northeast-2.amazonaws.com/cdn.wecode.co.kr/icon/search.png" onmousedown="printPlace()" >
                </div>

               <script>
			var map;
			var markers = [];
			
			kakao.maps.load(function() {
	    		var mapContainer = document.getElementById("map");
	    		
	    		var options = {
	        		center: new kakao.maps.LatLng(37.6023222243288, 126.955350026719),
					level: 3
	    		};
				
	    		kakao.maps.load(function() {
	        		map = new kakao.maps.Map(mapContainer, options);
	    		});	

	    		marker = new kakao.maps.Marker({
	    			map: map,
	    	    	position: new kakao.maps.LatLng(37.6023222243288, 126.955350026719)
	    		});
	    		marker.setMap(map);
	    		markers.push(marker);
			});
		
			var callback = function(result, status) {
		    	if (status === kakao.maps.services.Status.OK) {
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

			map.setDraggable(false);
		
			function setMarkers(map) {
		    	for (var i = 0; i < markers.length; i++) {
		        	markers[i].setMap(map);
		    	}            
			}
			
			function hideMarkers(){
				setMarkers(null);
			}
		
			var places = new kakao.maps.services.Places(map);
		
			function printPlace(){
				places.keywordSearch(document.getElementById('val').value, callback, 'FD6');
			}
		
			function Enter(){
				if(event.keyCode==13){
					printPlace();
				}
			}
		</script>
	</div>
      </div>
     </section>
	</body>
</html>
