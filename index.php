<?php
// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';
// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv
('CHANNEL_ACCESS_TOKEN'));
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv(
'CHANNEL_SECRET')]);
// LINE Messaging APIがリクエストに付与した署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader
::LINE_SIGNATURE];
// 署名が正当かチェック。正当であればリクエストをパースし配列へ
$events = $bot->parseEventRequest(file_get_contents('php://input'),
$signature);
// 配列に格納された各イベントをループで処理
foreach ($events as $event) {
// テキストを返信
replyTextMessage($bot, $event->getReplyToken(), 'TextMessage');
}

// テキストを返信。引数はLINEBot、返信先、テキスト
function replyTextMessage($bot, $replyToken, $text) {
    // 返信を行いレスポンスを取得
    // TextMessageBuilderの引数はテキスト
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot
    \MessageBuilder\TextMessageBuilder($text));
    // レスポンスが異常な場合
    if (!$response->isSucceeded()) {
    // エラー内容を出力
    error_log('Failed! '. $response->getHTTPStatus . ' '
    . $response->getRawBody());
    }
}

// 位置情報を返信。引数はLINEBot、返信先、タイトル、
// 住所、緯度、経度
function replyLocationMessage($bot, $replyToken, $title, $address,
$lat, $lon) {
// LocationMessageBuilderの引数はダイアログのタイトル、
// 住所、緯度、経度
$response = $bot->replyMessage($replyToken, new \LINE\LINEBot\
MessageBuilder\LocationMessageBuilder(
$title, $address, $lat, $lon));
if (!$response->isSucceeded()) {
error_log('Failed!'. $response->getHTTPStatus . ' ' .
$response->getRawBody());
    }
}

// スタンプを返信。引数はLINEBot、返信先、
// スタンプのパッケージID、スタンプID
function replyStickerMessage($bot, $replyToken, $packageId, $stickerId) {
    // StickerMessageBuilderの引数はスタンプのパッケージID、スタンプID
    $response = $bot->replyMessage($replyToken,new \LINE\LINEBot\
    MessageBuilder\StickerMessageBuilder(
    $packageId, $stickerId));
    if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' .
    $response->getRawBody());
    }
    }
?>