<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\TblPatient;
use App\Models\TblLineMessage;

use Carbon\Carbon;
use App\Services\LineBotService;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;


class Message extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '送信';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tbl_line_messages = TblLineMessage::where('is_sent',0)->limit(3)->get();
        foreach ($tbl_line_messages AS $tbl_line_message) {
            $line_bot_service = new LineBotService();

            if($tbl_line_message->image1_url){
                $line_bot_service->pushMessage($tbl_line_message->tbl_patient->line_user_id, new ImageMessageBuilder(asset($tbl_line_message->image1_url), asset($tbl_line_message->image1_url)),$tbl_line_message->tbl_patient);
            }

            if($tbl_line_message->image2_url){
                $line_bot_service->pushMessage($tbl_line_message->tbl_patient->line_user_id, new ImageMessageBuilder(asset($tbl_line_message->image2_url), asset($tbl_line_message->image2_url)),$tbl_line_message->tbl_patient);
            }

            if($tbl_line_message->message){
                $line_bot_service->pushMessage($tbl_line_message->tbl_patient->line_user_id, new TextMessageBuilder($tbl_line_message->message),$tbl_line_message->tbl_patient);
            }

            $tbl_line_message->is_sent = 1;
            $tbl_line_message->save();
        }
        $this->info(count($tbl_line_messages).'件送信しました。');
        return Command::SUCCESS;
    }
}
