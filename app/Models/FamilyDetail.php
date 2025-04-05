<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'head_of_family', 'mobile_number', 'village', 'taluka',
        'district', 'address', 'sub_caste', 'ration_card', 
        'number_of_family_members', 'ward_no', 'vidhan_sabha',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function persons()
    {
        return $this->hasMany(PersonDetail::class, 'family_id');
    }
}
