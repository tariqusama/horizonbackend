<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    // GET /api/admin/audit-logs
    public function index()
    {
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($logs);
    }
}
