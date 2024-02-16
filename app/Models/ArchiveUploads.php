<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveUploads extends Model
{
    use HasFactory;

    protected $table = 'archive_uploads';

    protected $fillable = [
        'document_id',
        'status',
        'user_id',
        'archived_at',
        'deliverable_type',
        'file_name',
        'firebase_url',
        'academic_year',
        'program',
        'user_name'
    ];
}
