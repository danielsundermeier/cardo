<?php

namespace App\Traits;

trait HasPath
{
    public function getPathAttribute()
    {
        return $this->path('show');
    }

    public function getEditPathAttribute()
    {
        return $this->path('edit');
    }

    protected function path(string $action = '') : string
    {
        return ($this->id ? route($this->baseRoute() . '.' . $action, $this->path_parameter) : '');
    }

    protected function baseRoute() : string
    {
        return '';
    }

    public function getPathParameterAttribute() : array
    {
        return [
            $this->baseRoute() => $this->id
        ];
    }
}

?>