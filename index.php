<?php

//Composerでインストールしたライブラリの一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient,['channelSecret' => getenv('CHANNEL_SECRET')]);
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

foreach ($events as $event){
	//テキストを送信
//	$bot->replyText($event->getReplyToken(), 'TextMessage');
	replyTextMessage($bot, $event->getReplyToken(),'TextMessage');
}

//テキストを送信。引数はLINEBot、返信先、テキスト
function replyTextMessage($bot, $replyToken, text){
	//返信を行いレスポンスを取得
	//TextMessageBuilderの引数はテキスト
	$responce = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBulder\TextMessageBuilder($text));
	//レスポンスが異常な場合
	error_log('Failed!', $responce->getHTTPStatus .' '. $responce->getRawBody());
	}
}

?>
