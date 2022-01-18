<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function create()
    {
        return view('regist.register');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|confirmed|min:8',
            ]
        );

        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        );

        // sample.jpgを読み込む
        $init_img = Storage::disk('public')->get('sample.jpg');
        // request変数に要素を追加
        // $request->merge(['icon' => $init_img]);
        // id.jpgとして保存する
        $id = $user->id;
        $filename = 'public/icon/'.$id.'.jpg';
        Storage::put($filename, $init_img);

        event(new Registered($user));

        return view('regist.complete', compact('user'));
    }
}
