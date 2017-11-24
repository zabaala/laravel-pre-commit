<?php

namespace Zabaala\PreCommit\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class PreCommit extends Command
{
    /**
     * Files to be analysed.
     *
     * @var array
     */
    private $files = [];

    /**
     * The exit code returned by the process.
     *
     * @var int
     */
    private $exitCode;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pre-commit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Git pre-commit hook, with PHPCS, PHPCBF and PHPUNIT.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->checkDependencies();

        // extract PHP files...
        $this->extractFilesToBeAnalysed();

        // Run code sniffer...
        $this->runCodeSniffer();

        // Run Code Beautifier and Fixer...
        $this->runPHPCBF();

        // Run PHPUnit
        $this->runPHPUnit();

        if ($this->exitCode) {
            $this->output->error('Something is wrong. Check Code Sniffer and PHPUnit log.');
        } else {
            $this->output->success('Yeah!! Everything is alright.');
        }

        exit($this->exitCode);
    }

    /**
     * Check if dependencies exists.
     */
    private function checkDependencies()
    {
        $installedPackages = [];

        exec("composer show -N", $installedPackages);
        $installedPackages = collect($installedPackages);

        $continue = $installedPackages->contains("phpunit/phpunit") &&
                    $installedPackages->contains("squizlabs/php_codesniffer");

        if (! $continue) {
            $this->output->error('The packages PHPUnit and PHP_CodeSniffer wasn\'t found.');
            exit(1);
        }
    }

    /**
     * Extract PHP files to be analysed from HEAD.
     */
    private function extractFilesToBeAnalysed()
    {
        exec("git diff --cached --name-only --diff-filter=ACMR HEAD | grep \\\\.php", $this->files);
    }

    /**
     * Run Code Sniffer to detect PSR2 code standard.
     */
    private function runCodeSniffer()
    {
        $process = $this->process(
            "./vendor/bin/phpcs --standard=PSR2 --encoding=utf-8 -n -p " . implode(" ", $this->files)
        );
        $this->exitCode = $process->getExitCode();
    }

    /**
     * Run Code Beautifier and Fixer.
     */
    private function runPHPCBF()
    {
        $process = $this->process(
            "./vendor/bin/phpcbf --standard=PSR2 --encoding=utf-8 " . implode(" ", $this->files)
        );
        $this->exitCode = $process->getExitCode();
    }

    /**
     * Run PHP Unit test.
     */
    private function runPHPUnit()
    {
        $process = $this->process("./vendor/bin/phpunit");
        $this->exitCode = $process->getExitCode();
    }

    /**
     * @param $command
     * @return Process
     */
    private function process($command)
    {
        $process = new Process($command);

        $process->run(
            function ($type, $line) {
                $this->output->write($line);
            }
        );

        return $process;
    }
}
