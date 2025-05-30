<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationCode extends Model
{
    use HasFactory;

    // Si vous utilisez l'assignation de masse
    protected $fillable = [
        'code',
        'role',
    ];

    // Si vous ne voulez rien masquer
    // protected $guarded = [];
}