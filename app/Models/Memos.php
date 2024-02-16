<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memos extends Model
{
    use HasFactory;

    protected $table = 'memos';

    protected $fillable = [
        'memo_to',
        'memo_from',
        'memo_subject',
        'firebase_url'
    ];
}
