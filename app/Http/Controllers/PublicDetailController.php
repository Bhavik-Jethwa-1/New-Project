<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicDetail;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsVolunteer;

class PublicDetailController extends Controller
{
    // Store new public detail
    public function store(Request $request)
    {
        if (!Auth::user() || !in_array(Auth::user()->role, ['admin', 'volunteer'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $validatedData = $request->validate([
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'surname' => 'required|string',
            'date_of_birth' => 'required|string', // Accepting as string to process formatting
            'gender' => 'required|in:Male,Female,Other',
            'mobile_no' => 'required|string|size:10|unique:public_details,mobile_no',
            'is_whatsapp' => 'string|in:yes,no',
            'village' => 'required|string',
            'taluka' => 'required|string',
            'district' => 'required|string',
            'address' => 'required|string',
            'marital_status' => 'required|string',
            'education_status' => 'required|in:Studying,Completed',
            'education_details' => 'nullable|string',
            'applied_scholarship' => 'string|in:yes,no',
            'occupation' => 'required|string',
            'occupation_details' => 'nullable|string',
            'handicap' => 'string|in:yes,no',
            'handicap_percentage' => 'nullable|numeric|min:0|max:100',
            'handicap_card' => 'string|in:yes,no',
            'orphan' => 'string|in:yes,no',
            'sub_caste' => 'required|in:1,2,3,4',
            'aadhar_card_no' => 'required|string|size:16|unique:public_details,aadhar_card_no',
            'ward_no' => 'required|integer',
            'vidhan_sabha' => 'required|string',
            'government_service' => 'required|string|in:yes,no',
        ]);
    
        // Convert date format from DD-MM-YYYY to YYYY-MM-DD
        $validatedData['date_of_birth'] = date('Y-m-d', strtotime(str_replace('/', '-', $validatedData['date_of_birth'])));
    
        $validatedData['user_id'] = Auth::id(); // Store the user who created it
    
        $publicDetail = PublicDetail::create($validatedData);
        return response()->json(['message' => 'Data saved successfully', 'data' => $publicDetail], 201);
    }
    // Update existing public detail (only by owner or admin)
    public function update(Request $request, $id)
    {
        $publicDetail = PublicDetail::findOrFail($id);
    
        // Check if user is admin or the owner of the record
        if (Auth::user()->role !== 'admin' && Auth::id() !== $publicDetail->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $validatedData = $request->validate([
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'surname' => 'required|string',
            'date_of_birth' => 'required|string', // Accepting as string to process formatting
            'gender' => 'required|in:Male,Female,Other',
            'mobile_no' => 'required|string|size:10|unique:public_details,mobile_no,' . $id, // Ignore unique constraint for the same record
            'is_whatsapp' => 'string|in:yes,no',
            'village' => 'required|string',
            'taluka' => 'required|string',
            'district' => 'required|string',
            'address' => 'required|string',
            'marital_status' => 'required|string',
            'education_status' => 'required|in:Studying,Completed',
            'education_details' => 'nullable|string',
            'applied_scholarship' => 'string|in:yes,no',
            'occupation' => 'required|string',
            'occupation_details' => 'nullable|string',
            'handicap' => 'string|in:yes,no',
            'handicap_percentage' => 'nullable|numeric|min:0|max:100',
            'handicap_card' => 'string|in:yes,no',
            'orphan' => 'string|in:yes,no',
            'sub_caste' => 'required|in:1,2,3,4',
            'aadhar_card_no' => 'required|string|size:16|unique:public_details,aadhar_card_no,' . $id, // Ignore unique constraint for the same record
            'ward_no' => 'required|integer',
            'vidhan_sabha' => 'required|string',
            'government_service' => 'required|string|in:yes,no',
        ]);
    
        // Convert `DD/MM/YYYY` to `YYYY-MM-DD` before saving
        $validatedData['date_of_birth'] = date('Y-m-d', strtotime(str_replace('/', '-', $validatedData['date_of_birth'])));
    
        $publicDetail->update($validatedData);
    
        return response()->json([
            'message' => 'Data updated successfully', 
            'data' => $publicDetail
        ], 200);
    }

    // Admin: Search for public details
    public function search(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = PublicDetail::query();

        // Filter by each field if provided in the request
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('surname')) {
            $query->where('surname', 'like', '%' . $request->surname . '%');
        }
        if ($request->has('date_of_birth')) {
            $query->whereDate('date_of_birth', $request->date_of_birth);
        }
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->has('mobile_no')) {
            $query->where('mobile_no', $request->mobile_no);
        }
        if ($request->has('village')) {
            $query->where('village', 'like', '%' . $request->village . '%');
        }
        if ($request->has('taluka')) {
            $query->where('taluka', 'like', '%' . $request->taluka . '%');
        }
        if ($request->has('district')) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }
        if ($request->has('education_status')) {
            $query->where('education_status', $request->education_status);
        }
        if ($request->has('education_details')) {
            $query->where('education_details', 'like', '%' . $request->education_details . '%');
        }
        if ($request->has('occupation')) {
            $query->where('occupation', 'like', '%' . $request->occupation . '%');
        }
        if ($request->has('handicap')) {
            $query->where('handicap', $request->handicap);
        }
        if ($request->has('orphan')) {
            $query->where('orphan', $request->orphan);
        }
        if ($request->has('sub_caste')) {
            $query->where('sub_caste', $request->sub_caste);
        }
        if ($request->has('aadhar_card_no')) {
            $query->where('aadhar_card_no', $request->aadhar_card_no);
        }
        if ($request->has('ward_no')) {
            $query->where('ward_no', $request->ward_no);
        }
        if ($request->has('vidhan_sabha')) {
            $query->where('vidhan_sabha', 'like', '%' . $request->vidhan_sabha . '%');
        }
        if ($request->has('government_service')) {
            $query->where('government_service', $request->government_service);
        }

        // Return paginated results
        $results = $query->paginate(10);

        // Convert date of birth to DD/MM/YYYY format
        $results->getCollection()->transform(function ($record) {
            $record->date_of_birth = date('d/m/Y', strtotime($record->date_of_birth));
            return $record;
        });
    
        return response()->json($results, 200);
    }     

    public function destroy($id)
    {
        $publicDetail = PublicDetail::findOrFail($id);

        // Check if the user is admin or the owner of the record
        if (Auth::user()->role !== 'admin' && Auth::id() !== $publicDetail->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete the record
        $publicDetail->delete();

        return response()->json(['message' => 'Data deleted successfully'], 200);
    }
}
