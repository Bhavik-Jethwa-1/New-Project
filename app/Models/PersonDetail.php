<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'family_id', 'name', 'surname', 'father_or_husband_name',
        'mother_name', 'date_of_birth', 'age', 'gender', 'mobile_no',
        'marital_status', 'education', 'education_details',
        'education_completion_year', 'occupation', 'occupation_details',
        'handicap', 'handicap_percentage', 'handicap_card', 'orphan',
        'aadhar_card_no', 'government_service', 'eligible_for_income_tax',
        'driving_licence', 'election_card', 'pan_card', 'sharamik_card',
        'maa_amruta_card', 'cast_certificate', 'birth_certificate',
        'insurance_policy', 'abha_card', 'jandhan_account',
    ];

    public function family()
    {
        return $this->belongsTo(FamilyDetail::class, 'family_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
