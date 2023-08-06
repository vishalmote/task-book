<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{

    protected $table = 'note';
    use SoftDeletes;
    protected $fillable = [
        'subject',
        'note',
        'attachments',
        'task_id'
    ];
    protected $casts = [
        'attachments' => 'array'
    ];
}
