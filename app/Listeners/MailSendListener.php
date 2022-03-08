<?php

namespace App\Listeners;

use App\Services\MailService;
use Exception;
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
        Log::debug("MailSendListener Consumed - handle");
//        Log::debug($event);

        $mailData = json_decode(json_encode($event), true);

        Log::debug("MailSendListener Consumed");

        Log::channel('mail_log')->info("consumed-mail-payload-after-parsing" . json_encode($mailData));

        $mailData = $mailData['data'] ?? [];
        if (!empty($mailData) && !empty($mailData['to']) && !empty($mailData['subject']) && !empty($mailData['message_body'])) {
            $mailData['from'] = env('MAIL_FROM_ADDRESS', 'noreply@nise.gov.bd');
            Log::channel('mail_log')->info('Mail Sent to: ' . json_encode($mailData['to']) . " - " . $mailData['from'] . ' - ' . $mailData['subject']);
            $this->mailService->sendMail($mailData);
        } else {
            Log::channel('mail_log')->info("Mail payload format is invalid & the payload is " . json_encode($mailData));
            throw_if(true, Exception::class, "Mail payload format is invalid   & the payload is ");
        }
    }
}
