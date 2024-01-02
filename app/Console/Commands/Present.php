<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        //条件：completed_atがある / 1ヵ月健診日の当日もしくは経過している / presented_at がnullである


        //1通目
        //最初のメッセージ (ケースがある場合追加メッセージ)(フォトアートの場合文言追加)

        //2通目
        //動画を送る処理(あれば)

        //3通目
        //フォトアートを送る処理(あれば)

        //4通目
        //アンケート無しのメッセージ、アンケート有り＋高評価時のメッセージ

        //5通目
        //フォトアートがある場合のメッセージ


        //完了手続きをする　presented_atに日付を入れる

        return Command::SUCCESS;
    }
}
