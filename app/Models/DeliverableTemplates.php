<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverableTemplates extends Model
{
    use HasFactory;

    protected $table = 'deliverable_templates';

    protected $fillable = [
        'type',
        'firebase_url',
    ];
}
