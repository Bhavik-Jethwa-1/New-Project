<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersonDetailController extends Controller
{
        // Get all records: Admin sees all, volunteer sees their own
    public function index()
    {
    $user = Auth::user();

    if ($user->role === 'admin') 
    {
        $data = PersonDetail::with('family')->paginate(10);
    } else 
    {
        $data = PersonDetail::with('family')->where('user_id', $user->id)->paginate(10);
    }

    return response()->json($data);
    }

    public function update(Request $request, $id)
    {
    $person = PersonDetail::findOrFail($id);
    $user = Auth::user();

    if (!in_array($user->role, ['admin', 'volunteer'])) 
    {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    if ($user->role === 'volunteer' && $person->user_id !== $user->id) 
    {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        // Same validation rules as in the store() method
        'name' => 'required|string|max:255',
        // ... add all other validation rules here
    ]);

    $person->update($validated);
    return response()->json(['message' => 'Record updated', 'data' => $person]);
    }

// Delete function - Only Admin can delete
    public function destroy($id)
    {
    $person = PersonDetail::findOrFail($id);

    if (Auth::user()->role !== 'admin') 
    {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $person->delete();
    return response()->json(['message' => 'Person detail deleted successfully.']);
    }

// Search function for admin
    public function search(Request $request)
    {
        if (Auth::user()->role !== 'admin') 
    {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $query = PersonDetail::query();

    $allowedFilters = [
        'name', 'surname', 'father_husband_name', 'mother_name', 'date_of_birth',
        'age', 'gender', 'mobile_no', 'marital_status', 'education', 'education_details',
        'occupation', 'handicap', 'orphan', 'aadhar_card_no', 'government_service',
        'income_tax_return', 'driving_license', 'election_card', 'pan_card',
        'shramik_card', 'maa_amruta_card', 'cast_certificate', 'birth_certificate',
        'insurance_policy', 'abha_card', 'jandhan_bank_account', 'family_id'
    ];

    foreach ($allowedFilters as $field) 
    {
        if ($request->filled($field)) 
        {
            $query->where($field, $request->input($field));
        }
    }

    $results = $query->with('family')->paginate(10);

    return response()->json($results);
    }
}
