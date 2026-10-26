<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'recommendations' => 'array',
        'extra_data' => 'array',
        'high_bp' => 'boolean',
        'high_chol' => 'boolean',
        'phys_activity' => 'boolean',
        'family_history' => 'boolean',
        'smoker' => 'boolean',
        'fruits_veggies' => 'boolean',
        'chol_check' => 'boolean',
        'heart_disease' => 'boolean',
        'gestational_history' => 'boolean',
    ];
}
