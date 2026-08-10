<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $application = $user->applications()->latest()->first();

        if (!$application) {
            $application = Application::create([
                'user_id' => $user->id,
                'title' => 'Green Card Application (I-90 / I-130)',
                'subtitle' => 'Basic Plan',
                'status' => 'Pending',
                'progress' => '25%'
            ]);
        }


        $documents = Document::where('application_id', $application->id)->get();
        return response()->json($documents);
    }

    /**
     * Upload / Store a document for active application
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'doc_type' => 'nullable|string',
            'name' => 'nullable|string'
        ]);

        $user = $request->user();
        $application = $user->applications()->latest()->first();

        if (!$application) {
            $application = Application::create([
                'user_id' => $user->id,
                'title' => 'Green Card Application (I-90 / I-130)',
                'subtitle' => 'Basic Plan',
                'status' => 'Pending',
                'progress' => '25%'
            ]);
        }

        $docNameMap = [
            'prCard' => 'Permanent Resident Card',
            'photoId' => 'Government Issued Photo ID',
            'birthCert' => 'Birth Certificate',
            'policeReport' => 'Police Report',
            'statement' => 'Signed Statement',
            'marriageCert' => 'Marriage Certificate',
            'divorceDecree' => 'Divorce Decree',
            'courtOrder' => 'Court Order',
            'residenceEvidence' => 'Residence Evidence',
            'priorCard' => 'Prior Green Card Copy',
            'otherDocs' => 'Supporting Evidence'
        ];

        $docType = $request->input('doc_type');
        $docName = $request->input('name') ?: ($docNameMap[$docType] ?? ($docType ?: 'Uploaded Document'));

        $file = $request->file('file');
        $filename = $file->hashName();
        $file->storeAs('documents', $filename, 'public');
        $path = 'documents/' . $filename;

        $document = Document::updateOrCreate(
            [
                'application_id' => $application->id,
                'name' => $docName,
            ],
            [
                'status' => 'Uploaded',
                'file_path' => $path
            ]
        );

        return response()->json($document);
    }

    /**
     * Upload a file for an existing document record
     */
    public function upload(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $document = Document::findOrFail($id);

        if ($document->application->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $file = $request->file('file');
        $filename = $file->hashName();
        $file->storeAs('documents', $filename, 'public');
        $path = 'documents/' . $filename;

        $document->update([
            'status' => 'Uploaded',
            'file_path' => $path
        ]);

        return response()->json($document);
    }
}

