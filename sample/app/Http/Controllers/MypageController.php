<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    // マイページの表示
    public function create()
    {
        $works = Work::where('userID', Auth::user()->id)->get();
        $data = ['works'=>$works];
        return view('mypage', $data);
    }
    // 投稿画面の表示
    public function create_submit()
    {
        return view('submit');
    }
    // 画像保存画面の表示
    public function submit(Request $request)
    {
        // バリデーションの実施
        // TODO:worktitleで禁止文字（'_'）を与える
        $request->validate(
            [
                'work' => 'required',
                'worktitle' => 'required|string|max:255',
                'caption' => 'required|string|max:255',
            ]
        );

        // ユーザ情報を取得
        $user = Auth::user();

        // データベースへの登録
        $work = Work::create([
            'work' => $request->work,
            'worktitle' => $request->worktitle,
            'caption' => $request->caption,
            'userID' => $user->id,
        ]);

        // 画像の保存（ファイルへの保存）
        $id = $work->id;
        $title = $work->worktitle;
        $filename = $work->id.'_'.$work->worktitle.'.jpg';
        $request->file('work')->storeAs('public/work', $filename);

        return redirect('/mypage');

    }

    public function updateIcon(Request $request)
    {
        // バリデーションの実施
        // TODO:worktitleで禁止文字（'_'）を与える
        $request->validate(
            [
                'icon' => 'required',
            ]
        );

        // ユーザ情報を取得
        // TODO:username、icon、icon_existを取得する。
        $user = Auth::user();
        $user->update(['icon_exist' => 1]);

        // 画像の保存（ファイルへの保存）
        $id = $user->id;
        $filename = $id.'.jpg';
        $request->file('icon')->storeAs('public/icon', $filename);

        return redirect('/mypage');

    }
    // workの表示
    public function work(Request $request, $id)
    {
        // IDで指定された作品をデータベースから取得する
        $work = Work::where('id', $id)->first();
        $data = ['work'=>$work];
        // 取得した作品データをブレード（作品用（これは自力で作る）の）に渡す（work.blade→mypage_work.bladeにする）
        $user = Auth::user();
        if($work->userID == $user->id)
            return view('mypage_work', $data);
        else
            return view('work',$data);
    }

    public function deletework(Request $request, $id)
    {
        $deleteRow = Work::where('id', $id)->delete();
        return redirect('/mypage');
    }

    public function create_changework(Request $request, $id)
    {
        $work = Work::where('id', $id)->first();
        $data = ['work'=>$work];
        // 取得した作品データをブレード（作品用（これは自力で作る）の）に渡す（work.blade→mypage_work.bladeにする）
        return view('changework', $data);
    }

    public function changework(Request $request, $id)
    {
        // バリデーションの実施
        // TODO:worktitleで禁止文字（'_'）を与える
//        $request->validate(
//            [
//                'work' => 'required',
//                'worktitle' => 'required|string|max:255',
//                'caption' => 'required|string|max:255',
//            ]
//        );
        var_dump($id);
        // IDで指定された作品をデータベースから取得する
        $work = Work::where('id', $id)->first();
        $id = $work->id;
        $title = $work->worktitle;
        $old_filename = $work->id.'_'.$work->worktitle.'.jpg';

        if($request->filled('worktitle')){
            $work->worktitle = $request->worktitle;
        }
        if($request->filled('caption')){
            $work->caption = $request->caption;
        }

        $id = $work->id;
        $title = $work->worktitle;
        $new_filename = $work->id.'_'.$work->worktitle.'.jpg';

        if($request->has('work')){
            $request->file('work')->storeAs('public/work', $new_filename);
        }else{
            $old_filename = 'work/'.$old_filename;
            $change_img = Storage::disk('public')->get($old_filename);
            $new_filename = 'public/work/'.$new_filename;
            Storage::put($new_filename, $change_img);
        }
        $work->save();

        // データベースを更新する

        // $work->update(['worktitle' => $request->worktitle]);
        // $work->update(['caption' => $request->caption]);

        // 画像の保存（ファイルへの保存）
        // 更新完了
        return redirect('/mypage');
    }

}
