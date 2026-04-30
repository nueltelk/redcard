<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->profile;
        return view('user.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = auth()->user()->profile;

        $profile->update([
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return back()->with('success', 'Profile updated');
    }
}