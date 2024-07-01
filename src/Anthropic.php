<?php

namespace Phnx\Anthropic;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Phnx\Anthropic\DataTransferObjects\ChatResponseData;
use Phnx\Anthropic\Exceptions\ApiKeyIsMissing;

class Anthropic
{
    protected string $baseUrl = 'https://api.anthropic.com/';

    protected array $defaultParameters;

    protected PendingRequest $client;

    public function __construct()
    {
        $apiKey = config('anthropic.api_key');
        $apiVersion = config('anthropic.api_version', '2023-06-01');
        $version = config('anthropic.model_version', 'v1');

        if (! $apiKey) {
            throw ApiKeyIsMissing::create();
        }

        $this->client = Http::baseUrl($this->baseUrl . $apiVersion)
                            ->withHeader('x-api-key', $apiKey)
                            ->withHeader('anthropic-version', $version)
                            ->asJson()
                            ->timeout(config('anthropic.request_timeout', 30));

        $this->defaultParameters = [
            'model' => config('anthropic.chat_model'),
            'max_tokens' => (int) config('anthropic.max_tokens', 1024),
        ];
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function chat(array $parameters): ChatResponseData
    {
        $response = $this->contact(
            endpoint: 'messages',
            parameters: $this->mergeParameters($parameters)
        );

        return ChatResponseData::fromResponse($response);
    }

    private function mergeParameters(array $parameters): array
    {
        return array_merge($this->defaultParameters, $parameters);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    private function contact(string $endpoint, array $parameters): Response
    {
        $response = $this->client->post(
            url: $endpoint,
            data: $this->mergeParameters($parameters)
        );

        if ($response->failed()) {
            $response->throw();
        }

        return $response;
    }
}
