<?php

namespace App\Console\Commands\Workingtimes;

use App\Models\Courses\Course;
use App\Models\Courses\Date;
use App\Models\WorkingTimes\WorkingTime;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SetEndAtCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workingtimes:set_end_at';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets end_at for all workingtimes';

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
        $workingtimes = WorkingTime::whereNull('end_at')->get();
        foreach ($workingtimes as $workingtime) {
            $end_at = $workingtime->start_at;
            $end_at->addSeconds($workingtime->duration_in_seconds);
            $workingtime->end_at = $end_at;
            $workingtime->save();

            $this->line($workingtime->start_at->format('Y-d-m H:i:s') . "\t" . $workingtime->end_at->format('Y-d-m H:i:s') . "\t" . $workingtime->duration_in_seconds);
        }
    }
}
