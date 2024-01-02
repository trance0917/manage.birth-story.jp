<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use App\Models\MstMaternity;
use App\Models\LogLineWebhook;


final class WebHookController extends Controller
{
    public function github(Request $request){
        exec("cd /var/www/dev.manage.birth-story.jp ; git pull",$opt, $return_ver);
    }
}
