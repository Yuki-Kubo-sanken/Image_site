<?php
//
//declare(strict_types=1);
//
//namespace App\Http\Controllers;
//
//use App\Models\Work;
//use Illuminate\Auth\Events\Registered;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Auth;
//
//class SubmitController extends Controller
//{
//    public function create()
//    {
//        return view('submit');
//    }
//
//    public function store(Request $request)
//    {
//        // バリデーションの実施
//        // TODO:worktitleで禁止文字（'_'）を与える
//        $request->validate(
//            [
//                'work' => 'required',
//                'worktitle' => 'required|string|max:255',
//                'caption' => 'required|string|max:255',
//            ]
//        );
//
//        // ユーザ情報を取得
//        // TODO:username、icon、icon_existを取得する。
//        $user = Auth::user();
//
//        // データベースへの登録
//        $work = Work::create([
//            'work' => $request->work,
//            'worktitle' => $request->worktitle,
//            'caption' => $request->caption,
//            'userID' => $user->id,
//        ]);
//
//        // 画像の保存（ファイルへの保存）
//        $id = $work->id;
//        $title = $work->worktitle;
//        $filename = $work->id.'_'.$work->worktitle.'.jpg';
//        $request->file('work')->storeAs('public/work', $filename);
//
//        return redirect('/home');
//
//    }
//
//
//
//}
