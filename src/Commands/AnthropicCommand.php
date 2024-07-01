<?php

namespace Phnx\Anthropic\Commands;

use Illuminate\Console\Command;

class AnthropicCommand extends Command
{
    public $signature = 'anthropic';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
