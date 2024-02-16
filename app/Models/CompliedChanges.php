<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompliedChanges extends Model
{
    use HasFactory;
    
    protected $table = 'complied_changes';

    protected $fillable = [
        'document_id',
        'change_description',
        'status',
        'file_path',
        'user_id',
        'date_upladedByUser',
        'deliverable_type',
        'file_name',
        'firebaseUrl_complied',
        'academic_year',
        'program',
        'user_name'
    ];
}
