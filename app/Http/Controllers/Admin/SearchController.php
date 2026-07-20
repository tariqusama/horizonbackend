<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Application;

class SearchController extends Controller
{
    // GET /api/admin/search?q={query}
    public function search(Request $request)
    {
        $query = $request->query('q');

        if (!$query) {
            return response()->json(['users' => [], 'cases' => [], 'tickets' => []]);
        }

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->get();

        $cases = Application::where('application_id', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->with('user')
            ->get();

        $tickets = Ticket::where('ticket_id', 'like', "%{$query}%")
            ->orWhere('subject', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->with('user')
            ->get();

        return response()->json([
            'users' => $users,
            'cases' => $cases,
            'tickets' => $tickets,
        ]);
    }
}
