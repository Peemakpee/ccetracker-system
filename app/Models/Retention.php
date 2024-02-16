<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retention extends Model
{
    use HasFactory;

    protected $table = 'retention';

    protected $fillable = [
        'file_path',
        'file_name',
        'firebase_url',
    ];
}
