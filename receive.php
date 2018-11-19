<?php
$json_str = file_get_contents('php://input'); //接收request的body
  $json_obj = json_decode($json_str); //轉成json格式
  
  $myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
  fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  
  $sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
  $sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
  $sender_replyToken = $json_obj->events[0]->replyToken; //取得訊息的replyToken
  
  $imageId = $json_obj->events[0]->message->id; //取得訊息編號
  $url = 'https://api.line.me/v2/bot/message/'.$imageId.'/content';
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer f9J+SIcrrv+iLO/NmtQrJNaLYJZ+EKIrRm/G3c7HD9V1xij+L39EZNWwzXNgl7yM0g/fFlbFXqzOwPBurRuuytkEhRgoboIBASmDphR3Z8ORK02HZhZ4e3YOkaetKj5LY2XzuuBDuKiv4yuqv/e4yAdB04t89/1O/w1cDnyilFU='
  ));

  $json_content = curl_exec($ch);
  curl_close($ch);
  $imagefile = fopen($imageId.".jpeg", "w+") or die("Unable to open file!");
  fwrite($imagefile, $json_content); 
  fclose($imagefile); //將圖片存在server上
  //先在line，上傳一張圖片
  //在瀏覽器中輸入: https://sporzfy.com/class/20181119_line/log.txt，會在裏頭顯示image type的id
  //可以在網址上輸入: https://sporzfy.com/class/20181119_line/8884441406951.jpeg (8884441406951是image type的id )
  


  $header[] = "Content-Type: application/json";
  $post_data = array (
	"requests" => array (
	  array (
		"image" => array (
		  "source" => array (
			"imageUri" => "http://139.59.123.8/class/20181119_line/".$imageId.".jpeg"  //20181119_line是jenkins的專案
		  )
		),
		"features" => array (
		  array (
			"type" => "TEXT_DETECTION",
			"maxResults" => 1
		  )
		)
	  )
	)
  );
?>
