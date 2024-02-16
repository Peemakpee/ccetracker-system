<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedChanges extends Model
{
    use HasFactory;
    protected $table = 'approved_changes';

    protected $fillable = [
        'document_id',
        'change_description',
        'file_path',
        'user_id',
        'date_upladedByUser',
        'deliverable_type',
        'file_name',
        'status',
        'firebaseUrl_changes',
        'academic_year',
        'program',
        'user_name',
        'subject',
        'subject_code',
    ];
    
}
