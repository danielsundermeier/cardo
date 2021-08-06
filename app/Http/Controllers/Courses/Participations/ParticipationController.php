<?php

namespace App\Http\Controllers\Courses\Participations;

use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Courses\Date;
use App\Models\Courses\Participant;
use App\Models\Courses\Participation;
use App\Models\Partners\Partner;
use App\Models\Receipts\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipationController extends Controller
{
    protected $baseViewPath = 'course.participation';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Course $course)
    {
        if ($request->wantsJson()) {

            $sql = 'SELECT
                        course_participation.participant_id,
                        COUNT(*) AS participations_count
                    FROM
                        course_participation
                            INNER JOIN course_date on course_date.id = course_participation.course_date_id
                    WHERE
                        course_date.course_id = :course_id AND
                        year(course_date.at) = :year
                    GROUP BY
                        course_participation.participant_id';

            $participations = DB::select($sql, [
                'course_id' => $course->id,
                'year' => $request->input('year')
            ]);

            foreach ($participations as $key => $participation) {
                $participation->participant = Participant::with([
                    'partner'
                ])->find($participation->participant_id);
            }

            usort($participations, function ($elem1, $elem2) {
                return strcmp($elem1->participant->partner->name, $elem2->participant->partner->name);
            });

            return $participations;
        }

        return view($this->baseViewPath . '.index')
            ->with('model', $course);
    }
}
