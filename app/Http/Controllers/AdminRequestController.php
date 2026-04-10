<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;

class AdminRequestController extends Controller
{
    public function index()
    {
        $requests = DocumentRequest::with('trainee.filiere')
            ->orderByRaw("FIELD(status, 'en_attente') DESC")
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Mark as read when admin visits the inbox
        DocumentRequest::where('is_read_by_admin', false)->update(['is_read_by_admin' => true]);

        return view('admin.requests.index', compact('requests'));
    }

    public function schedule(Request $request, DocumentRequest $docRequest)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'admin_message' => 'nullable|string'
        ]);

        $docRequest->update([
            'status' => 'planifie',
            'appointment_date' => $request->appointment_date,
            'admin_message' => $request->admin_message,
        ]);

        return back()->with('success', 'Le rendez-vous a été fixé et envoyé au stagiaire.');
    }

    public function complete(DocumentRequest $docRequest)
    {
        $docRequest->update([
            'status' => 'termine'
        ]);

        return back()->with('success', 'La demande a été marquée comme terminée.');
    }

    public function reject(Request $request, DocumentRequest $docRequest)
    {
        $request->validate([
            'admin_message' => 'required|string'
        ]);

        $docRequest->update([
            'status' => 'rejete',
            'admin_message' => $request->admin_message,
        ]);

        return back()->with('error', 'La demande a été rejetée.');
    }

    // API to check for new requests (poll)
    public function checkNew()
    {
        $newCount = DocumentRequest::where('is_read_by_admin', false)
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        return response()->json([
            'has_new' => $newCount > 0,
            'count' => $newCount
        ]);
    }
}
