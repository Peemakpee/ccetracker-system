<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileData extends Model
{
    use HasFactory;

    protected $table = 'file_data'; // Specify the table name

    protected $fillable = [
        'selectedProgram',
        'selectedAcademicYear',
        'additionalInfo',
        // Add other attributes that you want to fill here
    ];

    // Define any relationships here, if needed
}
