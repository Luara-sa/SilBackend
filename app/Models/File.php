<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    protected $fillable = ['name', 'path', 'size', 'type', 'extension', 'model_id', 'model_type'];

    public function model()
    {
        return $this->morphTo();
    }


}
