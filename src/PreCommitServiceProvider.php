<?php

namespace Zabaala\PreCommit;

use Illuminate\Support\ServiceProvider;
use Zabaala\PreCommit\Commands\PreCommit;
use Zabaala\PreCommit\Commands\PreCommitPublish;

class PreCommitServiceProvider extends ServiceProvider
{
    /**
     * @const string.
     */
    const COMMAND_PRE_COMMIT = 'command.git.pre-commit';

    /**
     * @const string.
     */
    const COMMAND_PUBLISH = 'command.git.pre-commit-publish';

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
        $this->registerPreCommitCommand();

        $this->registerPublishCommand();
    }

    /**
     * Register pre-commit command.
     */
    private function registerPreCommitCommand()
    {
        $this->app->singleton(
            self::COMMAND_PRE_COMMIT,
            function () {
                return new PreCommit();
            }
        );

        $this->commands(self::COMMAND_PRE_COMMIT);
    }

    /**
     * Register publish command.
     */
    private function registerPublishCommand()
    {
        $this->app->singleton(
            self::COMMAND_PUBLISH,
            function () {
                return new PreCommitPublish();
            }
        );

        $this->commands(self::COMMAND_PUBLISH);
    }


}
