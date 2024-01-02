<?php

namespace App\Services;

use App\Models\LogLineMessage;
use App\Models\MstMaternity;
use App\Models\MstMaternityUser;
use App\Models\TblPatient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\RawMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use App\Services\Traits\LineBotServiceMakeRichMenuTrait;
use App\Services\Traits\LineBotServicePushMessageTrait;

class LineBotService extends LINEBot
{
    use LineBotServiceMakeRichMenuTrait,LineBotServicePushMessageTrait;

    /** @var string */
    private $channelSecret;
    /** @var HTTPClient */
    private $httpClient;

    public function __construct()
    {
        $httpClient = new CurlHTTPClient(config('birthstory.line_message_channel_token'));
        $args = ['channelSecret' => config('birthstory.line_message_channel_secret')];

        parent::__construct($httpClient, $args);
        $this->httpClient = $httpClient;
        $this->channelSecret = $args['channelSecret'];
    }

    public function pushMessage($to, MessageBuilder $messageBuilder, $model = null)
    {
        $log_line_message = new LogLineMessage;

        $res = parent::pushMessage($to, $messageBuilder);
        $http_status = $res->getHTTPStatus();
        if ($model instanceof TblPatient) {
            $log_line_message->type = 1;
            $log_line_message->application_type = 2;
            $log_line_message->tbl_patient_id = $model->tbl_patient_id;
            $log_line_message->line_user_id = $model->line_user_id;
        } elseif ($model instanceof MstMaternityUser) {
            $log_line_message->type = 2;
            $log_line_message->application_type = 2;
            $log_line_message->mst_maternity_user_id = $model->mst_maternity_user_id;
            $log_line_message->line_user_id = $model->line_user_id;
        }else{
            $log_line_message->type = 3;
            $log_line_message->application_type = 2;
            $log_line_message->line_user_id = $to;
        }
        $log_line_message->message = json_encode($messageBuilder->buildMessage());
        $log_line_message->http_status = $http_status;
        $log_line_message->save();

        if($http_status!=200){
            event(new \App\Events\LineErrorSendEvent($log_line_message));
        }

        return $res;
    }

    public function pushMessageFollow(TblPatient $tbl_patient){

        $message = [
            'type' => 'flex', 'altText' => 'ご出産おめでとうございます！',
            'contents' =>[
                'type' => 'bubble','size' => 'kilo','direction' => 'ltr',
                'body' => [
                    'type' => 'box','layout' => 'vertical','margin' => 'none','spacing' => 'none',
                    'contents' => [
                        ['type' => 'text','text' => 'ご出産おめでとうございます！','weight' => 'bold','align' => 'center','wrap' => true],
                        ['type' => 'separator','color' => '#999999','margin' => 'md'],
                        ['type' => 'text','text' =>'ご出産おめでとうございます。','wrap' => true,'margin' => 'lg'],
                        ['type' => 'text','text' => '産院からのプレゼントで、一生に一度しかないご出産を記念した、感動する出産記念ムービーをプレゼントしております。','margin' => 'md','wrap' => true],
                        [
                            'type' => 'text','text' => '無料でお作りいただけます。','margin' => 'md','wrap' => true,
                            'contents' => [
                                ['type' => 'span','text' => '無料','weight' => 'bold','decoration' => 'underline'],
                                ['type' => 'span','text' => 'でお作りいただけます。']
                            ]
                        ],
                        ['type' => 'text','text' => '下記からお写真を提出してください。','margin' => 'md','wrap' => true],
                        [
                            'type' => 'button','style' => 'primary','color' => '#F68CA9','margin' => 'lg',
                            'action' => [
                                'type' => 'uri','label' => '写真を提出する','uri' =>  'https://yahoo.co.jp/?openExternalBrowser=1'
                            ]
                        ],
                    ]
                ]
            ],
        ];

        if($tbl_patient->review_point){
            $message['contents']['body']['contents'][] = ['type' => 'separator','color' => '#999999','margin' => 'xxl'];
            $message['contents']['body']['contents'][] = [
                'type' => 'text','wrap' => true,'margin' => 'xl','size' => 'sm',
                'text' => 'お写真の提出が完了し、簡単なアンケートをにお答えいただけますと、バースストーリーからAmazonギフト'.$tbl_patient->review_point.'ptを進呈しております。',
                'contents' => [
                    ['type' => 'span','text' => 'お写真の提出が完了し、簡単なアンケートにお答えいただけますと、バースストーリーから'],
                    ['type' => 'span','text' => 'Amazonギフト'.$tbl_patient->review_point.'pt','color' => '#ee3333','weight' => 'bold'],
                    ['type' => 'span','text' => 'を進呈しております。']
                ]
            ];
        }
        $this->pushMessage($tbl_patient->line_user_id, new RawMessageBuilder($message), $tbl_patient);
    }
}
