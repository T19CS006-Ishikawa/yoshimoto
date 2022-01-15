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
//	replyTextMessage($bot, $event->getReplyToken(),'TextMessage');
//	replyImageMessage($bot, $event->getReplyToken(),'https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg','https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg');
	// スタンプを返信
//	replyStickerMessage($bot, $event->getReplyToken(), 1, 1);
  // 複数のメッセージをまとめて返信
  replyMultiMessage($bot, $event->getReplyToken(),
    new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('TextMessage'),
    new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder('https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg', 'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg'),
    new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(1, 1)
  );

}

//テキストを送信。引数はLINEBot、返信先、テキスト
function replyTextMessage($bot, $replyToken, $text){
	//返信を行いレスポンスを取得
	//TextMessageBuilderの引数はテキスト
	$responce = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

	//レスポンスが異常な場合
	if(!$responce->isSucceeded()){
	error_log('Failed!'. $responce->getHTTPStatus .' '. $responce->getRawBody());
	}
}

//画像を返信。引数はLINE Bot、返信先、画像URL、サムネイルURL
function replyImageMessage($bot, $replyToken, $originalImageUrl,$previewImageUrl){
	//ImageMessageBuilderの引数は画像url,サムネイルurl
	$responce = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl,$previewImageUrl));
	if(!$responce->isSucceeded()){
	error_log('Failed!'. $responce->getHTTPStatus .' '. $responce->getRawBody());
	}
}

// スタンプを返信。引数はLINEBot、返信先、
// スタンプのパッケージID、スタンプID
function replyStickerMessage($bot, $replyToken, $packageId, $stickerId) {
	// StickerMessageBuilderの引数はスタンプのパッケージID、スタンプID
	$response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder($packageId, $stickerId));
	if (!$response->isSucceeded()) {
		error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
	}
}

// 複数のメッセージをまとめて返信。引数はLINEBot、返信先、メッセージ(可変長引数)
function replyMultiMessage($bot, $replyToken, ...$msgs) {
  // MultiMessageBuilderをインスタンス化
  $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
  // ビルダーにメッセージを全て追加
  foreach($msgs as $value) {
    $builder->add($value);
  }
  $response = $bot->replyMessage($replyToken, $builder);
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}


?>
