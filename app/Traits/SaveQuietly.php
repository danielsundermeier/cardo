<?php

namespace App\Traits;

trait SaveQuietly
{
    /**
     * Save model without triggering observers on model
     */
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
}