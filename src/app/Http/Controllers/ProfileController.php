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
        // 既存のユーザーのプロフィールを取得
        $profile = Auth::user()->profile;

        return view('profile', compact('profile'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        // プロフィール画像の処理
        if ($request->hasFile('profile_image')) {
            // 新しい画像の保存
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');

            // 古い画像があれば削除
            if (Auth::user()->profile && Auth::user()->profile->profile_image) {
                Storage::disk('public')->delete('profile_images/' . Auth::user()->profile->profile_image);
            }

            // ユーザーのプロフィールを取得し、画像ファイル名を保存
            Auth::user()->profile->profile_image = basename($imagePath);
        }

        // プロフィールが既に存在するかどうかを確認
        $profile = Auth::user()->profile ?? new Profile();

        // プロフィール情報の更新
        $profile->user_id = Auth::id();
        $profile->post_code = $request->input('postal_code');
        $profile->user_name = $request->input('user_name');
        $profile->address = $request->input('address');
        $profile->building_name = $request->input('building_name');

        // プロフィールを保存
        $profile->save();

        // 最初のログインフラグを false に更新
        Auth::user()->is_first_login = false;
        Auth::user()->save();  // ユーザー情報を保存して更新

        // 更新後、リダイレクト
        return redirect('/')->with('success', 'プロフィールが更新されました。');
    }
}
