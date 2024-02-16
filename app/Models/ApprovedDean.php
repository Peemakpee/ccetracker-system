<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedDean extends Model
{
    use HasFactory;
    protected $table = 'approve_dean';

    protected $fillable = [
        'document_id',
        'comment',
        'file_path',
        'user_id',
        'date_upladedByUser',
        'deliverable_type',
        'file_name',
        'status',
        'firebaseUrl_wSignDean',
        'academic_year',
        'program',
        'user_name',
        'show',
        'deadline_id',
        'subject',
        'subject_code',
    ];
}
