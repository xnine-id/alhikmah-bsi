<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Interfaces\BsiInterface;
use App\Constants\BsiResponseCode;
use Exception;

class BsiController extends Controller
{
    public function __construct(protected BsiInterface $bsiService) {}

    public function auth(Request $request)
    {
        Log::info('AUTH Request JSON: ', $request->json()->all() ?: []);
        Log::info('AUTH Headers: ', $request->headers->all());

        try {
            $signature = $request->header('X-SIGNATURE', '');
            $clientKey = $request->header('X-CLIENT-KEY', '');
            $timestamp = $request->header('X-TIMESTAMP', '');

            $result = $this->bsiService->authenticate($signature, $clientKey, $timestamp);

            Log::info("AUTH END RESPONSE :", $result);
            return response()->json($result, 200);
        } catch (Exception $e) {
            $responseCode = $e->getCode() ?: BsiResponseCode::AUTH_ERROR;
            $responseMessage = $e->getMessage() ?: BsiResponseCode::getMessage($responseCode);
            $output = ["responseCode" => $responseCode, "responseMessage" => $responseMessage];

            Log::info("AUTH END RESPONSE (ERROR):", $output);

            $statusCode = substr((string) $responseCode, 0, 3);
            if (!is_numeric($statusCode) || $statusCode < 100 || $statusCode > 599) {
                $statusCode = 500;
            }

            return response()->json($output, (int) $statusCode);
        }
    }

    public function inquiry(Request $request)
    {
        Log::info('INQUIRY Request JSON: ', $request->json()->all() ?: []);
        Log::info('INQUIRY Headers: ', $request->headers->all());

        try {
            $result = $this->bsiService->inquiry($request->headers->all(), $request->json()->all());
            Log::info("INQUIRY END RESPONSE :", $result);
            return response()->json($result, 200);
        } catch (Exception $e) {
            $responseCode = $e->getCode() ?: BsiResponseCode::INQUIRY_GENERAL_ERROR;
            $responseMessage = $e->getMessage() ?: BsiResponseCode::getMessage($responseCode);
            $output = ["responseCode" => $responseCode, "responseMessage" => $responseMessage];

            Log::info("INQUIRY END RESPONSE (ERROR):", $output);

            $statusCode = substr((string) $responseCode, 0, 3);
            if (!is_numeric($statusCode) || $statusCode < 100 || $statusCode > 599) {
                $statusCode = 500;
            }

            return response()->json($output, (int) $statusCode);
        }
    }

    public function payment(Request $request)
    {
        Log::info('PAYMENT Request JSON: ', $request->json()->all() ?: []);
        Log::info('PAYMENT Headers: ', $request->headers->all());

        try {
            $result = $this->bsiService->payment($request->headers->all(), $request->json()->all());
            return response()->json($result, 200);
        } catch (Exception $e) {
            $responseCode = $e->getCode() ?: BsiResponseCode::PAYMENT_GENERAL_ERROR;
            $responseMessage = $e->getMessage() ?: BsiResponseCode::getMessage($responseCode);
            $output = ["responseCode" => $responseCode, "responseMessage" => $responseMessage];

            Log::info("PAYMENT END RESPONSE (ERROR):", $output);

            $statusCode = substr((string) $responseCode, 0, 3);
            if (!is_numeric($statusCode) || $statusCode < 100 || $statusCode > 599) {
                $statusCode = 500;
            }

            return response()->json($output, (int) $statusCode);
        }
    }
}
