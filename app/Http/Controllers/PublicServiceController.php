<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;

class PublicServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::with(['services.packages'])->orderBy('order_index')->get();
        return response()->json($categories);
    }
}
