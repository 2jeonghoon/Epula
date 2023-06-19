<?php
header('Content-Type: text/html; charset=UTF-8');
include("./SQLconstants.php");
include("./WriteLog.php");

session_start();

$review = $_POST['my_review_id'];


// MySQL 드라이버 연결
$conn = mysqli_connect( $mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database ) or die( "Can't access DB" );

$query = "select * from Reviews where review_id = '".$review."'";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_array($result)){
    $r_id = $row['restaurant_id'];
    $cnt = $row['review_id'];
}

$query = "select * from Restaurants where restaurant_id = ".$r_id."";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_array($result)){
    $r_name = $row['name'];
}

// MySQL 리뷰 삭제 실행
if(!is_null($_SESSION['user_id'] )) {
    $query = "Delete From Reviews where content = '".$review."'";
    $result = mysqli_query( $conn, $query );
    $message = "review id: ".$cnt." user_id: ".$_SESSION['user_id']." r_id: ".$r_id." 리뷰를 삭제했습니다.";
}
else {
    $message = "리뷰를 삭제하지 못했습니다.";
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