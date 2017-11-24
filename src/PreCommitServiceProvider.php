<?php

namespace Zabaala\PreCommit;

use Illuminate\Support\ServiceProvider;
use Zabaala\PreCommit\Commands\PreCommit;

class PreCommitServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommand();
    }

    /**
     * Register the git:pre-commit command.
     */
    private function registerCommand()
    {
        $this->app->singleton(
            'command.git.pre-commit', function ($app) {
                return new PreCommit();
            }
        );

        $this->commands('command.git.pre-commit');
    }

}