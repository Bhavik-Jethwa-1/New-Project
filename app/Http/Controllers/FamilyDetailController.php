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
            'ward_no', 'vidhan_sabha'
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

    /**
     * Update the specified family detail.
     */
    public function update(Request $request, FamilyDetail $family)
    {
        try {
            // Check if user is allowed to update
            if (!in_array(Auth::user()->role, ['admin', 'volunteer'])) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Volunteers can only update their own records
            if (Auth::user()->role === 'volunteer' && Auth::id() !== $family->user_id) {
                return response()->json(['message' => 'You are not allowed to edit this record.'], 403);
            }

            // Validate request data
            $validatedData = $request->validate([
                'head_of_family' => 'nullable|string|max:122',
                'mobile_number' => 'required|digits:10',
                'village' => 'nullable|string|max:255',
                'taluka' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'sub_caste' => 'nullable|in:1,2,3,4',
                'ration_card' => 'nullable|in:yes,no,APL,BPL',
                'number_of_family_members' => 'nullable|integer|min:1',
                'ward_no' => 'nullable|integer',
                'vidhan_sabha' => 'nullable|string|max:255',
            ]);

            // Update record
            $family->update($validatedData);

            return response()->json([
                'message' => 'Family detail updated successfully.',
                'data' => $family
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Family Update Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating family detail.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified family detail.
     */
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
