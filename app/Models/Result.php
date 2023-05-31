<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Result extends Model
{
    protected $table = 'results';
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = ['batch', 'attempts', 'input_string', 'key', 'hash'];
}
