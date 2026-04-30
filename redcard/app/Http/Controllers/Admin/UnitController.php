<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index()
    {
        return view('admin.units.index', [
            'units' => Unit::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:units,code'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
            'description' => ['nullable', 'string', 'max:5000'],
            
        ]);

        Unit::create($data);

        return back()->with('success', 'Unit berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::query()->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('units', 'code')->ignore($unit->id),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
        ]);

        $unit->update($data);

        return back()->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Unit::query()->whereKey($id)->delete();

        return back()->with('success', 'Unit berhasil dihapus.');
    }
}