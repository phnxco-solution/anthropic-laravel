<?php

namespace Phnx\Anthropic\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Phnx\Anthropic\Anthropic
 */
class Anthropic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Phnx\Anthropic\Anthropic::class;
    }
}
