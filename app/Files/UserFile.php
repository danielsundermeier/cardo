<?php

namespace App\Files;

use App\Traits\HasPath;
use App\Traits\IsDeletable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserFile extends Model
{
    use HasPath, IsDeletable;

    const MIME_TYPES = [
        'jpeg',
        'bmp',
        'png',
        'pdf',
    ];

    protected $appends = [
        'url',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'extension',
        'fileable_id',
        'fileable_type',
        'filename',
        'mime',
        'name',
        'original_name',
        'preview',
        'size',
        'thumbnail',
        'user_id',
    ];

    public function isDeletable()
    {
        return true;
    }

    public function getEditPathAttribute()
    {
        return '';
    }

    protected function getBaseRouteAttribute() : string
    {
        return 'userfileable';
    }

    public function getPathParameterAttribute() : array
    {
        return [
            'userfile' => $this->id
        ];
    }

    public function getStoragePathAttribute() : string
    {
        return Storage::disk('public')->path('userfiles/' . $this->filename);
    }

    public static function fromUploadedFile(UploadedFile $file, Model $fileable = null) : self
    {
        $attributes['mime'] = $file->getClientMimeType();
        $attributes['name'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $attributes['extension'] = $file->extension();
        $attributes['original_name'] = $file->getClientOriginalName();
        $attributes['size'] = $file->getSize();
        $attributes['user_id'] = auth()->user()->id;
        if (! is_null($fileable)) {
            $attributes['fileable_type'] = get_class($fileable);
            $attributes['fileable_id'] = $fileable->id;
        }

        Storage::disk('public')->makeDirectory('userfiles');

        $userfile = new self($attributes);
        $userfile->setFilename($attributes['extension']);

        if ($path = $file->storeAs('userfiles', $userfile->filename, ['disk' => 'public'])) {
            if (is_null($fileable)) {
                $userfile->save();
            }
            else {
                $fileable->userfiles()->save($userfile);
            }
        }

        return $userfile->load('fileable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fileable()
    {
        return $this->morphTo('fileable');
    }

    public function getUrlAttribute()
    {
        return Storage::url('userfiles/' . $this->filename);
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        return $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('name', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('original_name', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('filename', 'LIKE', '%' . $searchtext . '%');
        });
    }

    public function setFilename(string $extension)
    {
        $uuid = Str::uuid();
        if ($this->checkFilename($uuid)) {
            $this->setUUID();
        }

        $this->filename = $uuid . '.' . $extension;
    }

    protected function checkFilename(string $uuid)
    {
        return self::where('filename', 'LIKE', $uuid . '.%')->exists();
    }
}
