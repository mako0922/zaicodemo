<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; //追加

class UserController extends Controller
{
    //下記を追加
/*
    //userデータの取得
    public function index() {
        return view('user.index', ['user' => Auth::user() ]);
    }
*/
    //userデータの編集
    public function edit() {
        return view('user_update.index', ['users' => Auth::user(),'user_update' => Auth::user() ]);
    }
    //userデータの保存
    public function update(Request $request) {

        $user_form = $request->all();
        $user = Auth::user();
        //不要な「_token」の削除
        unset($user_form['_token']);
        //保存
        $user->fill($user_form)->save();
        //リダイレクト
        return redirect('zaico_home');
    }
}
