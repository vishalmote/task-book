<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{

    protected $table = 'task';
    use SoftDeletes;
    protected $fillable = [
        'subject',
        'description',
        'start_date',
        'due_date',
        'status',
        'priority',
        'created_by_id'
    ];
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
