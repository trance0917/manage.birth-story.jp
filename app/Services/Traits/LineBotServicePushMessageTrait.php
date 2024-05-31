<?php

namespace App\Services\Traits;

use App\Models\TblPatient;
use App\Models\MstMaternityUser;
use LINE\LINEBot\MessageBuilder\RawMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;


trait LineBotServicePushMessageTrait
{
    public function pushMessagePresentFirst(TblPatient $tbl_patient){
        $baby_name = '';
        if($tbl_patient->baby_name){
            $baby_name = $tbl_patient->baby_name;
            if($tbl_patient->sex=='1'){
                $baby_name = $baby_name . 'くん';
            }else{
                $baby_name = $baby_name . 'ちゃん';
            }
        }


        $message = [
            'type' => 'flex', 'altText' => '出産記念プレゼントです',
            'contents' => [
                'type' => 'bubble','size' => 'mega','direction' => 'ltr',
                'body'=> [
                    'type' => 'box','layout' => 'vertical','margin' => 'none','spacing' => 'none',
                    'contents'=> [
                        ['type' => 'text','text' => '出産記念プレゼントです','wrap'=> true,'weight' => 'bold'],
                        ['type' => 'separator','color' => '#999999','margin' => 'md'],
                        [
                            'text' => ( !empty($baby_name) ? $baby_name . 'の' : '' ) . 'ご出産、誠におめでとうございます！'."\n".'お子さんの健やかな成長を、心より願っております。',
                            'type' => 'text','wrap'=> true,'margin' => 'lg'
                        ],
                        [
                            'text' => 'ささやかですが、バースストーリーから祝いとして、出産記念ムービー'.(!is_null($tbl_patient->present_photoart_path)?'と可愛く加工したお写真':'').'を贈ります。',
                            'type' => 'text','wrap'=> true,'margin' => 'md'
                        ],
                    ]
                ]
            ]
        ];

        //ケースがある場合
        if($tbl_patient->mst_maternity->is_case){
            //ケース有り
            $message['contents']['body']['contents'][] = [
                'text' => $tbl_patient->mst_maternity->name.'からもDVDのプレゼントがありますので、1ヶ月健診時にお受け取りください。',
                'type' => 'text','wrap'=> true,'margin' => 'md'
            ];
        }

        $this->pushMessage($tbl_patient->line_user_id, new RawMessageBuilder($message), $tbl_patient);
    }

    public function pushMessagePresentPhotoart(TblPatient $tbl_patient){
        $message = [
            'type' => 'flex', 'altText' => '出産記念プレゼントです',
            'contents' => [
                'type'=> 'bubble','size'=> 'kilo','direction'=> 'ltr',
                'body'=> [
                    'type'=> 'box','layout'=> 'vertical',
                    'contents'=> [
                        [
                            'text'=> 'お贈りしたお写真でインスタグラムに投稿いただけませんか？',
                            'type'=> 'text','wrap'=> true,
                            'contents'=> [
                                ['type'=> 'span','text'=> 'お贈りしたお写真で'],
                                ['type'=> 'span','text'=> 'インスタグラムに投稿','weight'=> 'bold'],
                                ['type'=> 'span','text'=> 'いただけませんか？']
                            ]
                        ],
                        ['text'=> '下記ボタンから必要なハッシュタグをコピーして、産院さんをご紹介いただけると大変うれしいです♪','type'=> 'text','wrap'=> true,'margin'=> 'md'],
                        [
                            'type'=> 'button','color'=> '#F68CA9','style'=> 'primary','margin'=> 'lg','height'=> 'sm',
                            'action'=> [
                                'uri'=> config('birthstory.front_app_url') . '/' . $tbl_patient->code . '/instagram/?openExternalBrowser=1',
                                'type'=> 'uri','label'=> 'インスタグラムへ'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->pushMessage($tbl_patient->line_user_id, new RawMessageBuilder($message), $tbl_patient);
    }

    public function pushMessagePresentPhotoartToMaternityUser(MstMaternityUser $mst_maternity_user,TblPatient $tbl_patient){
        $this->pushMessage($mst_maternity_user->line_user_id, new ImageMessageBuilder($tbl_patient->present_photoart_path_url, $tbl_patient->present_photoart_path_url),$mst_maternity_user);
        $this->pushMessage($mst_maternity_user->line_user_id, new TextMessageBuilder($tbl_patient->name.'さまのフォトアートです。(instagramの掲載:可能)'), $mst_maternity_user);
    }

    public function pushMessagePresentNotReview(TblPatient $tbl_patient){
        $message = [
            'type' => 'flex', 'altText' => 'お写真の提出が完了しました。',
            'contents' => [
                'type' => 'bubble','size' => 'kilo','direction' => 'ltr',
                'body' => [
                    'type' => 'box','layout' => 'vertical','margin' => 'none','spacing' => 'none',
                    'contents' => [
                        ['size' => 'md','text' => 'アンケートのお願い','type' => 'text','align' => 'center','weight' => 'bold','wrap' => true],
                        ['type' => 'separator','margin' => 'md','color' => '#999999'],
                        ['type' => 'text','text' => '産院さまにとってママさまのご意見はとても大切です。','wrap' => true,'margin' => 'lg'],
                        ['type' => 'text','text' => 'バースストーリーは産院さまに代わってママさんのご意見を集め、さらなる満足度の向上に努めております。','wrap' => true,'margin' => 'lg'],
                    ]
                ],
                'footer' => [
                    'type' => 'box','layout' => 'vertical',
                    'contents' => [
                        [
                            'type' => 'button','style' => 'primary','color' => '#F68CA9',
                            'action' => [
                                'type' => 'uri','label' => 'アンケートに答える',
                                'uri' => config('birthstory.front_app_url') . '/' . $tbl_patient->code . '/review/?openExternalBrowser=1'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        if($tbl_patient->review_point){
            $message['contents']['body']['contents'][] = [
                'type' => 'box','layout' => 'vertical','margin' => 'lg',
                'contents' => [
                    [
                        'type' => 'text','wrap' => true,
                        'text' => 'アンケートにお答えいただけますと、心ばかりではありますが、Amazonギフト'.$tbl_patient->review_point.'ptを進呈いたします。',
                        'contents' => [
                            ['type' => 'span','text' => 'アンケートにお答えいただけますと、心ばかりではありますが、'],
                            ['type' => 'span','text' => 'Amazonギフト'.$tbl_patient->review_point.'ptを進呈','color' => '#cc3333','weight' => 'bold'],
                            ['type' => 'span','text' => 'を進呈いたします。']
                        ]
                    ]
                ]
            ];
        }
        $this->pushMessage($tbl_patient->line_user_id, new RawMessageBuilder($message), $tbl_patient);
    }
    public function pushMessagePresentHighScoreReview(TblPatient $tbl_patient){
        $message = [
            'type' => 'flex', 'altText' => '出産記念プレゼントです',
            'contents' => [
                'type' => 'bubble', 'size' => 'kilo', 'direction' => 'ltr',
                'body' => [
                    'type' => 'box', 'layout' => 'vertical','spacing' => 'none', 'margin' => 'none',
                    'contents' => [
                        ['type' => 'text', 'text' => 'レビューをありがとうございました！', 'size' => 'md', 'weight' => 'bold', 'align' => 'center',"wrap" => true],
                        ['type' => 'separator', 'margin' => 'md'],
                        [
                            'type' => 'image',
                            'url' => config('birthstory.front_app_url') . '/images/line-star.png',
                            'margin' => 'lg', 'offsetTop' => '0px', 'offsetBottom' => '0px', 'offsetStart' => '0px', 'offsetEnd' => '0px',
                            'aspectRatio' => '5:1','size' => '3xl'
                        ],
                        [
                            'type' => 'text', 'text' => $tbl_patient->mst_maternity->name.'のレビューを提出していただき、ありがとうございました！googleレビューへの投稿がお済でなければ、ご対応いただけると嬉しいです。',
                            'wrap' => true, 'color' => '#555555', 'size' => 'md', 'weight' => 'regular','margin' => 'lg',
                            'contents' => [
                                ['type' => 'span', 'text' => $tbl_patient->mst_maternity->name."のレビューを提出していただき、ありがとうございました！"],
                                ['type' => 'span', 'text' => 'googleレビューへの投稿','decoration' => 'underline', 'weight' => 'bold'],
                                ['type' => 'span', 'text' => 'がお済でなければ、ご対応いただけると嬉しいです。']
                            ],
                        ],
                        [
                            'type' => 'text',
                            'text' => '下記のボタンから、レビューをコピーして30秒で投稿ができます。',
                            'wrap' => true,
                            'contents' => [
                                ['type' => 'span', 'text' => '下記のボタンから、'],
                                ['type' => 'span','text' => 'レビューをコピーして30秒で投稿','decoration' => 'underline', 'weight' => 'bold'],
                                ['type' => 'span', 'text' => 'ができます。']
                            ],

                        ]
                    ]
                ],
                'footer' => [
                    'type' => 'box', 'layout' => 'vertical','margin' => 'none', 'spacing' => 'none',
                    'contents' => [
                        [
                            'type' => 'button', 'style' => 'primary', 'color' => '#F68CA9', 'margin' => 'none','height' => 'sm',
                            'action' => [
                                'type' => 'uri', 'label' => 'コピーして投稿する',
                                'uri' => config('birthstory.front_app_url') . '/' . $tbl_patient->code . '/?openExternalBrowser=1'
                            ]
                        ]
                    ]
                ]
            ],
        ];
        $this->pushMessage($tbl_patient->line_user_id, new RawMessageBuilder($message), $tbl_patient);
    }
}
