<?php
// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';
// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv
('Rde3/I/TFVtd50VVzUEcuCswK7lIonOQL3yMZ6c5LE2sSdl/XUkLNSlAtX1w/GP1eUJ56sPTYT+R12Znwx0p/h7jBsLqe3UQHe7WejJoyjbZY/+nC5jp8L7I7IMLaennOZu1ECMwTV9MSMFDe80JnAdB04t89/1O/w1cDnyilFU='));
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv(
'f95acfbb398e6832ef3ce8734109856c')]);
// LINE Messaging APIがリクエストに付与した署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader
::LINE_SIGNATURE];
// 署名が正当かチェック。正当であればリクエストをパースし配列へ
$events = $bot->parseEventRequest(file_get_contents('php://input'),
$signature);
// 配列に格納された各イベントをループで処理
foreach ($events as $event) {
// テキストを返信
$bot->replyText($event->getReplyToken(), 'TextMessage');
}
?>