<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        $category = Category::create([
            'name' => $data['category_name'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil ditambahkan.',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
            ]);
        }

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        Category::findOrFail($id)->update($request->all());
        return back();
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return back();
    }
}