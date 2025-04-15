<?php

namespace App\Http\Controllers;

use App\Models\FamilyDetail;
use App\Models\PersonDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class FamilyDetailController extends Controller
{
   // 🔍 Index function with role-based access and filters
   public function index(Request $request)
   {
       $user = Auth::user();
       $query = FamilyDetail::with('personDetails');

       if ($user->role === 'volunteer') {
           $query->where('user_id', $user->id);
       } elseif ($user->role === 'admin') {
           $filterableFields = [
               'head_name', 'mobile_no', 'village', 'taluka', 'district',
               'address', 'sub_caste', 'ration_card', 'no_of_family_members',
               'ward_no', 'vidhan_sabha'
           ];

           foreach ($filterableFields as $field) {
               if ($request->has($field)) {
                   $query->where($field, $request->input($field));
               }
           }
       } else {
           return response()->json(['message' => 'Unauthorized'], 403);
       }

       return response()->json($query->paginate(10), 200);
   }

   // ➕ Store new family detail
   public function store(Request $request)
   {
       if (!in_array(Auth::user()->role, ['admin', 'volunteer'])) {
           return response()->json(['message' => 'Unauthorized'], 403);
       }

       $validatedData = $request->validate([
           'head_of_family' => 'required|string|max:122',
           'mobile_number' => 'required|unique:family_details|string|max:10|min:10',
           'village' => 'required|string|max:255',
           'taluka' => 'required|string|max:255',
           'district' => 'required|string|max:255',
           'address' => 'required|string',
           'sub_caste' => 'required|in:1,2,3,4',
           'ration_card' => 'required|in:yes,no,APL,BPL',
           'number_of_family_members' => 'required|integer|min:1',
           'ward_no' => 'required|integer',
           'vidhan_sabha' => 'required|string|max:255',
       ]);

       $validatedData['user_id'] = Auth::id();

       $family = FamilyDetail::create($validatedData);

       return response()->json(['message' => 'Family detail added successfully.', 'data' => $family], 201);
   }

   // 📝 Update family details (admin and volunteer)
   public function update(Request $request, $id)
   {
       $family = FamilyDetail::findOrFail($id);

       if (!in_array(Auth::user()->role, ['admin', 'volunteer'])) {
           return response()->json(['message' => 'Unauthorized'], 403);
       }

       if (Auth::user()->role === 'volunteer' && Auth::id() !== $family->user_id) {
           return response()->json(['message' => 'You are not allowed to edit this record.'], 403);
       }

       $validatedData = $request->validate([
           'head_of_family' => 'string|max:122',
           'mobile_number' => 'required|string|max:10|min:10',
           'village' => 'string|max:255',
           'taluka' => 'string|max:255',
           'district' => 'string|max:255',
           'address' => 'string',
           'sub_caste' => 'in:1,2,3,4',
           'ration_card' => 'in:yes,no,APL,BPL',
           'number_of_family_members' => 'integer|min:1',
           'ward_no' => 'integer',
           'vidhan_sabha' => 'string|max:255',
       ]);

       $family->update($validatedData);

       return response()->json(['message' => 'Family detail updated successfully.', 'data' => $family], 200);
   }

   // ❌ Delete family detail (only admin)
   public function destroy($id)
   {
       if (Auth::user()->role !== 'admin') {
           return response()->json(['message' => 'Only admin can delete.'], 403);
       }

       $family = FamilyDetail::findOrFail($id);
       $family->delete();

       return response()->json(['message' => 'Family detail deleted successfully.'], 200);
   }
}
