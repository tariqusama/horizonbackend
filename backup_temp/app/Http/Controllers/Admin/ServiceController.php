<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('packages')->orderBy('id', 'asc')->get();
        return response()->json($services);
    }

    // POST /api/admin/services
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'starting_price' => 'required|numeric|min:0',
            'processing_time' => 'nullable|string',
            'is_popular' => 'boolean',
            'order_index' => 'integer',
            'packages' => 'nullable|array',
            'packages.*.name' => 'required|string',
            'packages.*.price' => 'required|numeric|min:0',
            'packages.*.features' => 'nullable|array',
            'packages.*.order_index' => 'integer'
        ]);

        $service = Service::create(\Illuminate\Support\Arr::except($validated, ['packages']));

        if (isset($validated['packages'])) {
            foreach ($validated['packages'] as $pkgData) {
                $service->packages()->create($pkgData);
            }
        }

        return response()->json(['message' => 'Service created successfully', 'service' => $service->load('packages')], 201);
    }

    // PUT /api/admin/services/{id}
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'starting_price' => 'required|numeric|min:0',
            'processing_time' => 'nullable|string',
            'is_popular' => 'boolean',
            'order_index' => 'integer',
            'packages' => 'nullable|array',
            'packages.*.id' => 'nullable|integer',
            'packages.*.name' => 'required|string',
            'packages.*.price' => 'required|numeric|min:0',
            'packages.*.features' => 'nullable|array',
            'packages.*.order_index' => 'integer'
        ]);

        $service = Service::findOrFail($id);
        $service->update(\Illuminate\Support\Arr::except($validated, ['packages']));

        if (isset($validated['packages'])) {
            $existingPackageIds = $service->packages()->pluck('id')->toArray();
            $newPackageIds = collect($validated['packages'])->pluck('id')->filter()->toArray();
            
            // Delete packages that are no longer in the request
            $service->packages()->whereNotIn('id', $newPackageIds)->delete();

            foreach ($validated['packages'] as $pkgData) {
                if (isset($pkgData['id']) && in_array($pkgData['id'], $existingPackageIds)) {
                    $service->packages()->where('id', $pkgData['id'])->update(\Illuminate\Support\Arr::except($pkgData, ['id']));
                } else {
                    $service->packages()->create($pkgData);
                }
            }
        }

        return response()->json(['message' => 'Service updated successfully', 'service' => $service->fresh('packages')]);
    }

    // DELETE /api/admin/services/{id}
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }
}
