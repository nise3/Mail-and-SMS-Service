<?php

namespace App\Http\Controllers;

use App\Models\MailLog;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MailSendController extends Controller
{
    public MailService $mailService;

    /**
     * @param MailService $mailService
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }


    public function mailSend(Request $request):JsonResponse
    {
        Log::info("mail-payload".json_encode($request->all()));
        $status = $this->mailService->sendMail($request->all());
        $response = [
            "_response" => [
                "success" => $status,
                "code" => $status ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => $status ? "Success" : "Fail"
            ]
        ];
        return \Illuminate\Support\Facades\Response::json($response, $response['_response']['code']);
    }
}
