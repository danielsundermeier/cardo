<?php

namespace App\Traits;

use App\Files\UserFile;

trait HasUserFiles
{
    public function userfiles()
    {
        return $this->morphMany(UserFile::class, 'fileable');
    }
}