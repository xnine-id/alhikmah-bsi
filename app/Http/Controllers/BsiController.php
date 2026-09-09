<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Interfaces\BsiInterface;
use App\Constants\BsiResponseCode;
use Exception;

class BsiController extends Controller
{
    private function getService(string $type): \App\Services\BsiService
    {
        $billerId = $type === 'open' 
            ? config('services.bsi.open_biller_id') 
            : config('services.bsi.close_biller_id');
            
        return new \App\Services\BsiService($billerId);
    }

    private function handleAuth(Request $request, string $type)
    {
        Log::info(strtoupper($type) . ' AUTH Request JSON: ', $request->json()->all() ?: []);
        Log::info(strtoupper($type) . ' AUTH Headers: ', $request->headers->all());

        try {
            $signature = $request->header('x-signature') ?? '';
            $clientKey = $request->header('x-client-key') ?? '';
            $timestamp = $request->header('x-timestamp') ?? '';

            $result = $this->getService($type)->authenticate($signature, $clientKey, $timestamp);
            
            Log::info(strtoupper($type) . " AUTH END RESPONSE :", $result);
            return response()->json($result, 200);
        } catch (Exception $e) {
            $responseCode = $e->getCode() ?: BsiResponseCode::AUTH_ERROR;
            $responseMessage = $e->getMessage() ?: BsiResponseCode::getMessage($responseCode);
            $output = ["responseCode" => $responseCode, "responseMessage" => $responseMessage];

            Log::info(strtoupper($type) . " AUTH END RESPONSE (ERROR):", $output);
            
            $statusCode = substr((string) $responseCode, 0, 3);
            if (!is_numeric($statusCode) || $statusCode < 100 || $statusCode > 599) {
                $statusCode = 500;
            }

            return response()->json($output, (int) $statusCode);
        }
    }

    private function handleInquiry(Request $request, string $type)
    {
        Log::info(strtoupper($type) . ' INQUIRY Request JSON: ', $request->json()->all() ?: []);
        Log::info(strtoupper($type) . ' INQUIRY Headers: ', $request->headers->all());

        try {
            $result = $this->getService($type)->inquiry($request->headers->all(), $request->json()->all(), $request->getContent());
            Log::info(strtoupper($type) . " INQUIRY END RESPONSE :", $result);
            return response()->json($result, 200);
        } catch (Exception $e) {
            $responseCode = $e->getCode() ?: BsiResponseCode::INQUIRY_GENERAL_ERROR;
            $responseMessage = $e->getMessage() ?: BsiResponseCode::getMessage($responseCode);
            $output = ["responseCode" => $responseCode, "responseMessage" => $responseMessage];

            Log::info(strtoupper($type) . " INQUIRY END RESPONSE (ERROR):", $output);

            $statusCode = substr((string) $responseCode, 0, 3);
            if (!is_numeric($statusCode) || $statusCode < 100 || $statusCode > 599) {
                $statusCode = 500;
            }

            return response()->json($output, (int) $statusCode);
        }
    }

    private function handlePayment(Request $request, string $type)
    {
        Log::info(strtoupper($type) . ' PAYMENT Request JSON: ', $request->json()->all() ?: []);
        Log::info(strtoupper($type) . ' PAYMENT Headers: ', $request->headers->all());

        try {
            $isClosePayment = $type === 'close';
            $result = $this->getService($type)->payment($request->headers->all(), $request->json()->all(), $request->getContent(), $isClosePayment);
            return response()->json($result, 200);
        } catch (Exception $e) {
            $responseCode = $e->getCode() ?: BsiResponseCode::PAYMENT_GENERAL_ERROR;
            $responseMessage = $e->getMessage() ?: BsiResponseCode::getMessage($responseCode);
            $output = ["responseCode" => $responseCode, "responseMessage" => $responseMessage];

            Log::info(strtoupper($type) . " PAYMENT END RESPONSE (ERROR):", $output);

            $statusCode = substr((string) $responseCode, 0, 3);
            if (!is_numeric($statusCode) || $statusCode < 100 || $statusCode > 599) {
                $statusCode = 500;
            }

            return response()->json($output, (int) $statusCode);
        }
    }

    public function authOpen(Request $request)
    {
        return $this->handleAuth($request, 'open');
    }

    public function inquiryOpen(Request $request)
    {
        return $this->handleInquiry($request, 'open');
    }

    public function paymentOpen(Request $request)
    {
        return $this->handlePayment($request, 'open');
    }

    public function authClose(Request $request)
    {
        return $this->handleAuth($request, 'close');
    }

    public function inquiryClose(Request $request)
    {
        return $this->handleInquiry($request, 'close');
    }

    public function paymentClose(Request $request)
    {
        return $this->handlePayment($request, 'close');
    }
}
