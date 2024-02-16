<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedPh extends Model
{

    use HasFactory;

    protected $table = 'approve_ph';

    protected $fillable = [
        'document_id',
        'comment',
        'file_path',
        'user_id',
        'date_upladedByUser',
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
