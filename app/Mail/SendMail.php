<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


/**
 * Class SendMail
 * @package App\Mail
 */
class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $mailConfig;

    /**
     * SendMail constructor.
     * @param array $mailConfig
     */
    public function __construct(array $mailConfig)
    {
        $this->mailConfig = $mailConfig;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $mail=$this::to($this->mailConfig['to'])
            ->from($this->mailConfig['from'],$this->mailConfig['name'])
            ->subject($this->mailConfig['subject'])
            ->html($this->mailConfig['messageBody']);

        if(!empty($this->mailConfig['replyTo'])){
            $mail->replyTo($this->mailConfig['replyTo']);
        }
        if(!empty($this->mailConfig['cc'])){
            $mail->cc($this->mailConfig['cc']);
        }
        if(!empty($this->mailConfig['bcc'])){
            $mail->bcc($this->mailConfig['bcc']);
        }

        /**
         * if(isset($this->mailConfig['attachment'])){
            $info = new SplFileInfo($this->mailConfig['attachment']);
            $ext=$info->getExtension();
            $mime=self::MIMETYPE[$ext];
            $fileNameAS=$this->mailConfig['subject']."_".date('Y/m/d').'.'.$ext;
            $mail->attach(public_path($this->mailConfig['attachment']),[
                'as' => $fileNameAS,
                'mime' => $mime,
            ]);
        }
         */
        return $mail;
    }
}
