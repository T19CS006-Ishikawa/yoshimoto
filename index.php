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
	// �X�^���v��ԐM
//	replyStickerMessage($bot, $event->getReplyToken(), 1, 1);
  // �����̃��b�Z�[�W���܂Ƃ߂ĕԐM
 /* replyMultiMessage($bot, $event->getReplyToken(),
    new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('TextMessage'),
    new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder('https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg', 'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg'),
    new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(1, 1)
  );
*/  replyButtonsTemplate($bot,
    $event->getReplyToken(),
    '���V�C���m�点 - �����͓V�C�\��͐���ł�',
    'https://' . $_SERVER['HTTP_HOST'] . '/imgs/template.jpg',
    '���V�C���m�点',
    '�����͓V�C�\��͐���ł�',
    // �^�b�v���A�e�L�X�g�����[�U�[�ɔ���������A�N�V����
    new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder (
      '�����̓V�C', 'tomorrow'),
    // �^�b�v���A�e�L�X�g��Bot�ɑ��M����A�N�V����(�g�[�N�ɂ͕\������Ȃ�)
    new LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder (
      '�T���̓V�C', 'weekend'),
    // �^�b�v���AURL���J���A�N�V����
    new LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder (
      'Web�Ō���', 'http://google.jp')
  );
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

// �X�^���v��ԐM�B������LINEBot�A�ԐM��A
// �X�^���v�̃p�b�P�[�WID�A�X�^���vID
function replyStickerMessage($bot, $replyToken, $packageId, $stickerId) {
	// StickerMessageBuilder�̈����̓X�^���v�̃p�b�P�[�WID�A�X�^���vID
	$response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder($packageId, $stickerId));
	if (!$response->isSucceeded()) {
		error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
	}
}

// �����̃��b�Z�[�W���܂Ƃ߂ĕԐM�B������LINEBot�A�ԐM��A���b�Z�[�W(�ϒ�����)
function replyMultiMessage($bot, $replyToken, ...$msgs) {
  // MultiMessageBuilder���C���X�^���X��
  $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
  // �r���_�[�Ƀ��b�Z�[�W��S�Ēǉ�
  foreach($msgs as $value) {
    $builder->add($value);
  }
  $response = $bot->replyMessage($replyToken, $builder);
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

// Buttons�e���v���[�g��ԐM�B������LINEBot�A�ԐM��A��փe�L�X�g�A�摜URL�A�^�C�g���A�{���A�A�N�V����(�ϒ�����)
function replyButtonsTemplate($bot, $replyToken, $alternativeText, $imageUrl, $title, $text, ...$actions) {
  // �A�N�V�������i�[����z��
  $actionArray = array();
  // �A�N�V������S�Ēǉ�
  foreach($actions as $value) {
    array_push($actionArray, $value);
  }
  // TemplateMessageBuilder�̈����͑�փe�L�X�g�AButtonTemplateBuilder
  $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
    $alternativeText,
    // ButtonTemplateBuilder�̈����̓^�C�g���A�{���A
    // �摜URL�A�A�N�V�����̔z��
    new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder ($title, $text, $imageUrl, $actionArray)
  );
  $response = $bot->replyMessage($replyToken, $builder);
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

?>
