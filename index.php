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
//	replyImageMessage($bot, $event->getReplyToken(),'https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg','https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg');
	//�ʒu����ԐM
	replyLocationMessage($bot, $event->getReplyToken(), 'LINE', '�����s�a�J��a�J2-21-1 �q�J���G27�K', 35.659025, 139.703473);
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
	$responce = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl,$previewImageUrl));

	if(!$responce->isSucceeded()){
	error_log('Failed!'. $responce->getHTTPStatus .' '. $responce->getRawBody());
	}
}

// �ʒu����ԐM�B������LINEBot�A�ԐM��A�^�C�g���A�Z���A
// �ܓx�A�o�x
function replyLocationMessage($bot, $replyToken, $title, $address, $lat, $lon) {
  // LocationMessageBuilder�̈����̓_�C�A���O�̃^�C�g���A�Z���A�ܓx�A�o�x
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\LocationMessageBuilder($title, $address, $lat, $lon));
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

?>
