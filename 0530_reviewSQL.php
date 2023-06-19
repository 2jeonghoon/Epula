<?php 
    header('Content-Type: text/html; charset=UTF-8');
    include("./SQLconstants.php");
    include("./WriteLog.php");

    session_start();

    $user_id = $_POST['user_id'];
    $review = $_POST['review'];
    $like = 0;
    $dislike = 0;
    $time = date("Y-m-d H:i:s");
    $score = $_POST['score'];
    $r_id = $_POST['restaurant_id'];

    
    // MySQL 드라이버 연결
    $conn = mysqli_connect( $mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database ) or die( "Can't access DB" );
 
    $query = "select * from Restaurants where restaurant_id = ".$r_id."";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result)){
        $r_name = $row['name'];
    }
    
    $cnt = 0;
    $query = "select * from Reviews";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result)){
        ++$cnt;
    }
    ++$cnt;
    
    // MySQL 리뷰 추가 실행
    if(!is_null($_SESSION['user_id'] )) {

		$query = "INSERT INTO Reviews ( review_id, user_id, restaurant_id, upload_date, score, content, likes, dislikes ) VALUES ( '$cnt', '$user_id', '$r_id', '$time', '$score', '$review', '$like', '$dislike')";
		$result = mysqli_query( $conn, $query );
        	$message = "review id: '$cnt' user_id: '$user_id' r_id: '$r_id' score: '$score' 리뷰를 등록했습니다.";
    }
    else {
        $message = "리뷰를 등록하지 못했습니다.";
    }
    
    // MySQL 드라이버 연결 해제
    mysqli_free_result( $result );
    mysqli_close( $conn );
    log_write(session_id(), $message);
?>
<form name = "frm" method = "post" action = "./review.php" >
	<input type = 'hidden' name = 'restaurant_name' value = '<?php echo $r_name;?>' >
	<input type = 'hidden' name = 'restaurant_id' value = '<?php echo $r_id;?>' >
</form>
<script language="javascript">
	document.frm.submit();
</script>
