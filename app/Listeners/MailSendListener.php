<?php

namespace App\Listeners;

use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailSendListener implements ShouldQueue
{
    public MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function handle($event)
    {
        $mailData = json_decode(json_encode($event), true);
        $this->mailService->sendMail($mailData);
    }
}
