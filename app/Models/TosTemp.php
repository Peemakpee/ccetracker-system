<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TosTemp extends Model
{
    protected $fillable = ['deliv_type', 'file_name', 'file_path', 'file_type', 'file_size'];

}
