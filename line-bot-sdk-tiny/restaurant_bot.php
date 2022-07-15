<?php // 参考：https://ysklog.net/line_bot/4551.html 
require('./Weather.php');
require('./webhook.php');
//https://developers.line.biz/ja/reference/messaging-api/#buttons LINEAPIリファレンス
// https://ohf2022a.mods.jp/hands-on/LINEbot/2/HanayamaSyota/restaurant_bot.php

$accessToken = 'Rde3/I/TFVtd50VVzUEcuCswK7lIonOQL3yMZ6c5LE2sSdl/XUkLNSlAtX1w/GP1eUJ56sPTYT+R12Znwx0p/h7jBsLqe3UQHe7WejJoyjbZY/+nC5jp8L7I7IMLaennOZu1ECMwTV9MSMFDe80JnAdB04t89/1O/w1cDnyilFU=';

//ユーザIDの取得

$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);

//取得データ
$replyToken = $json_object->{"events"}[0]->{"replyToken"};        //返信用トークン
$message_type = $json_object->{"events"}[0]->{"message"}->{"type"};    //メッセージタイプ
$message_text = $json_object->{"events"}[0]->{"message"}->{"text"};    //メッセージ内容
$LINE_user_id = $json_object->{"events"}[0]->{"source"}->{"userId"};   //LINE ユーザーID
$event_type = $json_object->{"events"}[0]->{"type"}; //イベントタイプ　ポストバックを受け取るときに使用
$postback_data = $json_object->{"events"}[0]->{"postback"}->{"data"}; //ポストバックしたデータ名

// debug用のログ：送信データ 
$file = 'recv_log.txt';
$date_time = date("Y-m-d H:i:s");
file_put_contents($file, $date_time . PHP_EOL, FILE_APPEND);
file_put_contents($file, $replyToken . PHP_EOL, FILE_APPEND);
file_put_contents($file, $message_type . PHP_EOL, FILE_APPEND);
file_put_contents($file, $message_text . PHP_EOL, FILE_APPEND);
file_put_contents($file, 'LINEUser:' . $LINE_user_id . PHP_EOL, FILE_APPEND);
file_put_contents($file, 'JSON:' . $json_string . PHP_EOL, FILE_APPEND);
file_put_contents($file, PHP_EOL . PHP_EOL, FILE_APPEND);

//レスポンスフォーマット デフォルト

if ($message_type == "location") {  //-------- 位置情報が送られた時
    #位置情報が設定されていない場合
    $lat=$json_object->{"events"}[0]->{"message"}->{"latitude"};
    $lon=$json_object->{"events"}[0]->{"message"}->{"longitude"};
    $adr=$json_object->{"events"}[0]->{"message"}->{"address"};
    $messageData = [
        'type' => 'template',
        'altText' => '位置情報登録確認メッセージ',
        'template' => [
            'type' => 'confirm',
            #住所を表示する
            'text' => $adr.
'この位置情報を登録しますか？。',//確認ボタンの上部のメッセージ部分
            'actions' => [
                [
                    'type' => 'message',
                    'label' => 'はい', //確認ボタンに表示させたい文字
                    'text' => 'データベースに登録する関数を呼び出す', //ボタンを押した際に送信させる文字(ボタンを押したタイミングLINE上に表示)
                ],
                [
                    'type' => 'message',
                    'label' => 'いいえ', //確認ボタンに表示させたい文字
                    'text' => 'キャンセル', //ボタンを押した際に送信させる文字(ボタンを押したタイミングLINE上に表示)
                ],
            ],
        ],
    ];
    reply($replyToken, $messageData, $accessToken);
}

if ($message_type == "text") {
    if (strcmp($message_text, "使い方") == 0) {
        $return_message_text = "使い方は以下の通りです。\r\n";
        $return_message_text .= "・お店をさがす\r\n →「ユーザ設定」で設定した位置情報から、休み時間内に行くことが可能な店を表示します。\r\n\n";
        $return_message_text .= "・お店の感想\r\nお店の感想(味・混み具合)を登録できます。ショップIDをコピーしてください。\r\n\n";
        $return_message_text .= "・つかいかたガイド\r\n →このBotの使い方を表示します。\r\n\n";
        $return_message_text .= "・ユーザ設定\r\n →位置情報や休み時間の開始、終了時刻を登録、変更できます。";
        $response_format = text_format($return_message_text);
        reply($replyToken, $response_format, $accessToken);
    } elseif (strcmp($message_text, "時刻") == 0  || strcmp($message_text, "時間") == 0) {
        $today = date("ただ今の時刻はY-m-d H:i:sです。");
        $return_message_text = $today;
        $response_format = text_format($return_message_text);
        reply($replyToken, $response_format, $accessToken);
    } elseif (strcmp($message_text, "設定") == 0) {
        //ユーザのIDを取得し、DBに存在しなければ新しく登録する

        $messageData = [
            'type' => 'template',
            'altText' => '設定メッセージ',
            'template' => [
                'type' => 'buttons',
                'text' => 'ユーザの設定をします。
登録または変更する項目を選んでください。', //ボタンの上部のメッセージ部分
                'actions' => [
                    [
                        'type' => 'message',
                        'label' => '位置情報', //ボタンに表示させたい文字
                        'text' => '位置情報の設定', //ボタンを押した際に送信させる文字(ボタンを押したタイミングでLINE上に表示)
                    ],
                    [
                        'type' => 'message',
                        'label' => '休憩時間',
                        'text' => '休憩時間の設定',
                    ],
                    [
                        'type' => 'message',
                        'label' => '戻る',
                        'text' => 'キャンセル',
                    ],
                ],
            ],
        ];
        $response_format = $messageData;
        reply($replyToken, $response_format, $accessToken);
    } else if (strcmp($message_text, "お店を探す") == 0) {
        #データベースから位置情報取得
        #テスト用の位置情報
        $lat = 36.063513;
        $lon = 136.222748;
        $return_message_text = get_restaurant_information($lat, $lon);
        $response_format = text_format($return_message_text);
        reply($replyToken, $response_format, $accessToken);
    } else if (strcmp($message_text, "位置情報の設定") == 0) {
        $messageData = [
            'type' => 'template',
            'altText' => '位置情報設定メッセージ',
            'template' => [
                'type' => 'buttons',
                'text' => '位置情報を登録します。
「位置情報を送る」ボタンを押してください。',
                'actions' => [
                    [
                        'type' => 'uri',
                        'label' => '位置情報を送る',
                        'uri' => 'line://nv/location',
                    ],
                ],
            ],
        ];
        $response_format = $messageData;
        reply($replyToken, $response_format, $accessToken);
    } else if (strcmp($message_text, "キャンセル") == 0) {
        $return_message_text = "キャンセルされました。";
        $response_format = text_format($return_message_text);
        reply($replyToken, $response_format, $accessToken);
    } else {
        $return_message_text = "そのメッセージは対応していません。";
        $response_format = text_format($return_message_text);
        reply($replyToken, $response_format, $accessToken);
    }

}

//ポストデータ 
function reply($replyToken, $response_format, $accessToken)
{
    $post_data = [
        "replyToken" => $replyToken,
        "messages" => [$response_format],
    ];
    //curl実行 Webサーバ → LINE サーバ 
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}
