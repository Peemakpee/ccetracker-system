<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replies extends Model
{
    use HasFactory;
    
    protected $fillable = ['message_id', 'reply_to', 'reply_message', 'reply_from'];
}
