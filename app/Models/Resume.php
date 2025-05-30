<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;
    protected $table = 'resumes';
    protected $fillable = [
        'seminaire_id',
        'contenu',
        'date_envoi',
        'chemin_pdf_resume', // Ajoutez ceci car nous avons ajouté cette colonne
    ];
    public function seminar()
    {
        return $this->belongsTo(Seminar::class, 'seminaire_id');
    }
}