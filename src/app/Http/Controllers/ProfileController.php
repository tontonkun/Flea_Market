<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Auth;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showProfile(ProfileUpdateRequest $request)
    {
        return view('myPage');
    }
    
    public function update(ProfileUpdateRequest $request)
    {
        if ($request->hasFile('profile_image')) {
            // 画像の保存
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            // ファイル名を保存
            Auth::user()->profile_image = basename($imagePath);
            Auth::user()->save();
        }


        // Find or create the profile for the authenticated user
        $profile = Auth::user()->profile ?? new Profile();
        $profile->user_id = Auth::id();
        $profile->post_code = $request->input('postal_code');
        $profile->address = $request->input('address');
        $profile->building_name = $request->input('building_name');
        $profile->save();

        return redirect('/');

    }
}