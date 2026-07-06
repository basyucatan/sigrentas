<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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
            $table->dateTime('fechaPro');
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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdCuarto')->constrained('cuartos')->restrictOnDelete();
            $table->foreignId('IdInquilino')->constrained('inquilinos')->restrictOnDelete();
            $table->foreignId('IdPropietario')->constrained('propietarios')->restrictOnDelete();
            $table->date('fechaIni');
            $table->date('fechaFin'); 
            $table->decimal('montoRenta',10,2);
            $table->decimal('deposito',10,2);
            $table->decimal('penaEntrega',10,2);
            $table->string('docContrato', 100)->nullable();
            $table->string('docInvMuebles', 100)->nullable();
            $table->string('firma', 100)->nullable();
            $table->json('adicionales')->nullable();
        });
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdContrato')->constrained('contratos')->cascadeOnDelete();
            $table->decimal('montoRenta',10,2);
            $table->date('fechaVence');
            $table->json('adicionales')->nullable();
        });
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdRecibo')->constrained('recibos')->cascadeOnDelete();
            $table->decimal('montoPago',10,2);
            $table->date('fecha');
            $table->json('adicionales')->nullable();
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