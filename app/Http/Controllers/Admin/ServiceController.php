<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // GET /api/admin/services
    public function index()
    {
        $services = Service::orderBy('id', 'asc')->get();
        return response()->json($services);
    }

    // POST /api/admin/services
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'tier' => 'required|string',
        ]);

        $service = Service::create($validated);
        return response()->json(['message' => 'Service created successfully', 'service' => $service], 201);
    }

    // PUT /api/admin/services/{id}
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'tier' => 'required|string',
        ]);

        $service = Service::findOrFail($id);
        $service->update($validated);

        return response()->json(['message' => 'Service updated successfully', 'service' => $service]);
    }

    // DELETE /api/admin/services/{id}
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }
}
