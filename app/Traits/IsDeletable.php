<?php

namespace App\Traits;

trait IsDeletable
{
    public function initializeIsDeletable()
    {
        $this->append([
            'is_deletable'
        ]);
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getIsDeletableAttribute() : bool
    {
        return $this->isDeletable();
    }
}

?>