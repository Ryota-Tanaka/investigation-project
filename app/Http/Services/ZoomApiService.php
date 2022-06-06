<?php

declare(strict_types=1);

namespace App\Http\Services;

use Exception;
use Firebase\JWT\JWT;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ZoomApiService
{
    /** @var string $apiUrl */
    private $apiUrl;
    /** @var string $apiSecret */
    private $apiSecret;
    /** @var string $apiKey */
    private $apiKey;
    /** @var string $jwt */
    private $jwt;
    /** @var string[] $headers */
    private $headers;

    /** @var string csv読み込み時のエラーコード */
    private const WEBINAR_CREATE_PATH = '/users/me/webinars';

    public function __construct()
    {
        $this->apiUrl = env('ZOOM_API_URL');
        $this->apiKey = env('ZOOM_API_KEY');
        $this->apiSecret = env('ZOOM_API_SECRET');
        $this->jwt = $this->createJwtToken();
        $this->headers = [
            'Authorization' => 'Bearer '.$this->jwt,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    /**
     * JWTトークンの発行
     *
     * @return string
     */
    public function createJwtToken()
    {
        $payload = [
            'iss' => $this->apiKey,
            'exp' => strtotime('+1 minute'),
        ];

        return JWT::encode($payload, $this->apiSecret, 'HS256');
    }

    /**
     * ウェビナーの作成をする
     *
     * @param array $data
     * @return PromiseInterface|Response
     * @throws Exception
     */
    public function createWebiner(array $data)
    {
        $url = $this->apiUrl . self::WEBINAR_CREATE_PATH;

        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'start_time' => $this->toZoomTimeFormat($data['startTime']),
                'agenda' => (!empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone' => 'Asia/Tokyo',
            ]),
        ];

        return Http::withHeaders($this->headers)->post($url, $body);
    }

    /**
     * ZOOM用の日付変換
     *
     * @param string $dateTime
     * @return string
     */
    private function toZoomTimeFormat(string $dateTime)
    {
        try {
            return (new \DateTime($dateTime))->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            return '';
        }
    }
}
