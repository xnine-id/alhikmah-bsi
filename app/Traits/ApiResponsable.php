<?php

namespace App\Traits;

use App\Http\Resources\PaginationCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

trait ApiResponsable
{
    private function apiResponse(Arrayable|array|JsonResource|null $data, int $code = 200, ?string $message = '', Arrayable|array|JsonResource|null $token = null): JsonResponse
    {
        $response = [];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data) {
            $response['data'] = $data;
        }

        if ($token) {
            $response['token'] = $token;
        }

        return response()->json($response, $code);
    }

    private function setCookie(string $accessToken, string $refreshToken): array
    {
        $acCookie = cookie(config('sanctum.ac_key'), $accessToken, config('sanctum.ac_expiration'), null, null, true, true, false, 'None');
        $rtCookie = cookie(config('sanctum.rt_key'), $refreshToken, config('sanctum.rt_expiration'), null, null, true, true, false, 'None');

        return [
            'acCookie' => $acCookie,
            'rtCookie' => $rtCookie,
        ];
    }

    private function respondCreated(Arrayable|array|JsonResource|null $data = null, string $message): JsonResponse
    {
        return $this->apiResponse(
            data: $data,
            code: Response::HTTP_CREATED,
            message: $message,
        );
    }

    private function respondSuccess(Arrayable|array|JsonResource|null $data = null, ?string $message = ''): JsonResponse
    {
        return $this->apiResponse(
            data: $data,
            code: Response::HTTP_OK,
            message: $message,
        );
    }

    private function respondPagination(PaginationCollection $data): JsonResponse
    {
        return response()->json($data, 200);
    }

    private function respondToken(Arrayable|array $token, string $message, Arrayable|array|JsonResource|null $data = null, bool $cookieBased = false): JsonResponse
    {
        $isMobile = preg_match('/android|iphone|ipad|mobile/i', strtolower(request()->userAgent() ?? ''));
        $response = null;

        if (!$isMobile) {
            $response = $this->apiResponse(
                data: $data,
                code: Response::HTTP_OK,
                message: $message,
            );
        } else {
            $response = $this->apiResponse(
                data: $data,
                code: Response::HTTP_OK,
                message: $message,
                token: $token,
            );
        }

        if ($cookieBased) {
            $cookies = $this->setCookie(
                $token[config('sanctum.ac_key')],
                $token[config('sanctum.rt_key')],
            );

            $response
                ->withCookie($cookies['acCookie'])
                ->withCookie($cookies['rtCookie']);
        }

        return $response;
    }

    private function parseError(\Throwable $th)
    {
        $result = [
            'code' => 500,
            'message' => config('app.debug') ? $th->getMessage() : 'Internal server error',
        ];

        $code = $th->getCode();

        if (is_int($code) && $code >= 400 && $code <= 500) {
            $result['code'] = $th->getCode();
            $result['message'] = $th->getMessage();
        } else {
            Log::error($th->getMessage());
        }

        return $result;
    }

    private function respondError(\Throwable $th)
    {
        $result = $this->parseError($th);

        return $this->apiResponse(
            data: null,
            code: $result['code'],
            message: $result['message'],
        );
    }
}