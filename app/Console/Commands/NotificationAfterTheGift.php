<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\TblPatient;
use Carbon\Carbon;
use App\Services\LineBotService;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;

//プレゼントした後のお知らせ。
//Notification after the gift.
class NotificationAfterTheGift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notification_after_the_gift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'プレゼントした後のお知らせ。Googleクチコミが高評価の場合に飛ばす。';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $line_bot_service = new LineBotService();
        //条件、presented_at(プレゼントを提出した事が判断できるカラム)の日付が入っていて、それよりも今日が二日後になっている時に送る
        //レビューが投降されているかどうか
        $tbl_patients = TblPatient::
            whereRaw('DATE_SUB(DATE_FORMAT(presented_at,\'%Y-%m-%d\'), INTERVAL -1 DAY) <= current_date()')
//            ->whereNotNull('reviewed_at')
            ->where('is_present_after_notified','!=',1)
            ->get();

        foreach($tbl_patients AS $tbl_patient_key=>$tbl_patient){
            //アンケートをしているか
            if($tbl_patient->reviewed_at){
                //所定の評価以上の場合
                if($tbl_patient->average_score >= $tbl_patient->mst_maternity->minimum_review_score){
                //Googleクチコミをまだ確認できていない場合
                    if(!$tbl_patient->is_google_review){
                        //プレゼント後に送るLINEのメッセージ
                        $line_bot_service->pushMessagePresentHighScoreReview($tbl_patient);
                        $tbl_patient->present_after_notified_at=now();
                        $tbl_patient->save();
                    }
                }
            }
            //状況に関係なく、取り扱ったフラグを立てる
            $tbl_patient->is_present_after_notified = 1;
            $tbl_patient->save();
        }

        return Command::SUCCESS;
    }
}
