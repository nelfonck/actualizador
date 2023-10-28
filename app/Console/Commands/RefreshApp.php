<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RefreshApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull files from GIT';

    private $alreadyUpToDate;

    private $pullLog = [];

    private $composerLog = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->runPull()) {
            $this->error("An error occurred while executing 'git pull'. \nLogs:");

            foreach($this->pullLog as $logLine) {
                $this->info($logLine);
            }

            return;
        }

        if($this->alreadyUpToDate) {
            $this->info("The application is already up-to-date");
            return;
        }

        /*if(!$this->runComposer()) {

            $this->error("Error while updating composer files. \nLogs:");

            foreach($this->composerLog as $logLine) {
                $this->info($logLine);
            }

            return;
        }*/

        $this->info("Succesfully updated the application.");

        return 0;
    }

    private function runPull()
    {

        $process = new Process(['git', 'pull', 'origin','main']);
        $this->info("Running 'git pull'");

        $process->run(function($type, $buffer) {
            $this->pullLog[] = $buffer;

            if($buffer == "Already up to date.\n") {
                $this->alreadyUpToDate = TRUE;
            }
            
        });

        return $process->isSuccessful();

    }

    private function runComposer()
    {

        $process = new Process(['composer', 'install']);
        $this->info("Running 'composer install'");

        $process->run(function($type, $buffer) {
            $this->composerLog[] = $buffer;
        });


        return $process->isSuccessful();



    }
}
