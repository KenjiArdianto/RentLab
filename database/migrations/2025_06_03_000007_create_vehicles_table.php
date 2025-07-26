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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types')->onDelete('set null');
            $table->foreignId('vehicle_name_id')->nullable()->constrained('vehicle_names')->onDelete('set null');
            $table->foreignId('vehicle_transmission_id')->nullable()->constrained('vehicle_transmissions')->onDelete('set null');
            $table->integer('engine_cc');
            $table->integer('seats');
            $table->year('year');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('main_image');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
