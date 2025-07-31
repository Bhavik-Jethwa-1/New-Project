<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VolunteerRequest;
use App\Models\User;
use Exception;

// Route::middleware('auth:sanctum')->get('/volunteer/person-details', [VolunteerRequestController::class, 'personDetails']);

class VolunteerRequestController extends Controller
{
    public function update(Request $request, VolunteerRequest $volunteerRequest)
    {
        try {
            $request->validate([
                'status' => 'required|in:approved,rejected',
            ]); 

            $user = $volunteerRequest->user;

            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            if ($request->status === 'approved') {
                $user->update(['role' => 'volunteer']);
                $volunteerRequest->delete();

                return response()->json([
                    'message' => 'Request approved. User is now a volunteer.'
                ], 200);
            }

            // Rejected
            $volunteerRequest->delete();
            return response()->json([
                'message' => 'Request rejected and deleted.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to process request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function request_index()
    {
        try {
            $pendingRequests = VolunteerRequest::where('status', 'pending')
                ->with('user')
                ->get();

            return response()->json(['pending_requests' => $pendingRequests]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve pending requests.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $volunteers = User::where('role', 'volunteer')->get();
            return response()->json($volunteers);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve volunteers.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // VolunteerRequestController.php
public function personDetails(Request $request)
{
    // $user = $request->user();
    $user = auth()->user();

    if (!$user || auth()->user()->role !== 'volunteer') {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
    ], 200);
}

}
