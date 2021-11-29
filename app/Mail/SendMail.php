<?php

namespace App\Mail;

use App\Services\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use SplFileInfo;


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
        $mail = $this::to($this->mailConfig['to'])
            ->from($this->mailConfig['from'], $this->mailConfig['name'])
            ->subject($this->mailConfig['subject'])
            ->html($this->mailConfig['messageBody']);

        if (!empty($this->mailConfig['replyTo'])) {
            $mail->replyTo($this->mailConfig['replyTo']);
        }
        if (!empty($this->mailConfig['cc'])) {
            $mail->cc($this->mailConfig['cc']);
        }
        if (!empty($this->mailConfig['bcc'])) {
            $mail->bcc($this->mailConfig['bcc']);
        }

        if (!empty($this->mailConfig['attachment'])) {
            foreach ($this->mailConfig['attachment'] as $attachment) {
                if (!empty($attachment)) {
                    $imageExplodeArray = explode(".", $attachment);
                    $ext = end($imageExplodeArray);
                    $mime = MailService::MAIL_MIMETYPE[$ext];
                    if (Storage::exists($attachment)) {
                        $mail->attachFromStorage($attachment, Uuid::uuid4() . "." . $ext, [
                            'mime' => $mime
                        ]);
                    }
                }
            }
        }
        return $mail;
    }
}
