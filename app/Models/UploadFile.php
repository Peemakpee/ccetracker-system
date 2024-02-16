<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'firebase_url',
        'program',
        'academic_year',
        'user_id',
        'user_name',
        'deliverable_type',
        'deadline_id',
        'subject',
        'subject_code'
    ];
}
