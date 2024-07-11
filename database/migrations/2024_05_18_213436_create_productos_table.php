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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('codigobarras', 25)->nullable();
            $table->unsignedBigInteger('categoria_id');
            $table->float('costo', 10,2)->default(0);
            $table->float('precio',10,2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('alertas');
            $table->string('imagen')->nullable();
            $table->timestamps();
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
                          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
