<?php


namespace Phnx\Anthropic\Exceptions;

use InvalidArgumentException;

/**
 * @internal
 */
final class ApiKeyIsMissing extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     */
    public static function create(): self
    {
        return new self(
            'The Anthropic API Key is missing. Please provide a valid API key in the `anthropic.api_key` configuration key.'
        );
    }
}
