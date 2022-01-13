<?php

//Composerでインストールしたライブラリの一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient,['chanelSecret => getenv('ChannelSecret' => getenv('CHANNEL_SECRET')]);
$signature = $_SERVER['HTTP_' , \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
$event = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

foreach($events as $event){
　//テキストを送信
　$bot->replyText($event->getReplyToken(), 'TextMessage');
}

?>
