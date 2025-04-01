<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'surname', 'father_name', 'mother_name',  'date_of_birth', 'gender',
        'mobile_no', 'is_whatsapp', 'village', 'taluka', 'district', 'address', 'education_status',
        'education_details', 'occupation', 'handicap', 'orphan', 'sub_caste', 'aadhar_card_no',
        'ward_no', 'vidhan_sabha', 'government_service'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
