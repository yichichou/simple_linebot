<?php
  $json_str=file_get_contents('php://input');  //接收requests的body
  $json_obj=json_decode($json_str); //轉成json格式
  
  $myfile = fopen("log.txt","w+") or die  ("Unable to open file !"); //設定一個log.txt來印訊息
  fwrite($myfile, "\xEF\xBB\xBF".$json_str); //轉成UTF8格式
?>
