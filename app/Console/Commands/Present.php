<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\TblPatient;
use Carbon\Carbon;
use App\Services\LineBotService;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;


class Present extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:present';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1ヵ月健診日を設定した日にプレゼントを実行する';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //1ヵ月健診に該当するデータを1件だけ取得する。
        //条件：completed_atがある / 1ヵ月健診日の+6日もしくは経過している / presented_at がnullである
        while (true) {
            $tbl_patient = TblPatient::whereNotNull('completed_at')
                ->whereRaw('DATE_SUB(DATE_FORMAT(health_check_date,\'%Y-%m-%d\'), INTERVAL -6 DAY) <= current_date()')
                ->whereNull('presented_at')
                ->inRandomOrder()->take(1)
                ->first();
            if(!$tbl_patient){
                break;
            }else{
                $this->line($tbl_patient->name);
                $line_bot_service = new LineBotService();

                //完了手続きをする　presented_atに日付を入れる
                $tbl_patient->presented_at = now();
                $tbl_patient->save();

                //1通目
                //最初のメッセージ (ケースがある場合追加メッセージ)(フォトアートの場合文言追加)
                $line_bot_service->pushMessagePresentFirst($tbl_patient);


                //2通目
                //動画を送る処理
                if($tbl_patient->present_movie_path){
                    $line_bot_service->pushMessage($tbl_patient->line_user_id, new VideoMessageBuilder($tbl_patient->present_movie_path_url, config('birthstory.front_app_url').'/images/present-movie-thumbnail.png'),$tbl_patient);
                }

                //3通目
                //写真が登録されているか
                if($tbl_patient->present_photoart_path){
                    $line_bot_service->pushMessage($tbl_patient->line_user_id, new ImageMessageBuilder($tbl_patient->present_photoart_path_url, $tbl_patient->present_photoart_path_url),$tbl_patient);
                }

                //産院に送る処理。まずinstagramの公開を許可しているか
                if($tbl_patient->is_use_instagram=='1'){
                    //している場合
                    if ($tbl_patient->mst_maternity->mst_maternity_users->count()) {
                        foreach ($tbl_patient->mst_maternity->mst_maternity_users as $mst_maternity_user_key => $mst_maternity_user) {
                            //通知を許可しているか
                            if ($mst_maternity_user->is_take_photoart) {
                                //写真が登録されているか
                                if($tbl_patient->present_photoart_path){
                                    $line_bot_service->pushMessagePresentPhotoartToMaternityUser($mst_maternity_user,$tbl_patient);
                                    event(new \App\Events\SendMailPhotoartToMaternityUserEvent($mst_maternity_user,$tbl_patient));
                                }
                            }
                        }
                    }
                }

                //4通目
                //アンケート有り＋高評価時のメッセージ
                if($tbl_patient->reviewed_at){
                    //規定以上で回答している場合
                    if($tbl_patient->average_score >= $tbl_patient->mst_maternity->minimum_review_score){
                        //Googleクチコミをまだ確認できていない場合
                        if(!$tbl_patient->is_google_review){
//                            $line_bot_service->pushMessagePresentHighScoreReview($tbl_patient);
                        }
                    }
                }else{
                    //回答していない場合
                    $line_bot_service->pushMessagePresentNotReview($tbl_patient);
                }

                //5通目
                //フォトアートがある場合のメッセージ
                if($tbl_patient->present_photoart_path){
                    $line_bot_service->pushMessagePresentPhotoart($tbl_patient);
                }

                //リッチメニューの削除
                $line_bot_service->deleteRichMenu($tbl_patient->richmenu_id);
                $tbl_patient->richmenu_id = null;

                $tbl_patient->save();
            }
        }

        return Command::SUCCESS;
    }
}
