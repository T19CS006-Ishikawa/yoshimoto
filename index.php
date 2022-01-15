<?php

// Composer�ŃC���X�g�[���������C�u�������ꊇ�ǂݍ���
require_once __DIR__ . '/vendor/autoload.php';

// �A�N�Z�X�g�[�N�����g��CurlHTTPClient���C���X�^���X��
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
// CurlHTTPClient�ƃV�[�N���b�g���g��LINEBot���C���X�^���X��
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// LINE Messaging API�����N�G�X�g�ɕt�^�����������擾
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

// �������������`�F�b�N�B�����ł���΃��N�G�X�g���p�[�X���z���
// �s���ł���Η�O�̓��e���o��
try {
  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
  error_log('parseEventRequest failed. InvalidSignatureException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
  error_log('parseEventRequest failed. UnknownEventTypeException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
  error_log('parseEventRequest failed. UnknownMessageTypeException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
  error_log('parseEventRequest failed. InvalidEventRequestException => '.var_export($e, true));
}

// �z��Ɋi�[���ꂽ�e�C�x���g�����[�v�ŏ���
foreach ($events as $event) {
  // MessageEvent�N���X�̃C���X�^���X�łȂ���Ώ������X�L�b�v
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
    error_log('Non message event has come');
    continue;
  }
  // TextMessage�N���X�̃C���X�^���X�łȂ���Ώ������X�L�b�v
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
    error_log('Non text message has come');
    continue;
  }
  // �I�E���Ԃ�
  $bot->replyText($event->getReplyToken(), $event->getText());
}

// �e�L�X�g��ԐM�B������LINEBot�A�ԐM��A�e�L�X�g
function replyTextMessage($bot, $replyToken, $text) {
  // �ԐM���s�����X�|���X���擾
  // TextMessageBuilder�̈����̓e�L�X�g
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));
  // ���X�|���X���ُ�ȏꍇ
  if (!$response->isSucceeded()) {
    // �G���[���e���o��
    error_log('Failed! '. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

// �摜��ԐM�B������LINEBot�A�ԐM��A�摜URL�A�T���l�C��URL
function replyImageMessage($bot, $replyToken, $originalImageUrl, $previewImageUrl) {
  // ImageMessageBuilder�̈����͉摜URL�A�T���l�C��URL
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
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

// �X�^���v��ԐM�B������LINEBot�A�ԐM��A
// �X�^���v�̃p�b�P�[�WID�A�X�^���vID
function replyStickerMessage($bot, $replyToken, $packageId, $stickerId) {
  // StickerMessageBuilder�̈����̓X�^���v�̃p�b�P�[�WID�A�X�^���vID
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder($packageId, $stickerId));
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

// �����ԐM�B������LINEBot�A�ԐM��A����URL�A�T���l�C��URL
function replyVideoMessage($bot, $replyToken, $originalContentUrl, $previewImageUrl) {
  // VideoMessageBuilder�̈����͓���URL�A�T���l�C��URL
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\VideoMessageBuilder($originalContentUrl, $previewImageUrl));
  if (!$response->isSucceeded()) {
    error_log('Failed! '. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

// �I�[�f�B�I�t�@�C����ԐM�B������LINEBot�A�ԐM��A
// �t�@�C����URL�A�t�@�C���̍Đ�����
function replyAudioMessage($bot, $replyToken, $originalContentUrl, $audioLength) {
  // AudioMessageBuilder�̈����̓t�@�C����URL�A�t�@�C���̍Đ�����
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\AudioMessageBuilder($originalContentUrl, $audioLength));
  if (!$response->isSucceeded()) {
    error_log('Failed! '. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

// �����̃��b�Z�[�W���܂Ƃ߂ĕԐM�B������LINEBot�A
// �ԐM��A���b�Z�[�W(�ϒ�����)
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

// Buttons�e���v���[�g��ԐM�B������LINEBot�A�ԐM��A��փe�L�X�g�A
// �摜URL�A�^�C�g���A�{���A�A�N�V����(�ϒ�����)
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

// Confirm�e���v���[�g��ԐM�B������LINEBot�A�ԐM��A��փe�L�X�g�A
// �{���A�A�N�V����(�ϒ�����)
function replyConfirmTemplate($bot, $replyToken, $alternativeText, $text, ...$actions) {
  $actionArray = array();
  foreach($actions as $value) {
    array_push($actionArray, $value);
  }
  $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
    $alternativeText,
    // Confirm�e���v���[�g�̈����̓e�L�X�g�A�A�N�V�����̔z��
    new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder ($text, $actionArray)
  );
  $response = $bot->replyMessage($replyToken, $builder);
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

// Carousel�e���v���[�g��ԐM�B������LINEBot�A�ԐM��A��փe�L�X�g�A
// �_�C�A���O�̔z��
function replyCarouselTemplate($bot, $replyToken, $alternativeText, $columnArray) {
  $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
  $alternativeText,
  // Carousel�e���v���[�g�̈����̓_�C�A���O�̔z��
  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder (
   $columnArray)
  );
  $response = $bot->replyMessage($replyToken, $builder);
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

?>
