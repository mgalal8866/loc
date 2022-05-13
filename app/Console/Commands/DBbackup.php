<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class DBbackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DB:BACKUP';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BACKUP DATABACE';

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
        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);
        // return 0;
    }
}
