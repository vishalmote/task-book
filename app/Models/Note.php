<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{

    protected $table = 'note';
    use SoftDeletes;
    protected $casts = [
        'attachments' => 'array'
    ];
}
