<?php

namespace App\Http\Controllers;

use App\Models\PersonDetail;
use App\Models\FamilyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Exception;

class PersonDetailController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = PersonDetail::with('family');

            if ($user->role === 'volunteer') {
                $query->where('user_id', $user->id);
            } elseif ($user->role === 'admin') {
                $filterableFields = [
                    'full_name', 'gender', 'dob', 'education', 'profession',
                    'mobile_no', 'email', 'aadhar_no', 'marital_status',
                    'special_ability', 'caste', 'relation_with_head'
                ];
    
                foreach ($filterableFields as $field) {
                    if ($request->filled($field)) {
                        $query->where($field, $request->input($field));
                    }
                }
            } else {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            return response()->json($query->paginate(50), 200);
        } catch (\Throwable $e) {
            \Log::error('Person Index Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching records', 'error' => $e->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            if (!in_array(Auth::user()->role, ['admin', 'volunteer'])) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'family_id' => 'required|exists:family_details,id',
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'father_or_husband_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date_format:d-m-Y',
                'gender' => 'required|in:male,female,others',
                'mobile_number' => 'nullable|digits:10',
                'marital_status' => 'required|in:unmarried,married,divorced',
                'education' => 'required|in:uneducated,studing,completed',
                'education_details' => 'nullable|string',
                'education_completion_year' => 'nullable|date_format:Y',
                'occupation' => 'nullable|string|max:255',
                'occupation_details' => 'nullable|string',
                'handicap' => 'required|in:yes,no',
                'handicap_percentage' => 'nullable|numeric|min:1|max:100',
                'handicap_card' => 'nullable|in:yes,no',
                'orphan' => 'required|in:yes,no',
                'aadhar_card_no' => 'required|string|size:16|unique:person_details,aadhar_card_no',
                'government_service' => 'in:yes,no',
                'eligible_for_income_tax' => 'in:yes,no',
                'driving_licence' => 'in:yes,no',
                'election_card' => 'in:yes,no',
                'pan_card' => 'in:yes,no',
                'sharamik_card' => 'in:yes,no',
                'maa_amruta_card' => 'in:yes,no',
                'cast_certificate' => 'in:yes,no',
                'birth_certificate' => 'in:yes,no',
                'insurance_policy' => 'in:yes,no',
                'abha_card' => 'in:yes,no',
                'jandhan_account' => 'in:yes,no',
            ]);

            $family = FamilyDetail::find($validated['family_id']);
            $existingCount = PersonDetail::where('family_id', $validated['family_id'])->count();

            if ($existingCount >= $family->number_of_family_members) {
                return response()->json([
                    'message' => 'This family already has the maximum number of members (' . $family->number_of_family_members . ') recorded.'
                ], 422);
            }

            $validated['user_id'] = Auth::id();
            $validated['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $request->date_of_birth)->format('Y-m-d');
            $validated['age'] = Carbon::parse($validated['date_of_birth'])->age;

            if (!empty($validated['education_completion_year']) && $validated['education_completion_year'] <= now()->year) {
                $validated['education'] = 'completed';
            }

            $data = PersonDetail::create($validated);
            return response()->json(['message' => 'Saved successfully', 'data' => $data], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error storing record', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, PersonDetail $person)
    {
        try {
            // Authorization check
            if (Auth::user()->role !== 'admin' && Auth::id() !== $person->user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'family_id' => 'required|exists:family_details,id',
                'name' => 'string|max:255',
                'surname' => 'string|max:255',
                'father_or_husband_name' => 'string|max:255',
                'mother_name' => 'string|max:255',
                'date_of_birth' => 'date_format:d-m-Y',
                'gender' => 'in:male,female,others',
                'mobile_number' => 'nullable|digits:10',
                'marital_status' => 'in:unmarried,married,divorced',
                'education' => 'in:uneducated,studing,completed',
                'education_details' => 'nullable|string',
                'education_completion_year' => 'nullable|date_format:Y',
                'occupation' => 'nullable|string|max:255',
                'occupation_details' => 'nullable|string',
                'handicap' => 'in:yes,no',
                'handicap_percentage' => 'nullable|numeric|min:1|max:100',
                'handicap_card' => 'nullable|in:yes,no',
                'orphan' => 'in:yes,no',
                'aadhar_card_no' => 'string|size:16|unique:person_details,aadhar_card_no,' . $person->id,
                'government_service' => 'in:yes,no',
                'eligible_for_income_tax' => 'in:yes,no',
                'driving_licence' => 'in:yes,no',
                'election_card' => 'in:yes,no',
                'pan_card' => 'in:yes,no',
                'sharamik_card' => 'in:yes,no',
                'maa_amruta_card' => 'in:yes,no',
                'cast_certificate' => 'in:yes,no',
                'birth_certificate' => 'in:yes,no',
                'insurance_policy' => 'in:yes,no',
                'abha_card' => 'in:yes,no',
                'jandhan_account' => 'in:yes,no',
            ]);

            // Check family member limit if family is being changed
            if ($validated['family_id'] != $person->family_id) {
                $family = FamilyDetail::findOrFail($validated['family_id']);
                $memberCount = PersonDetail::where('family_id', $validated['family_id'])->count();
            
                if ($memberCount >= $family->number_of_family_members) {
                    return response()->json([
                        'message' => 'This family already has the maximum number of members (' . $family->number_of_family_members . ') recorded.'
                    ], 422);
                }
            }

            // Format dates and calculate age
            $validated['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $validated['date_of_birth'])->format('Y-m-d');
        $validated['age'] = Carbon::parse($validated['date_of_birth'])->age;

            // Auto-update education status if completion year is provided
            if (!empty($validated['education_completion_year']) && $validated['education_completion_year'] <= now()->year) {
                $validated['education'] = 'completed';
            }

            $person->update($validated);
            return response()->json(['message' => 'Updated successfully', 'data' => $person]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating record', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(PersonDetail $person)
    {
    // Check if user is authenticated and is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $person->delete();
            return response()->json(['message' => 'Person detail deleted successfully.'], 200);
        } catch (\Exception $e) {
            \Log::error('Person Delete Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}