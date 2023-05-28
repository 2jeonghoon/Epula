<?php 
    header('Content-Type: text/html; charset=UTF-8');
    include("./SQLconstants.php");
    include("./WriteLog.php");
    
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $address = $_POST['address'];
    $pn = $_POST['pn'];
    $time = $_POST['time'];
    $deliver = $_POST['deliver'];
    $takeout = $_POST['takeout'];
    $image = $_POST['image'];
    $tag = $_POST['tag'];
    $message = "";
    
    // MySQL 드라이버 연결
    $conn = mysqli_connect( $mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database ) or die( "Can't access DB" );
    
    $query = "SELECT restaurant_id FROM Restaurants WHERE restaurant_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_array($result)) {
	        $id_e = $row['restaurant_id'];
    }

    // MySQL 식당 추가 실행
    if( is_null( $id_e ) ) {
		$query = "INSERT INTO Restaurants( restaurant_id, name, category, address, phone, opening_hour, delivery, take_out, picture, tag ) VALUES ( '$id', '$name', '$category', '$address', '$pn', '$time', '$deliver', '$takeout', '$image', '$tag');";
		$result = mysqli_query( $conn, $query );
        	$message = "식당".$id_e.$intId."dd".$id." (".$name.")을 등록했습니다.";
    }
    else {
        $message = "식당 (".$name.")을 등록하지 못했습니다. 이미 존재하는 식당입니다.";
    }
    
    // MySQL 드라이버 연결 해제
    mysqli_free_result( $result );
    mysqli_close( $conn );
    log_write(session_id(), $message);
?>
<form name = "frm" method = "post" action = "./MapMain.php" >
	<input type = 'hidden' name = 'message' value = ' * <?php echo $message;?>' >
</form>
<script language="javascript">
	document.frm.submit();
</script>
