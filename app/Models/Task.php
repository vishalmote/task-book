<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{

    protected $table = 'task';
    use SoftDeletes;
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
