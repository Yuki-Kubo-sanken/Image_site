<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    // ホームページの表示
    public function create_home()
    {
        $works = Work::all();
        $data = ['works'=>$works];
        return view('home', $data);
    }
    // 検索ページの表示
    public function get_searchword(Request $request)
    {
        // validation
        $request->validate(
            [
                'word' => 'required',
            ]
        );
        // キーワードの導入
        $word = $request->word;
        return redirect('/search/'.$word);
    }
    public function search(Request $request, $word)
    {
        // IDで指定された作品をデータベースから取得する
        $works = Work::where('worktitle', 'like', "%$word%")->get();
        $data = ['works'=>$works];
        // 取得した作品データをブレード（作品用（これは自力で作る）の）に渡す（work.blade→mypage_work.bladeにする）
        return view('search', $data);
    }
}
