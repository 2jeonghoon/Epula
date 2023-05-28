<?php
    header('Content-Type: text/html; charset=UTF-8');
    include './SQLconstants.php';
    include './WriteLog.php';

    session_start();

    $id = $_POST['id'];
    $message = "";
    
    // MySQL 드라이버 연결
    $conn = mysqli_connect( $mySQL_host, $mySQL_id, $mySQL_password, $mySQL_database ) or die( "Can't access DB" );
    
    // MYSQL 식당 삭제 실행
    $query = "delete from Restaurants where restaurant_id = '".$id."';";
    $result = mysqli_query( $conn, $query );
    if( !$result ){
        $message = "식당 (".$id.")삭제에 실패하였습니다. 삭제시 이름이 아니고 ID를 입력해주세요.";
    }
    else{
        $message = "식당 (".$id.")삭제에 성공하였습니다.";
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
