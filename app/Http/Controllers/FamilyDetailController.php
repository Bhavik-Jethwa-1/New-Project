<?php

namespace App\Http\Controllers;

use App\Models\FamilyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FamilyDetailController extends Controller
{
    /**
     * Display a listing of family details with filters.
     */
    public function index(Request $request)
    {
    try {
        $user = Auth::user();
        $query = FamilyDetail::with('personDetails');

        // Start filtering logic
        $filterableFields = [
            'head_name', 'mobile_no', 'village', 'taluka', 'district',
            'address', 'sub_caste', 'ration_card', 'no_of_family_members',
            'ward_no', 'vidhan_sabha',
        ];

        // If volunteer, restrict to their own entries
        if ($user->role === 'volunteer') {
            $query->where('user_id', $user->id);

            foreach ($filterableFields as $field) {
                if ($request->filled($field)) {
                    $query->where($field, $request->input($field));
                }
            }
        }

        // If admin, show all or filter by query
        elseif ($user->role === 'admin') {
            foreach ($filterableFields as $field) {
                if ($request->filled($field)) {
                    $query->where($field, $request->input($field));
                }
            }
        }

        else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($query->paginate(50), 200);

        } catch (\Throwable $e) {
            Log::error('Family Index Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching records',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created family detail.
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'volunteer'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $validatedData = $request->validate([
                'head_of_family' => 'required|string|max:122',
                'mobile_number' => 'required|unique:family_details|digits:10',
                'village' => 'required|string|max:255',
                'taluka' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'address' => 'required|string',
                'sub_caste' => 'required|in:1,2,3,4',
                'ration_card' => 'required|in:none,APL,BPL',
                'number_of_family_members' => 'required|integer|min:1',
                'ward_no' => 'required|integer',
                'vidhan_sabha' => 'required|string|max:255',
            ]);

            $validatedData['user_id'] = Auth::id();

            $family = FamilyDetail::create($validatedData);

            return response()->json([
                'message' => 'Family detail added successfully.',
                'data' => $family
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Family Store Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating family detail.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, FamilyDetail $family)
    {
        try {
            Log::info('User Role:', ['role' => Auth::user()->role]);
            // Check if user is allowed to update
            if (!in_array(Auth::user()->role, ['admin', 'volunteer'])) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Allow admin to update any record
            if (Auth::user()->role === 'volunteer') {
                if (Auth::id() !== $family->user_id) {
                    return response()->json(['message' => 'You are not allowed to edit this record.'], 403);
                }
            }
        // Validate only fields present in the request
        $validatedData = $request->validate([
            'head_of_family' => 'sometimes|string|max:122',
            'mobile_number' => 'sometimes|string|digits:10',
            'village' => 'sometimes|string|max:255',
            'taluka' => 'sometimes|string|max:255',
            'district' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'sub_caste' => 'sometimes|in:1,2,3,4',
            'ration_card' => 'sometimes|in:yes,no,APL,BPL',
            'number_of_family_members' => 'sometimes|integer|min:1',
            'ward_no' => 'sometimes|integer',
            'vidhan_sabha' => 'sometimes|string|max:255',
        ]);

        Log::info('Validated update data:', $validatedData);

        // Update only the fields provided
        if (!$family->update($validatedData)) {
            return response()->json(['message' => 'Update failed.'], 500);
        }
        $family->update($validatedData);

        return response()->json([
            'message' => 'Family detail updated successfully.',
            'data' => $family
        ], 200);

        } catch (\Throwable $e) {
            Log::error('Family Update Error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'family_id' => $family->id
            ]);
            return response()->json([
                'message' => 'Error updating family detail.',
                'error' => 'An unexpected error occurred.'
                ], 500);
        }
    }

    public function destroy(FamilyDetail $family)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Only admin can delete.'], 403);
        }

        try {
            $family->delete();

            return response()->json(['message' => 'Family detail deleted successfully.'], 200);
        } catch (\Throwable $e) {
            \Log::error('Family Delete Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting record.', 'error' => $e->getMessage()], 500);
        }
    }

}
