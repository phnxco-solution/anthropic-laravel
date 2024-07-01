<?php

namespace Phnx\Anthropic\DataTransferObjects;

use Illuminate\Http\Client\Response;

final class ContentData
{
    public string $type;

    public string $text;

    private function __construct(array $data)
    {
        $this->type = data_get($data, 'type');
        $this->text = data_get($data, 'text');
    }

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new \InvalidArgumentException('The data array cannot be empty');
        }

        return new self($data);
    }
}
