<?php

namespace Phnx\Anthropic\DataTransferObjects;

use Illuminate\Http\Client\Response;

final class ChatResponseData
{

    public string $id;

    public string $type;

    public string $role;

    public array $content;

    public string $response;

    public string $model;

    public ?string $stopReason;

    public ?string $stopSequence;

    public array $usage;

    public ?float $inputTokens;

    public ?float $outputTokens;

    public ?array $rateLimiter;

    private function __construct(array $data)
    {
        $this->id = data_get($data, 'id');
        $this->type = data_get($data, 'type');
        $this->role = data_get($data, 'role');
        $this->content = data_get($data, 'content');
        $this->response = data_get($data, 'content.0.text');
        $this->model = data_get($data, 'model');
        $this->stopReason = data_get($data, 'stop_reason');
        $this->stopSequence = data_get($data, 'stop_sequence');
        $this->usage = data_get($data, 'usage');
        $this->inputTokens = data_get($data, 'usage.input_tokens');
        $this->outputTokens = data_get($data, 'usage.output_tokens');
        $this->rateLimiter = data_get($data, 'rate_limiter');
    }

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new \InvalidArgumentException('The data array cannot be empty');
        }

        return new self($data);
    }

    public static function fromResponse(Response $response): self
    {
        $data = [
            ...$response->json(),
            'rate_limiter' => [
                'request_limit' => (int) $response->header('anthropic-ratelimit-requests-limit'),
                'remaining_requests' => (int) $response->header('anthropic-ratelimit-requests-remaining'),
                'requests_reset_at' => strtotime($response->header('anthropic-ratelimit-requests-reset')),
                'token_limit' => (float) $response->header('anthropic-ratelimit-tokens-limit'),
                'remaining_tokens' => (float) $response->header('anthropic-ratelimit-tokens-remaining'),
                'tokens_reset_at' => strtotime($response->header('anthropic-ratelimit-tokens-reset')),
            ],
        ];

        return self::fromArray($data);
    }
}
