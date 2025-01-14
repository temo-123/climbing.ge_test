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
        Schema::dropIfExists('ice_routes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('ice_routes', function (Blueprint $table) {
            $table->id();

            
            $table->string('name')->nullable();
            $table->text('text')->nullable();

            $table->string('grade')->nullable();

            $table->string('bolts')->nullable();
            $table->string('author')->nullable();
            $table->string('creation_data')->nullable();
            $table->string('height')->nullable();
            $table->string('first_ascent')->nullable();
            $table->string('anchor_type')->nullable();
            $table->string('category')->nullable();
            $table->string('stars')->nullable();

            $table->integer('num')->nullable();

            $table->foreignId('ice_sector_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }
};
