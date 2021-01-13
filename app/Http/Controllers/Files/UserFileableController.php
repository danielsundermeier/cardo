<?php

namespace App\Http\Controllers\Files;

use App\Files\UserFile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserFileableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type, $model)
    {
        return $model->userfiles()
            ->search($request->input('searchtext'))
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $type, $model)
    {
        $attributes = $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:51200|mimes:' . join(',', UserFile::MIME_TYPES),
        ]);

        $userfiles = [];
        foreach ($attributes['files'] as $key => $file) {
            $userfiles[] = UserFile::fromUploadedFile($file, $model);
        }

        if ($request->wantsJson()) {
            return $userfiles;
        }

        return back()
            ->with('status', 'Datei hochgeladen!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserFile $userfile)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $userfile->update($validatedData);

        return $userfile->load('fileable');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, UserFile $userfile)
    {
        if (Storage::disk('public')->delete('userfiles/' . $userfile->filename)) {
            $userfile->delete();
        }

        if ($request->wantsJson()) {
            return true;
        }

        return back()
            ->with('status', 'Datei gelöscht!');
    }
}
