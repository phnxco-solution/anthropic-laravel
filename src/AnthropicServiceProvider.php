<?php

namespace Phnx\Anthropic;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasInstallCommand(fn (InstallCommand $command) => $command
                ->publishConfigFile()
                ->askToStarRepoOnGitHub('phnxco-solution/anthropic-laravel')
            );
    }
}
