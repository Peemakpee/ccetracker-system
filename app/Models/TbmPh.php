<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbmPh extends Model
{
    use HasFactory;

    protected $table = 'tbm_ph';

    protected $fillable = [
        'document_id',
        'comment',
        'file_path',
        'user_id',
        'date_upladedByUser',
        'deliverable_type',
        'file_name',
        'status',
        'firebaseUrl_wSign',
        'academic_year',
        'program',
        'user_name'
    ];
}
