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
            'locations' => Location::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:locations,name'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        Location::create($data);

        return back()->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $location = Location::query()->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:locations,name,' . $location->id],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $location->update($data);

        return back()->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $location = Location::query()->findOrFail($id);

        try {
            $location->delete();
        } catch (\Throwable $e) {
            return back()->withErrors([
                'location_delete' => 'Lokasi tidak bisa dihapus karena sedang dipakai pada data peminjaman.',
            ]);
        }

        return back()->with('success', 'Lokasi berhasil dihapus.');
    }
}