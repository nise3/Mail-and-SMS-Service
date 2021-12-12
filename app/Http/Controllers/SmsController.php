<?php

namespace App\Http\Controllers;

use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class SmsController extends Controller
{
    public SmsService $smsService;

    /**
     * @param SmsService $smsService
     */
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * @throws Throwable
     */
    public function smsSend(Request $request): JsonResponse
    {
        Log::info("sms-payload".json_encode($request->all()));
        $status = $this->smsService->sendSms($request->all());
        $response = [
            "_response" => [
                "success" => $status,
                "code" => $status ? ResponseAlias::HTTP_OK : ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                "message" => $status ? "Success" : "Fail"
            ]
        ];
        return Response::json($response, $response['_response']['code']);
    }


}
