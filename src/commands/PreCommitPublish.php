<?php

namespace Zabaala\PreCommit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Zabaala\PreCommit\PreCommitException;

class PreCommitPublish extends Command
{
    /**
     * @const string
     */
    const TEMPLATE_PATH = 'pre-commit.example';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pre-commit:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a .git/hook/pre-commit file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->publish();
    }

    /**
     * Extract PHP files to be analysed from HEAD.
     */
    private function publish()
    {
        $template = $this->getTemplatePath();

        $destination = base_path('.git/hooks/pre-commit');

        if (! is_dir(base_path('.git/hooks'))) {
            throw new PreCommitException('.git/hooks directory not exists. Git repository is present?');
        }

        exec("mv -f $template $destination");
    }

    /**
     * @return string
     */
    private function getTemplatePath()
    {
        return __DIR__
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . 'files' .
            DIRECTORY_SEPARATOR
            . 'pre-commit.example';
    }
}
