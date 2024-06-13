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
        Schema::create('denominacions', function (Blueprint $table) {
            $table->id();
            $table->enum('tipomoneda',['BILLETE', 'MONEDA', 'OTRO'])->default('BILLETE');
            $table->float('valor', 10 , 2);
            $table->string('imagen', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denominacions');
    }
};
