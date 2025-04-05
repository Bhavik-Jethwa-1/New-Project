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
        Schema::create('family_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('head_of_family');
            $table->string('mobile_number');
            $table->string('village');
            $table->string('taluka');
            $table->string('district');
            $table->text('address');
            $table->enum('sub_caste', ['1', '2', '3', '4']);
            $table->enum('ration_card', ['APL', 'BPL','NONE']);
            $table->integer('number_of_family_members');
            $table->integer('ward_no');
            $table->string('vidhan_sabha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_details');
    }
};
