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
        Schema::create('public_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->string('surname');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('mobile_no', 10)->unique();
            $table->string('is_whatsapp');
            $table->string('village');
            $table->string('taluka');
            $table->string('district');
            $table->text('address');
            $table->string('education_status');
            $table->string('education_details')->nullable();
            $table->string('occupation');
            $table->string('handicap');
            $table->string('orphan');
            $table->enum('sub_caste', ['1', '2', '3', '4']);
            $table->string('aadhar_card_no', 16)->unique();
            $table->integer('ward_no');
            $table->string('vidhan_sabha');
            $table->string('government_service');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_details');
    }
};
