<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichier extends Model
{
    use HasFactory;
    protected $table = 'fichiers';
    protected $fillable = ['seminaire_id', 'chemin', 'date_ajout'];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class, 'seminaire_id');
    }
}