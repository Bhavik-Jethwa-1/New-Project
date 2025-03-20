<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VolunteerRequest;
use App\Models\User;

class VolunteerRequestController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'volunteer') 
        {
            return response()->json(['message' => 'You are already a volunteer and cannot submit another request.'], 400);
        }
        $existingRequest = VolunteerRequest::where('user_id', $user->id)->first();

        if ($existingRequest) 
        {
            return response()->json(['message' => 'You already have a pending or processed request.'], 400);
        }

        $volunteerRequest = VolunteerRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
        return response()->json(['message' => 'Volunteer request submitted successfully.', 'request' => $volunteerRequest], 201);
    }

    public function update(Request $request, $id)
    {
    // Validate the status input
        $request->validate([
        'status' => 'required|in:approved,rejected',
        ]);

    // Find the volunteer request by ID
        $volunteerRequest = VolunteerRequest::findOrFail($id);

    // Get the user associated with this volunteer request
        $user = $volunteerRequest->user;

        if (!$user) 
        {
            return response()->json(['message' => 'User not found for this volunteer request.'], 404);
        }

        if ($request->status === 'approved') 
        {
        // Update the user's role to 'volunteer'
            $user->update(['role' => 'volunteer']);

        // Delete the volunteer request from the table
            $volunteerRequest->delete();

            return response()->json([
                'message' => 'Request approved successfully. User role updated to volunteer.',
            ], 200);
        }

        if ($request->status === 'rejected') 
        {
        // Delete the volunteer request from the table
            $volunteerRequest->delete();

            return response()->json([
                'message' => 'Request rejected and deleted successfully.',
            ], 200);
        }
    }


    public function request_index()
    {
        $pendingRequests = VolunteerRequest::where('status', 'pending')->with('user')->get();

        return response()->json(['pending_requests' => $pendingRequests]);
    }

    public function index()
    {
        $users = User::where('role', 'volunteer')->get();
        return response()->json($users);
    }
}
