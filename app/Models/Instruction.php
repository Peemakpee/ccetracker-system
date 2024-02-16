<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $fillable = [
        'user_type',
        'subject',
        'body',
        'link',
    ];

    protected $table = 'instructions'; // Name of the table in the database
}
