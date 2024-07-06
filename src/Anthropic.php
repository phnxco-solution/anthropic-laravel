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
            parameters: $parameters
        );

        return ChatResponseData::fromResponse($response);
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

    private function mergeParameters(array $parameters): array
    {
        $parameters = $this->parseParameters($parameters);

        return array_merge(
            $this->defaultParameters,
            $parameters
        );
    }

    private function parseParameters(array $parameters): array
    {
        $systemMessage = '';
        $messages = collect(data_get($parameters, 'messages', []))
            ->reduce(function ($carry, array $message) use (&$systemMessage) {
                if ($message['role'] === 'system') {
                    $systemMessage = $message['content'];

                    return $carry;
                }

                $carry[] = [
                    'role' => $message['role'],
                    'content' => $message['content'],
                ];

                return $carry;
            }, []);

        return [
            'system' => $systemMessage,
            ...$parameters,
            'messages' => $messages,
        ];
    }
}
