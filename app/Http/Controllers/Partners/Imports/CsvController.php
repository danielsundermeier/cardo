<?php

namespace App\Http\Controllers\Partners\Imports;

use App\Http\Controllers\Controller;
use App\Models\Partners\Partner;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('partner.import.csv.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'import_file' => 'required|file|max:51200|mimes:csv,txt',
        ]);

        $file = $attributes['import_file']->openFile();
        $loop = 0;
        while (! $file->eof()) {
            if ($loop == 0) {
                $loop++;
                $columns = $file->fgetcsv(';');
                continue;
            }
            $columns = $file->fgetcsv(';');
            Partner::create([
                'is_client' => true,
                'lastname' => $columns[1],
                'firstname' => $columns[2],
                'birthday_at' => $columns[3] ? Carbon::createFromFormat('d.m.y', $columns[3]) : null,
                'postcode' => $columns[4],
                'city' => $columns[5],
                'address' => $columns[6],
                'phonenumber' => $columns[7],
            ]);
            $loop++;
        }

        return back()->with('status', [
            'type' => 'success',
            'text' => 'Partner eingelesen.',
        ]);
    }
}
