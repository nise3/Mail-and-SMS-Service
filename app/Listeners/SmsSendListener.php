<?php

namespace App\Listeners;


use App\Services\SmsService;
use http\Exception\RuntimeException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class SmsSendListener implements ShouldQueue
{
    public SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * @throws Throwable
     */
    public function handle($event)
    {
        $smsData = json_decode(json_encode($event), true);
        $smsData = $smsData['data'] ?? [];
        if (!empty($smsData) && !empty($smsData['recipient']) && !empty($smsData['message'])) {
            $this->smsService->sendSms($smsData);
        }else{
            Log::channel('sms_log')->info("Sms payload format is invalid & the payload is ".json_encode($smsData,JSON_PRETTY_PRINT));
            throw_if(true, RuntimeException::class, "Sms payload format is invalid");
        }

    }
}
