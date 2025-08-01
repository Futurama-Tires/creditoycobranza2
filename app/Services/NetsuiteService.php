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
        $this->account = env('NETSUITE_ACCOUNT');
        $this->consumerKey = env('NETSUITE_CONSUMER_KEY');
        $this->consumerSecret = env('NETSUITE_CONSUMER_SECRET');
        $this->tokenId = env('NETSUITE_TOKEN_ID');
        $this->tokenSecret = env('NETSUITE_TOKEN_SECRET');

        $this->client = new Client([
            'base_uri' => "https://{$this->account}.suitetalk.api.netsuite.com",
            // El header se pone petición por petición para firmarlo dinámicamente
        ]);
    }

    public function queryDataset(string $dataset, int $limit = 1000, int $offset = 0)
    {
        $method = 'GET';
        $endpoint = "/services/rest/query/v1/dataset/{$dataset}/result?limit={$limit}&offset={$offset}";
        $url = "https://{$this->account}.suitetalk.api.netsuite.com{$endpoint}";

        $oauth = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_token' => $this->tokenId,
            'oauth_nonce' => bin2hex(random_bytes(16)),
            'oauth_timestamp' => time(),
            'oauth_signature_method' => 'HMAC-SHA256',
            'oauth_version' => '1.0',
        ];

        // 1‑ Normalizar parámetros
        $baseParams = $this->normalizeParameters($oauth);        // No hay query params en este GET
        // 2‑ Crear base string
        $baseString = strtoupper($method) . '&' .
            rawurlencode($url) . '&' .
            rawurlencode($baseParams);
        // 3‑ Firmar
        $signingKey = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->tokenSecret);
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
                    'Accept' => 'application/json',
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

    public function suiteqlQuery(string $query)
    {
        $method = 'POST';
        $endpoint = "/services/rest/query/v1/suiteql";
        $url = "https://{$this->account}.suitetalk.api.netsuite.com{$endpoint}";

        $oauth = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_token' => $this->tokenId,
            'oauth_nonce' => bin2hex(random_bytes(16)),
            'oauth_timestamp' => time(),
            'oauth_signature_method' => 'HMAC-SHA256',
            'oauth_version' => '1.0',
        ];

        // Normalize OAuth parameters
        $baseParams = $this->normalizeParameters($oauth);
        $baseString = strtoupper($method) . '&' . rawurlencode($url) . '&' . rawurlencode($baseParams);
        $signingKey = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->tokenSecret);
        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha256', $baseString, $signingKey, true));

        $authHeader = 'OAuth realm="' . $this->account . '", ' .
            implode(', ', array_map(
                fn($k, $v) => sprintf('%s="%s"', $k, rawurlencode($v)),
                array_keys($oauth),
                $oauth
            ));

        try {
            // NetSuite expects the query in a specific format
            $requestBody = [
                'q' => $query,
                // Optional: Add limits if needed
                // 'limit' => 1000,
                // 'offset' => 0
            ];

            $bodyJson = json_encode($requestBody);
            logger()->info('SuiteQL request body: ' . $bodyJson);

            $response = $this->client->post($endpoint, [
                'headers' => [
                    'Authorization' => $authHeader,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Prefer' => 'transient', // Important for query performance
                ],
                'body' => $bodyJson,
                'http_errors' => false // To get full error response
            ]);

            $responseBody = $response->getBody()->getContents();
            $decodedResponse = json_decode($responseBody, true);

            if ($response->getStatusCode() !== 200) {
                logger()->error('SuiteQL error response: ', $decodedResponse ?? $responseBody);
                throw new \Exception('SuiteQL query failed: ' . ($decodedResponse['title'] ?? 'Unknown error'));
            }

            return $decodedResponse;
        } catch (GuzzleException $e) {
            logger()->error('SuiteQL connection error: ' . $e->getMessage());
            throw new \Exception('SuiteQL connection failed');
        } catch (\Exception $e) {
            logger()->error('SuiteQL error: ' . $e->getMessage());
            throw $e;
        }
    }


}
