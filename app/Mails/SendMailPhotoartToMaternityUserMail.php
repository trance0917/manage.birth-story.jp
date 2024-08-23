<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailPhotoartToMaternityUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mst_maternity_user;
    public $tbl_patient;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\Models\MstMaternityUser $mst_maternity_user,\App\Models\TblPatient $tbl_patient)
    {
        $this->mst_maternity_user = $mst_maternity_user;
        $this->tbl_patient = $tbl_patient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to('kei8@apost.plala.or.jp');

        $this->subject('写真素材の提供');
        $this->from(config('mail.from.address'),config('app.name'));
        $this->attach(public_path('/storage/patients/'.$this->tbl_patient->tbl_patient_id.'_'.$this->tbl_patient->code.'/'.$this->tbl_patient->present_photoart_path),[
            'as' => 'photo_'.$this->tbl_patient->present_photoart_path,
        ]);
        $this->text('emails.send-mail-photoart-to-maternity-user');
        $this->callbacks[] = function($message) {
            event(new \App\Events\SendMailLogEvent($this));
        };
        return $this;


    }
}
