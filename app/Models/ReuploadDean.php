<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReuploadDean extends Model
{
    use HasFactory;

    protected $table = 'reupload_dean';

    protected $fillable = [
        'document_id',
        'file_path',
        'user_id',
        'comment',
        'date_uploadedByUser',
        'deliverable_type',
        'file_name',
        'firebaseUrl_wSign',
        'academic_year',
        'program',
        'user_name',
        'deadline_id',
        'subject',
        'subject_code',
    ];
}
