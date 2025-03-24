<?php

namespace App\Traits;
use App\Models\File;

trait GeneralTrait
{

    public function files()
    {
        return $this->morphMany(File::class, 'model');
    }
}
