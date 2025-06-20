<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Para MySQL
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('activa', 'entregada', 'cerrada') DEFAULT 'activa'");
        
        // Alternativa para otros motores de BD
        /*
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 20)->default('activa')->change();
        });
        */
    }

    public function down()
    {
        // Para MySQL
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('activa', 'cerrada') DEFAULT 'activa'");
        
        // Alternativa para otros motores de BD
        /*
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['activa', 'cerrada'])->default('activa')->change();
        });
        */
    }
};