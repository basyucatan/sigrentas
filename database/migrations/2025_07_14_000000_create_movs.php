<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdCuarto')->constrained('cuartos')->restrictOnDelete();
            $table->foreignId('IdInquilino')->constrained('inquilinos')->restrictOnDelete();
            $table->date('fechaIni');
            $table->date('fechaFin'); 
            $table->string('docContrato');
            $table->boolean('activo')->default(true);
        });
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdCuarto')->constrained('cuartos')->restrictOnDelete();
            $table->foreignId('IdFalla')->nullable()->constrained('fallas')->nullOnDelete();
            $table->foreignId('IdAutor')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('IdTecnico')->nullable()->constrained('tecnicos')->nullOnDelete();
            $table->foreignId('IdPrioridad')->nullable()->constrained('prioridads')->nullOnDelete();
            $table->enum('tipo', ['correctivo', 'preventivo', 'entrega']);
            $table->enum('estatus', ['pendiente', 'proceso', 'terminado', 'cancelado'])->default('pendiente');
            $table->text('ticket');
            $table->dateTime('fechaSol');
            $table->dateTime('fechaFin')->nullable();
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdTicket')->constrained('tickets')->cascadeOnDelete();
            $table->string('evidencia', 50);
            $table->string('archivo');
        });
        Schema::create('ticketsSegs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdTicket')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('IdUser')->constrained('users');
            $table->text('comentario');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticketsSegs');
        Schema::dropIfExists('evidencias');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('contratos');
    }
};