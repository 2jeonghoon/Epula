<?php
    header('Content-Type: text/html; charset=UTF-8');
    include './SQLconstants.php';
    include './WriteLog.php';

    session_start();

    $id = $_POST['id'];
    $text = $_POST['text'];
    $delete_restaurant = $_POST['delete_restaurant'];
    $delete_request = $_POST['delete_request'];
    $message = "";
    
    // MySQL 드라이버 연결
    $conn = mysqli_connect( $mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database ) or die( "Can't access DB" );

    if(!is_null($delete_restaurant)) {
        $query = "DELETE FROM RestaurantsToDelete WHERE restaurant_id = '$delete_restaurant'";
	$result = mysqli_query($conn, $query);
	
	$query = "DELETE FROM Restaurants WHERE restaurant_id = '$delete_restaurant'";
	$result = mysqli_query($conn, $query);
    }

    if(!is_null($delete_request)) {
	$query = "DELETE FROM RestaurantsToDelete WHERE restaurant_id = '$delete_request'";
	$result = mysqli_query($conn, $query);
    }

    // MYSQL 식당 삭제 실행
    $stmt = $conn->stmt_init();
    $stmt = $conn->prepare("SELECT restaurant_id FROM RestaurantsToDelete WHERE restaurant_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt = $conn->prepare("SELECT restaurant_id FROM Restaurants WHERE restaurant_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result2 = $stmt->get_result();
    $row2 = $result2->fetch_assoc();

    if(is_null($row['restaurant_id']) && !is_null($row2['restaurant_id'])) {
	$message = "식당 (".$id.")삭제 요청에 성공하였습니다.";
    } else if (is_null($row2['restaurant_id'])){
	$message = "식당 (".$id.")은 등록되지 않은 식당입니다.";
    }
    else {	
        $message = "식당 (".$id.")삭제에 실패하였습니다. 이미 요청이 등록된 식당이거나, 삭제시 이름이 아니고 ID를 입력해주세요.";
    }
    

    $stmt = $conn->prepare("INSERT INTO RestaurantsToDelete (restaurant_id, text) VALUES (?, ?)");
    $stmt->bind_param("ss", $id, $text);
    $stmt->execute();
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
