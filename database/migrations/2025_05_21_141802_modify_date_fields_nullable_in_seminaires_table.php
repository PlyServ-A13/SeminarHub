<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seminaires', function (Blueprint $table) {
            // Permettre à date_presentation d'être NULL
            $table->date('date_presentation')->nullable()->change();
            // Permettre à heure_presentation d'être NULL (si elle existe et doit être modifiée)
            $table->time('heure_presentation')->nullable()->change();
            // Si vous avez aussi date_limite_resume et qu'elle doit être nullable:
            // $table->date('date_limite_resume')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminaires', function (Blueprint $table) {
            // Attention : Revenir en arrière suppose que vous savez ce que NON NULL signifie
            // pour votre logique (ex: une valeur par défaut serait nécessaire ou la rendre non nullable
            // si elle ne contenait que des valeurs non nulles).
            // Pour la simplicité, on la re-modifie en non-nullable, mais cela pourrait échouer
            // si des NULL existent déjà.
            $table->date('date_presentation')->nullable(false)->change();
            $table->time('heure_presentation')->nullable(false)->change();
            // $table->date('date_limite_resume')->nullable(false)->change();
        });
    }
};