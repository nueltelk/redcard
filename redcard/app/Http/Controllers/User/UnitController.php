<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $units = Unit::when($search, function ($query, $search) {
            return $query
                ->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('code', 'like', "%$search%")
                        ->orWhereHas('categories', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%$search%");
                        });
                });
        })
            ->with('categories')
            ->orderBy('name')
            ->get();

        $locations = Location::query()->orderBy('name')->get();

        return view('user.units.index', compact('units', 'search', 'locations'));
    }
}