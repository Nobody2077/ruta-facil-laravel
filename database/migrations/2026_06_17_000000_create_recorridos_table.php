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
        Schema::create('recorridos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre', 160);
            $table->string('codigo', 30)->unique();
            $table->string('origen', 120);
            $table->string('destino', 120);
            $table->text('descripcion')->nullable();
            $table->decimal('tarifa_bs', 6, 2)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recorridos');
    }
};
