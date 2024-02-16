<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reupload extends Model
{
    use HasFactory;

    protected $table = 'reuploads';

    protected $fillable = [
        'document_id',
        'file_path',
        'user_id',
        'date_uploadedByUser',
        'deliverable_type',
        'file_name',
        'firebase_url',
        'academic_year',
        'program',
        'user_name',
        'status',
        'deadline_id',
        'subject',
        'subject_code',
    ];
}
