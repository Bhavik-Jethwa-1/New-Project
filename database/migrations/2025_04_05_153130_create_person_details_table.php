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
        Schema::create('person_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('family_id')->constrained('family_details')->onDelete('cascade');
        
            $table->string('name');
            $table->string('surname');
            $table->string('father_or_husband_name');
            $table->string('mother_name');
            $table->date('date_of_birth');
            $table->integer('age')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('mobile_no')->nullable();
            $table->enum('marital_status', ['unmarried', 'married', 'divorced']);
            $table->enum('education', ['uneducated', 'studying', 'completed']);
            $table->string('education_details')->nullable();
            $table->year('education_completion_year')->nullable();
            $table->string('aadhar_card_no', 16)->unique();
            $table->string('occupation')->nullable();
            $table->text('occupation_details')->nullable();
            $table->enum('handicap', ['yes', 'no']);
            $table->integer('handicap_percentage')->nullable();
            $table->enum('handicap_card', ['yes', 'no'])->nullable();
            $table->enum('orphan', ['yes', 'no']);
            $table->enum('government_service', ['yes', 'no'])->nullable();
            $table->enum('eligible_for_income_tax', ['yes', 'no'])->nullable();
            $table->enum('driving_licence', ['yes', 'no'])->nullable();
            $table->enum('election_card', ['yes', 'no'])->nullable();
            $table->enum('pan_card', ['yes', 'no'])->nullable();
            $table->enum('sharamik_card', ['yes', 'no'])->nullable();
            $table->enum('maa_amruta_card', ['yes', 'no']);
            $table->enum('cast_certificate', ['yes', 'no']);
            $table->enum('birth_certificate', ['yes', 'no']);
            $table->enum('insurance_policy', ['yes', 'no']);
            $table->enum('abha_card', ['yes', 'no']);
            $table->enum('jandhan_account', ['yes', 'no']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_details');
    }
};
