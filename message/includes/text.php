<?php
/**
 * Copyright 2017 GoneTone
 *
 * Line Bot
 * 範例 Example Bot (Text)
 *
 * 此範例 GitHub 專案：https://github.com/GoneTone/line-example-bot-php
 * 官方文檔：https://developers.line.biz/en/reference/messaging-api#text-message
 */
/**
陣列輸出 Json
==============================
{
    "type": "text",
    "text": "Hello, world!"
}
==============================
*/
// 調整為收收到文字訊息轉發至 notify api   
$headers = [
  'Content-Type: application/x-www-form-urlencoded',
  'Authorization: Bearer Zl4QpMoFU2hPihfpDJNehMv1ARSuKWLcdYh7CVk8WYk'
];
$data['message'] = $message['text']??'小廢物你好';
// 備忘錄 Sv0HqJizpB1T267Qk20PKHsPBwE5c7VXrp39UI5um62
// 正式 Zl4QpMoFU2hPihfpDJNehMv1ARSuKWLcdYh7CVk8WYk
$result = post_CURL("https://notify-api.line.me/api/notify", http_build_query($data),$headers); 

// if (strtolower($message['text']) == "text" || $message['text'] == "文字"){
//     $client->replyMessage(array(
//         'replyToken' => $event['replyToken'],
//         'messages' => array(
//             array(
//                 'type' => 'text', // 訊息類型 (文字)
//                 'text' => 'Hello, world!' // 回復訊息
//             )
//         )
//     ));
// }

function post_CURL($url, $data, $headers = null, $debug = false, $CA = false, $CApem = "", $timeout = 30)
    {
        //網址,資料,header,返回錯誤訊息,https時驗證CA憑證,CA檔名,超時(秒)
        global $path_cacert;
        $result = "";
        $cacert = $path_cacert . $CApem;
        //CA根证书
        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL && $CA && $CApem == "") {
            return "請指定CA檔名";
        }
        if ($headers == null) {
            $headers = [
                'Content-Type: application/x-www-form-urlencoded',
            ];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        //允許執行的最長秒數
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout - 2);
        //連接前等待時間(0為無限)
        //$headers == '' ? '' : curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($SSL && $CA) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            // 驗證CA憑證
            curl_setopt($ch, CURLOPT_CAINFO, $cacert);
            // CA憑證檔案位置
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        } elseif ($SSL && !$CA) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // 信任任何憑證
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        if ($debug === true && curl_errno($ch)) {
            echo 'GCM error: ' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
?>
