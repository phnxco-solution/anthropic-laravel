<?php

namespace Phnx\Anthropic;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Phnx\Anthropic\Commands\AnthropicCommand;

class AnthropicServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('anthropic')
            ->hasConfigFile('anthropic')
            ->hasCommand(AnthropicCommand::class);
    }
}
