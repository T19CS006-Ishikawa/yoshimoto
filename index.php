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
 /* replyMultiMessage($bot, $event->getReplyToken(),
    new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('TextMessage'),
    new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder('https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg', 'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg'),
    new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(1, 1)
  );
*/  replyButtonsTemplate($bot,
    $event->getReplyToken(),
    'お天気お知らせ - 今日は天気予報は晴れです',
    'https://' . $_SERVER['HTTP_HOST'] . '/imgs/template.jpg',
    'お天気お知らせ',
    '今日は天気予報は晴れです',
    // タップ時、テキストをユーザーに発言させるアクション
    new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder (
      '明日の天気', 'tomorrow'),
    // タップ時、テキストをBotに送信するアクション(トークには表示されない)
    new LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder (
      '週末の天気', 'weekend'),
    // タップ時、URLを開くアクション
    new LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder (
      'Webで見る', 'http://google.jp')
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

// Buttonsテンプレートを返信。引数はLINEBot、返信先、代替テキスト、画像URL、タイトル、本文、アクション(可変長引数)
function replyButtonsTemplate($bot, $replyToken, $alternativeText, $imageUrl, $title, $text, ...$actions) {
  // アクションを格納する配列
  $actionArray = array();
  // アクションを全て追加
  foreach($actions as $value) {
    array_push($actionArray, $value);
  }
  // TemplateMessageBuilderの引数は代替テキスト、ButtonTemplateBuilder
  $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
    $alternativeText,
    // ButtonTemplateBuilderの引数はタイトル、本文、
    // 画像URL、アクションの配列
    new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder ($title, $text, $imageUrl, $actionArray)
  );
  $response = $bot->replyMessage($replyToken, $builder);
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

?>
