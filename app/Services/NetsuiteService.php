<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class NetsuiteService
{
    private Client $client;
    private string $account;
    private string $consumerKey;
    private string $consumerSecret;
    private string $tokenId;
    private string $tokenSecret;

    public function __construct()
    {
        $this->account        = env('NETSUITE_ACCOUNT');
        $this->consumerKey    = env('NETSUITE_CONSUMER_KEY');
        $this->consumerSecret = env('NETSUITE_CONSUMER_SECRET');
        $this->tokenId        = env('NETSUITE_TOKEN_ID');
        $this->tokenSecret    = env('NETSUITE_TOKEN_SECRET');

        $this->client = new Client([
            'base_uri' => "https://{$this->account}.suitetalk.api.netsuite.com",
            // El header se pone petición por petición para firmarlo dinámicamente
        ]);
    }

    public function queryDataset(string $dataset)
    {
        $method   = 'GET';
        $endpoint = "/services/rest/query/v1/dataset/{$dataset}/result";
        $url      = "https://{$this->account}.suitetalk.api.netsuite.com{$endpoint}";

        $oauth = [
            'oauth_consumer_key'     => $this->consumerKey,
            'oauth_token'            => $this->tokenId,
            'oauth_nonce'            => bin2hex(random_bytes(16)),
            'oauth_timestamp'        => time(),
            'oauth_signature_method' => 'HMAC-SHA256',
            'oauth_version'          => '1.0',
        ];

        // 1‑ Normalizar parámetros
        $baseParams = $this->normalizeParameters($oauth);        // No hay query params en este GET
        // 2‑ Crear base string
        $baseString = strtoupper($method) . '&' .
            rawurlencode($url) . '&' .
            rawurlencode($baseParams);
        // 3‑ Firmar
        $signingKey          = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->tokenSecret);
        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha256', $baseString, $signingKey, true));

        // 4‑ Encabezado Authorization
        $authHeader = 'OAuth realm="' . $this->account . '", ' .
            implode(', ', array_map(
                fn($k, $v) => sprintf('%s="%s"', $k, rawurlencode($v)),
                array_keys($oauth),
                $oauth
            ));

        try {
            $response = $this->client->get($endpoint, [
                'headers' => [
                    'Authorization' => $authHeader,
                    'Accept'        => 'application/json',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            logger()->error('SuiteTalk error: ' . $e->getMessage());
            return null;
        }
    }

    /** Devuelve los parámetros ordenados y codificados como exige RFC5849 */
    private function normalizeParameters(array $params): string
    {
        ksort($params);                          // Orden alfabético por clave
        $pairs = [];
        foreach ($params as $key => $value) {
            $pairs[] = rawurlencode($key) . '=' . rawurlencode($value);
        }
        return implode('&', $pairs);
    }
}
