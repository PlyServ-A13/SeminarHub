<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seminaires', function (Blueprint $table) {
            $table->text('raison_refus')->nullable()->after('statut'); // Ajoute la colonne après 'statut'
        });
    }

    public function down(): void
    {
        Schema::table('seminaires', function (Blueprint $table) {
            $table->dropColumn('raison_refus');
        });
    }
};