<?php

namespace App\Listeners;

use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class MailSendListener implements ShouldQueue
{
    public MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * @throws Throwable
     */
    public function handle($event)
    {
        $mailData = json_decode(json_encode($event), true);

        Log::debug("consumed-mail-whole-payload-after-parsing" . json_encode($mailData));

        $mailData = $mailData['data'] ?? [];

        Log::debug("consumed-mail-data-payload-after-parsing" . json_encode($mailData));

        if (!empty($mailData) && !empty($mailData['to']) && !empty($mailData['from']) && !empty($mailData['subject']) && !empty($mailData['message_body'])) {
            $this->mailService->sendMail($mailData);
        } else {
            Log::channel('mail_log')->info("Mail payload format is invalid & the payload is " . json_encode($mailData));
            throw_if(true, \Exception::class, "Mail payload format is invalid   & the payload is ");
        }
    }
}
