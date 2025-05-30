<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Important pour modifier les ENUM

return new class extends Migration
{
    public function up(): void
    {
        // La modification d'ENUM peut varier légèrement selon la version de MySQL/MariaDB
        // Cette méthode est généralement compatible.
        DB::statement("ALTER TABLE seminaires MODIFY COLUMN statut ENUM('en_attente','validé','publié','rejeté') NOT NULL DEFAULT 'en_attente'");
    }

    public function down(): void
    {
        // Pour revenir en arrière, on enlève 'rejeté'
        // Attention: si des séminaires ont déjà le statut 'rejeté', cette opération down() échouera
        // ou les convertira en une chaîne vide ou la première valeur de l'ENUM selon la config MySQL.
        DB::statement("ALTER TABLE seminaires MODIFY COLUMN statut ENUM('en_attente','validé','publié') NOT NULL DEFAULT 'en_attente'");
    }
};