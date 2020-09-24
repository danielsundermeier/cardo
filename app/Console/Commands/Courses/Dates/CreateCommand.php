<?php

namespace App\Console\Commands\Courses\Dates;

use App\Models\Courses\Course;
use App\Models\Courses\Date;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:date:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates course dates from yesterday for next week';

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

        $yesterday = today()->subDay();
        $day_of_week = $yesterday->dayOfWeek;
        $next_week = $yesterday->addWeek();

        $courses = Course::where('day', $day_of_week)
            ->whereDoesntHave('dates', function ($query) use ($next_week) {
                $query->where('at', $next_week);
            })
            ->get();
        foreach ($courses as $key => $course) {
            $participations = $this->getLastParticipations($course);
            $date = $course->dates()->create([
                'at' => $next_week,
                'staff_id' => $course->partner_id
            ]);
            foreach ($participations as $participation) {
                $date->participations()->create([
                    'participant_id' => $participation->participant_id
                ]);
                $participation->participant
                    ->cache()
                    ->save();
            }
        }
    }

    protected function getLastParticipations(Course $course) : Collection
    {
            $last_date = Date::with('participations.participant')
                ->where('course_id', $course->id)
                ->latest()
                ->first();

            return is_null($last_date) ? new Collection() : $last_date->participations;

    }
}
