<?php
header('Content-Type: text/html; charset=UTF-8');
include("./SQLconstants.php");
include("./WriteLog.php");

session_start();

$review = $_POST['my_review_id'];
$val = $_POST['ldlsubmit'];
$selected_user_id = $_POST['selected_user'];

// MySQL 드라이버 연결
$conn = mysqli_connect( $mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database ) or die( "Can't access DB" );

$query = "select * from Reviews where review_id = '".$review."'";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_array($result)){
    $r_id = $row['restaurant_id'];
}

$query = "select * from Restaurants where restaurant_id = ".$r_id."";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_array($result)){
    $r_name = $row['name'];
}

$query = "SELECT * FROM Users WHERE user_id='".$selected_user_id."'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$selected_user_gourmet = $row['gourmet_score'];
log_write($_SESSION['session_id'], $selected_user_id."의 식객지수: ".$selected_user_gourmet);

// MySQL 좋아요 / 싫어요 실행
if(!is_null($_SESSION['user_id'] )) {
	if($val == "좋아요"){
		$query = "update Reviews set likes = likes + 1 where review_id = '$review'";
		$result = mysqli_query($conn, $query);
        	
		if ($selected_user_gourmet < 100 ) {
			$query = "update Users set gourmet_score = round(gourmet_score + 0.1, 1) where user_id = '".$selected_user_id."'";
			$result = mysqli_query($conn, $query);
		}
	}
	else if($val == "싫어요"){
		$query = "update Reviews set dislikes = dislikes + 1 where review_id = '$review'";
		$result = mysqli_query($conn, $query);

		if ($selected_user_gourmet > 0) {
			$query = "update Users set gourmet_score = round(gourmet_score - 0.1, 1) where user_id = '".$selected_user_id."'";
			$result = mysqli_query($conn, $query);
		}
	}
	$query = "select * from Users where user_id = '".$selected_user_id."'";
	$result = mysqli_query($conn, $query);
	while($row = mysqli_fetch_array($result)){
		$_SESSION['gourmet_score'] = $row['gourmet_score'];
	}
	$message = $val." 누름";
}
else {
	$message = "좋아요 / 싫어요 실패";
}

// MySQL 드라이버 연결 해제
mysqli_free_result( $result );
mysqli_close( $conn );
log_write(session_id(), $message);
?>
<form name="frm" method="post" action="./review.php">
    <input type='hidden' name='restaurant_name' value='<?php echo $r_name;?>'>
    <input type='hidden' name='restaurant_id' value='<?php echo $r_id;?>'>
</form>
<script language="javascript">
    document.frm.submit();
</script>
