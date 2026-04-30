<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('admin.locations.index', [
            'locations' => Location::all()
        ]);
    }

    public function store(Request $request)
    {
        Location::create($request->all());
        return back();
    }

    public function update(Request $request, $id)
    {
        Location::findOrFail($id)->update($request->all());
        return back();
    }

    public function destroy($id)
    {
        Location::destroy($id);
        return back();
    }
}