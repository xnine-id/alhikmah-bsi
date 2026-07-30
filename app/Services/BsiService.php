<?php

namespace App\Services;

use App\Interfaces\BsiInterface;
use App\Constants\BsiResponseCode;
use App\Models\MSiswa;
use App\Models\MTarif;
use App\Models\TBsiToken;
use App\Models\TBulan;
use Illuminate\Support\Facades\Log;
use Exception;

class BsiService implements BsiInterface
{
    private $CLIENT_SECRET;
    private $BPI_PUBLIC_KEY;

    public function __construct()
    {
        $this->CLIENT_SECRET = config('services.bsi.client_secret');
        $this->BPI_PUBLIC_KEY = config('services.bsi.public_key');
    }

    public function authenticate(string $signature, string $clientKey, string $timestamp): array
    {
        $data = $clientKey . "|" . $timestamp;

        $publicKey = openssl_pkey_get_public($this->BPI_PUBLIC_KEY);

        $verified = 0;
        if ($publicKey && $signature) {
            $verified = openssl_verify($data, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256);
        }

        if ($verified == 1) {
            try {
                $token = $this->createToken();
                return [
                    "responseCode" => BsiResponseCode::AUTH_SUCCESS,
                    "responseMessage" => BsiResponseCode::getMessage(BsiResponseCode::AUTH_SUCCESS),
                    "accessToken" => $token,
                    "tokenType" => "BearerToken",
                    "expiresIn" => "900"
                ];
            } catch (Exception $e) {
                throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::AUTH_DB_ERROR), BsiResponseCode::AUTH_DB_ERROR);
            }
        } else {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::AUTH_ERROR), BsiResponseCode::AUTH_ERROR);
        }
    }

    public function inquiry(array $headers, array $payload): array
    {
        $signature = $headers['X-SIGNATURE'][0] ?? '';
        $partnerId = $headers['X-PARTNER-ID'][0] ?? '';
        $externalId = $headers['X-EXTERNAL-ID'][0] ?? '';
        $authorization = $headers['Authorization'][0] ?? '';
        $timestamp = $headers['X-TIMESTAMP'][0] ?? '';
        $endpointUrl = $headers['Endpoint-Url'][0] ?? '';

        $tmpAccessToken = explode(" ", $authorization);
        $accessToken = $tmpAccessToken[1] ?? '';

        $stringToSign = json_encode($payload);
        $signatureLocal = $this->generateBase64SignatureMessage('POST', $endpointUrl, $stringToSign, $accessToken, $timestamp, $this->CLIENT_SECRET);

        Log::info('Local Signature: ' . $signatureLocal);

        if ($signatureLocal != $signature) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_UNAUTHORIZED_ACCESS), BsiResponseCode::INQUIRY_UNAUTHORIZED_ACCESS);
        }

        if (!$this->isTokenValid($accessToken)) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_UNAUTHORIZED_TOKEN, ['accessToken' => $accessToken]), BsiResponseCode::INQUIRY_UNAUTHORIZED_TOKEN);
        }

        // Validasi Payload Inquiry
        $requiredInquiryFields = ['partnerServiceId', 'customerNo', 'trxDateInit', 'virtualAccountNo', 'inquiryRequestId', 'sourceBankCode'];
        foreach ($requiredInquiryFields as $field) {
            if (!isset($payload[$field]) || $payload[$field] === '') {
                throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_INVALID_MANDATORY_FIELD, ['xyz' => $field]), BsiResponseCode::INQUIRY_INVALID_MANDATORY_FIELD);
            }
        }

        if (!preg_match('/^[0-9\s]{1,8}$/', $payload['partnerServiceId'])) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_INVALID_FIELD_FORMAT), BsiResponseCode::INQUIRY_INVALID_FIELD_FORMAT);
        }

        if (!preg_match('/^[0-9]{1,12}$/', $payload['customerNo'])) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_INVALID_FIELD_FORMAT), BsiResponseCode::INQUIRY_INVALID_FIELD_FORMAT);
        }

        // $this->removeToken($accessToken);

        $partnerServiceId = $payload['partnerServiceId'];
        $customerNo = $payload['customerNo'] ?? '';
        $inquiryRequestId = $payload['inquiryRequestId'] ?? $externalId;
        $trxDateInit = $payload['trxDateInit'] ?? '';
        
        $amount = $payload['amount'] ?? null;
        $amountValue = $amount['value'] ?? null;

        [$siswa, $tagihanBulanIni] = $this->verifyCustomerNo($customerNo);

        if ($amountValue && $tagihanBulanIni->SPP != $amountValue) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_BILL_NOT_FOUND), BsiResponseCode::INQUIRY_BILL_NOT_FOUND);
        }

        return [
            "responseCode" => BsiResponseCode::INQUIRY_SUCCESS,
            "responseMessage" => BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_SUCCESS),
            "virtualAccountData" => [
                "partnerServiceId" => $partnerServiceId,
                "customerNo" => $customerNo,
                "virtualAccountNo" => $partnerServiceId . $customerNo,
                "virtualAccountName" => $siswa->NAMA,
                "inquiryRequestId" => $inquiryRequestId,
                "totalAmount" => ["value" => $tagihanBulanIni->SPP, "currency" => "IDR"],
                "additionalInfo" => [
                    ["label" => "JENJANG", "value" => $siswa->JENJANG],
                    ["label" => "TAHUN AJARAN", "value" => $siswa->history[0]?->tahunAjaran->TA_CODE ?? $siswa->tahunAjaran->TA_CODE],
                    ["label" => "TINGKAT", "value" => $siswa->history[0]?->TINGKAT ?? $siswa->TINGKAT],
                ],
                "billDetail" => [
                    ["label" => "SPP", "value" => $tagihanBulanIni->SPP],
                ],
            ]
        ];
    }

    public function payment(array $headers, array $payload): array
    {
        $signature = $headers['X-SIGNATURE'][0] ?? '';
        $partnerId = $headers['X-PARTNER-ID'][0] ?? '';
        $externalId = $headers['X-EXTERNAL-ID'][0] ?? '';
        $authorization = $headers['Authorization'][0] ?? '';
        $timestamp = $headers['X-TIMESTAMP'][0] ?? '';
        $endpointUrl = $headers['Endpoint-Url'][0] ?? '';

        $tmpAccessToken = explode(" ", $authorization);
        $accessToken = $tmpAccessToken[1] ?? '';

        $stringToSign = json_encode($payload);
        $signatureLocal = $this->generateBase64SignatureMessage('POST', $endpointUrl, $stringToSign, $accessToken, $timestamp, $this->CLIENT_SECRET);

        Log::info('Local Signature: ' . $signatureLocal);

        if ($signatureLocal != $signature) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::PAYMENT_UNAUTHORIZED_ACCESS), BsiResponseCode::PAYMENT_UNAUTHORIZED_ACCESS);
        }

        if (!$this->isTokenValid($accessToken)) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::PAYMENT_UNAUTHORIZED_TOKEN, ['accessToken' => $accessToken]), BsiResponseCode::PAYMENT_UNAUTHORIZED_TOKEN);
        }

        // Validasi Payload Payment
        $requiredPaymentFields = ['partnerServiceId', 'customerNo', 'trxDateTime', 'paidAmount', 'virtualAccountNo', 'paymentRequestId', 'sourceBankCode'];
        foreach ($requiredPaymentFields as $field) {
            if (!isset($payload[$field]) || $payload[$field] === '') {
                throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::PAYMENT_INVALID_MANDATORY_FIELD, ['xyz' => $field]), BsiResponseCode::PAYMENT_INVALID_MANDATORY_FIELD);
            }
        }

        if (!preg_match('/^[0-9\s]{1,8}$/', $payload['partnerServiceId'])) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::PAYMENT_INVALID_FIELD_FORMAT), BsiResponseCode::PAYMENT_INVALID_FIELD_FORMAT);
        }

        if (!preg_match('/^[0-9]{1,12}$/', $payload['customerNo'])) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::PAYMENT_INVALID_FIELD_FORMAT), BsiResponseCode::PAYMENT_INVALID_FIELD_FORMAT);
        }

        if (!isset($payload['paidAmount']['value']) || !isset($payload['paidAmount']['currency'])) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::PAYMENT_INVALID_FIELD_FORMAT), BsiResponseCode::PAYMENT_INVALID_FIELD_FORMAT);
        }

        // $this->removeToken($accessToken);

        $partnerServiceId = $payload['partnerServiceId'] ?? '';
        $customerNo = $payload['customerNo'] ?? '';
        $paymentRequestId = $payload['paymentRequestId'] ?? $externalId;
        $trxDateTime = $payload['trxDateTime'] ?? '';
        $paidAmount = $payload['paidAmount'];
        $paidAmountValue = $paidAmount['value'] ?? 0;

        try {
            [$siswa, $tagihanBulanIni] = $this->verifyCustomerNo($customerNo);
        } catch (Exception $e) {
            // Mapping kode error INQUIRY ke PAYMENT
            $codeMap = [
                BsiResponseCode::INQUIRY_BILL_NOT_FOUND => BsiResponseCode::PAYMENT_BILL_NOT_FOUND,
                BsiResponseCode::INQUIRY_BILL_ALREADY_PAID => BsiResponseCode::PAYMENT_BILL_ALREADY_PAID,
                BsiResponseCode::INQUIRY_DB_ERROR => BsiResponseCode::PAYMENT_DB_ERROR,
                BsiResponseCode::INQUIRY_INVALID_DATA => BsiResponseCode::PAYMENT_INVALID_DATA,
            ];
            $newCode = $codeMap[$e->getCode()] ?? BsiResponseCode::PAYMENT_GENERAL_ERROR;
            throw new Exception(BsiResponseCode::getMessage($newCode), $newCode);
        }

        if ($paidAmountValue != $tagihanBulanIni->SPP) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::PAYMENT_AMOUNT_NOT_VALID), BsiResponseCode::PAYMENT_AMOUNT_NOT_VALID);
        }

        // Update status pembayaran di database
        $tagihanBulanIni->CLOSED = true;
        $tagihanBulanIni->TGL_BAYAR = now();
        $tagihanBulanIni->save();

        return [
            "responseCode" => BsiResponseCode::PAYMENT_SUCCESS,
            "responseMessage" => BsiResponseCode::getMessage(BsiResponseCode::PAYMENT_SUCCESS),
            "virtualAccountData" => [
                "partnerServiceId" => $partnerServiceId,
                "customerNo" => $customerNo,
                "virtualAccountNo" => $partnerServiceId . $customerNo,
                "virtualAccountName" => $siswa->NAMA,
                "paymentRequestId" => $paymentRequestId,
                "paidAmount" => $paidAmount,
                "additionalInfo" => [
                    ["label" => "JENJANG", "value" => $siswa->JENJANG],
                    ["label" => "TAHUN AJARAN", "value" => $siswa->history[0]?->tahunAjaran->TA_CODE ?? $siswa->tahunAjaran->TA_CODE],
                    ["label" => "TINGKAT", "value" => $siswa->history[0]?->TINGKAT ?? $siswa->TINGKAT],
                ],
                "billDetail" => [
                    ["label" => "SPP", "value" => $tagihanBulanIni->SPP],
                ],
                "referenceNo" => (string)$tagihanBulanIni->ID_TRANSBULAN,
            ]
        ];
    }

    private function verifyCustomerNo(string $customerNo)
    {
        $siswa = MSiswa::with([
            'history' => fn($q) => $q->orderBy('TINGKAT', 'desc'),
            'history.tahunAjaran',
            'tahunAjaran'
        ])
            ->where('nis', (int) $customerNo)
            ->first();

        if (!$siswa) {
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_BILL_NOT_FOUND), BsiResponseCode::INQUIRY_BILL_NOT_FOUND);
        }

        $idTa = $siswa->history[0]?->ID_TA ?? $siswa->ID_TA;
        $tingkat = $siswa->history[0]?->TINGKAT ?? $siswa->TINGKAT;

        // cek apakah bulan ini sudah bayar spp atau belum, lihat dari table TBulan
        // Format BULAN 1-12
        $currentMonth = now()->format('n');
        $currentYear = now()->format('Y');

        $tagihanBulanIni = TBulan::where('ID_SISWA', $siswa->ID_SISWA)
            ->where('ID_TA', $idTa)
            ->where('BULAN', $currentMonth)
            ->where(function ($query) use ($currentYear) {
                $query->whereNull('TGL_BAYAR')
                    ->orWhereYear('TGL_BAYAR', $currentYear);
            })
            ->first();

        if (!$tagihanBulanIni) {
            // Tagihan belum dibuat/tidak ada
            $tagihanBulanIni = TBulan::create([
                'ID_TA' => $idTa,
                'ID_SISWA' => $siswa->ID_SISWA,
                'TGL_BAYAR' => now(),
                'PETUGAS' => 'BSI',
                'BULAN' => $currentMonth,
                'SPP' => MTarif::where('ID_TA', $idTa)
                    ->where('JENJANG', $siswa->JENJANG)
                    ->where('tingkat', $tingkat)
                    ->first()?->SPP ?? 0,
                'NOTES' => 'Testing BSI',
                'CLOSED' => false
            ]);
            // throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_BILL_NOT_FOUND), BsiResponseCode::INQUIRY_BILL_NOT_FOUND);

            if (!$tagihanBulanIni) {
                throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_DB_ERROR), BsiResponseCode::INQUIRY_DB_ERROR);
            }

            if ($tagihanBulanIni->SPP == 0) {
                throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_INVALID_DATA), BsiResponseCode::INQUIRY_INVALID_DATA);
            }
        }

        if ($tagihanBulanIni->CLOSED == true) {
            // Tagihan sudah dibayar
            throw new Exception(BsiResponseCode::getMessage(BsiResponseCode::INQUIRY_BILL_ALREADY_PAID), BsiResponseCode::INQUIRY_BILL_ALREADY_PAID);
        }

        // for testing
        $tagihanBulanIni->SPP = 1;

        return [$siswa, $tagihanBulanIni];
    }

    private function generateBase64SignatureMessage($method, $endpoint, $body, $token, $timestamp, $secret)
    {
        $hash = hash('sha256', $body);
        $stringToSign = $method . ":" . $endpoint . ":" . $token . ":" . $hash . ":" . $timestamp;
        $signature = hash_hmac('sha512', $stringToSign, $secret, true);
        return base64_encode($signature);
    }

    private function isTokenValid($token)
    {
        return TBsiToken::query()
            ->where('token', $token)
            ->where('expired_at', '>=', now())
            ->exists();
    }

    private function removeToken($token)
    {
        TBsiToken::query()->where('token', $token)->delete();
    }

    private function createToken()
    {
        $token = bin2hex(random_bytes(16));
        TBsiToken::create([
            'token' => $token,
            'expired_at' => now()->addSeconds(900)
        ]);
        return $token;
    }
}
