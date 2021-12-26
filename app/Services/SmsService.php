<?php

namespace App\Services;


use Khbd\LaravelSmsBD\Facades\SMS;
use Throwable;
use http\Exception\RuntimeException;

class SmsService
{

    /**
     * @throws Throwable
     */
    public function sendSms(array $smsPayload): bool
    {
        $sendTo = $smsPayload['recipient'];
        $message = $smsPayload['message'];

        SMS::send($sendTo, $message);
        throw_if(!SMS::is_successful(), RuntimeException::class, "Sms is not sent to " . $sendTo);
        return true;
    }

}
