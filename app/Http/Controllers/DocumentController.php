<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $application = $request->user()->applications()->latest()->first();

        if (!$application) {
            return response()->json([]);
        }

        return response()->json($application->documents);
    }

    /**
     * Upload a document
     */
    public function upload(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $document = Document::findOrFail($id);

        // Ensure the document belongs to the user's application
        if ($document->application->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $path = $request->file('file')->store('public/documents');

        $document->update([
            'status' => 'Uploaded',
            'file_path' => $path
        ]);

        return response()->json($document);
    }
}
