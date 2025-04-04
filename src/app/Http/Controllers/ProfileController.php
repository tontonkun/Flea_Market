<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Auth;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // プロフィール表示
    public function showProfile(ProfileUpdateRequest $request)
    {
        // ユーザーのプロフィールを取得、無ければ新しいインスタンスを作成
        $profile = Auth::user()->profile ?? new Profile();

        return view('profile', compact('profile'));
    }

    // プロフィール更新
    public function update(ProfileUpdateRequest $request)
    {
        // プロフィールが存在しない場合は新規作成
        $profile = Auth::user()->profile ?? new Profile();

        // プロフィール画像の処理
        if ($request->hasFile('user_image_pass')) {
            // 新しい画像の保存
            $imagePath = $request->file('user_image_pass')->store('profile_images', 'public');

            // 古い画像があれば削除
            if ($profile->user_image_pass) {
                Storage::disk('public')->delete('profile_images/' . $profile->user_image_pass);
            }

            // 画像ファイル名を保存
            $profile->user_image_pass = basename($imagePath);
        }

        // プロフィール情報の更新
        $profile->user_id = Auth::id(); // ユーザーIDをセット
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
