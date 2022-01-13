<?php

//Composer�ŃC���X�g�[���������C�u�����̈ꊇ�ǂݍ���
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient,['channelSecret' => getenv('CHANNEL_SECRET')]);
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

foreach ($events as $event){
	//�e�L�X�g�𑗐M
//	$bot->replyText($event->getReplyToken(), 'TextMessage');
//	replyTextMessage($bot, $event->getReplyToken(),'TextMessage');
	replyImageMessage($bot, $event->getReplyToken(), 'https://' .
	$_SERVER['HTTP_HOST'] .
	'/imgs/orginal.jpg','https://' . $_SERVER['HTTP_HOST'] . 
	'/imgs/pewview.jpg');
}

//�e�L�X�g�𑗐M�B������LINEBot�A�ԐM��A�e�L�X�g
function replyTextMessage($bot, $replyToken, $text){
	//�ԐM���s�����X�|���X���擾
	//TextMessageBuilder�̈����̓e�L�X�g
	$responce = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

	//���X�|���X���ُ�ȏꍇ
	if(!$responce->isSucceeded()){
	error_log('Failed!'. $responce->getHTTPStatus .' '. $responce->getRawBody());
	}
}

//�摜��ԐM�B������LINE Bot�A�ԐM��A�摜URL�A�T���l�C��URL
function replyImageMessage($bot, $replyToken, $originalImageUrl,$previewImageUrl){
	//ImageMessageBuilder�̈����͉摜url,�T���l�C��url
	$responce = $bot->replyMessage($replyToken, new \LINE\LINEbot\MessageBuilder\TextMessageBuilder(%text));
	if(!$responce->isSucceeded()){
	error_log('Failed!'. $responce->getHTTPStatus . ' ' . $responce->getRawBody());
	}
}

?>
