<?php
  $json_str = file_get_contents('php://input'); //接收request的body
  $json_obj = json_decode($json_str); //轉成json格式
  
  $myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
  fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  
  $sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
  $sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
  $sender_replyToken = $json_obj->events[0]->replyToken; //取得訊息的replyToken
  
  switch ($sender_txt) {
    case "請告訴我妳的名字":  //圖文選單中連結欄位的文字
			$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "text",
						"text" => "Hello. This is push. You say ".$sender_txt
					)
				)
			);
      break;
    case "想要吃甚麼":
			$line_server_url = 'https://api.line.me/v2/bot/message/reply';
			$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "location",
						"title" => "my location",
						"address" => "〒150-0002 東京都渋谷区渋谷２丁目２１−１",
						"latitude" => 35.65910807942215,
						"longitude" => 139.70372892916203
					)
				)
			);
			break;
		case "你喜歡玩甚麼":
			$line_server_url = 'https://api.line.me/v2/bot/message/reply';
			$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "sticker",
						"packageId" => "1",
						"stickerId" => "1"
					)
				)
			);
			break;
		case "你要從哪出發":
			$line_server_url = 'https://api.line.me/v2/bot/message/reply';
			$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "template",
						"altText" => "this is a buttons template",
						"template" => array (
							"type" => "buttons",
							"thumbnailImageUrl" => "https://www.w3schools.com/css/paris.jpg",
							"title" => "Menu",
							"text" => "Please select",
							"actions" => array (
								array (
									"type" => "postback",
									"label" => "Buy",
									"data" => "action=buy&itemid=123"
								),
								array (
									"type" => "postback",
									"label" => "Add to cart",
									"data" => "action=add&itemid=123"
								)
							)
						)
					)
				)
			);
			break;
  }

  fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  $header[] = "Content-Type: application/json";
  $header[] = "Authorization: Bearer f9J+SIcrrv+iLO/NmtQrJNaLYJZ+EKIrRm/G3c7HD9V1xij+L39EZNWwzXNgl7yM0g/fFlbFXqzOwPBurRuuytkEhRgoboIBASmDphR3Z8ORK02HZhZ4e3YOkaetKj5LY2XzuuBDuKiv4yuqv/e4yAdB04t89/1O/w1cDnyilFU=";
  $ch = curl_init("https://api.line.me/v2/bot/message/reply");
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
  $result = curl_exec($ch);
  curl_close($ch);
?>
