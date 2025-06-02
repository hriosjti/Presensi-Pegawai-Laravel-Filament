<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLokasiKantorTable extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi_kantor', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('radius_meter');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi_kantor');
    }
}
