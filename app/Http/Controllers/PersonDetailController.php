<?php

namespace App\Http\Controllers;

use App\Models\PersonDetail;
use App\Models\FamilyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class PersonDetailController extends Controller
{
    // INDEX: Admin sees all, volunteer sees their own
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $data = PersonDetail::with('family')->paginate(20);
        } else {
            $data = PersonDetail::where('user_id', Auth::id())->with('family')->paginate(20);
        }

        return response()->json($data);
    }

    // STORE
    public function store(Request $request)
    {
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
            'mobile_number' => 'nullable|string|size:10',
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

        // 🛑 Prevent over-entry into the family
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
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $data = PersonDetail::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() !== $data->user_id) {
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
            'mobile_number' => 'nullable|string|size:10',
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
            'aadhar_card_no' => 'string|size:16|unique:person_details,aadhar_card_no,' . $data->id,
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

        // ✅ Optional: prevent assigning to a full family
        if ($validated['family_id'] != $data->family_id) {
            $family = FamilyDetail::find($validated['family_id']);
            $existingCount = PersonDetail::where('family_id', $validated['family_id'])->count();
            if ($existingCount >= $family->number_of_family_members) {
                return response()->json([
                    'message' => 'This family already has the maximum number of members (' . $family->number_of_family_members . ') recorded.'
                ], 422);
            }
        }

        $validated['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $validated['date_of_birth'])->format('Y-m-d');
        $validated['age'] = Carbon::parse($validated['date_of_birth'])->age;

        if (!empty($validated['education_completion_year']) && $validated['education_completion_year'] <= now()->year) {
            $validated['education'] = 'completed';
        }

        $data->update($validated);
        return response()->json(['message' => 'Updated successfully', 'data' => $data]);
    }

    // DESTROY
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = PersonDetail::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    // SEARCH (admin only)
    public function search(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = PersonDetail::query();

        $searchableFields = [
            'name', 'surname', 'father_or_husband_name', 'mother_name', 'gender', 'marital_status', 'education',
            'occupation', 'handicap', 'orphan', 'aadhar_card_no', 'government_service', 'eligible_for_income_tax',
            'driving_licence', 'election_card', 'pan_card', 'sharamik_card', 'maa_amruta_card', 'cast_certificate',
            'birth_certificate', 'insurance_policy', 'abha_card', 'jandhan_account', 'mobile_number', 'age'
        ];

        foreach ($searchableFields as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->$field);
            }
        }

        return response()->json($query->paginate(20));
    }
}
