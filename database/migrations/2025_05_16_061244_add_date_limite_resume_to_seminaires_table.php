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
            // Ajoutez la colonne 'date_limite_resume'.
            // Vous pouvez ajuster le type (date, datetime) et si elle peut être nulle (nullable).
            // Si chaque séminaire DOIT avoir une date limite, retirez ->nullable().
            $table->date('date_limite_resume')->nullable()->after('date_presentation'); // Cette ligne est le code
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminaires', function (Blueprint $table) {
            // Supprime la colonne si la migration est annulée
            $table->dropColumn('date_limite_resume'); // Cette ligne est le code
        });
    }
};