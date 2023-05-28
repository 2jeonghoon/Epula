<?php 
    function log_write($userid, $str) {
        $time = date('Y-m-d H:i:s');
        $pageName = basename($_SERVER['PHP_SELF']);
        
        $fp = fopen('./logs/'.$userid.'_log.txt', 'a');
        fwrite($fp, "\nTime: ".$time." Here: ".$pageName." Message : ".$str);
        fclose($fp);
    }
?>
