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
        return ($this->id ? route($this->base_route . '.' . $action, $this->path_parameter) : '');
    }

    protected function getBaseRouteAttribute() : string
    {
        return '';
    }

    public function getPathParameterAttribute() : array
    {
        return [
            $this->base_route => $this->id
        ];
    }
}

?>