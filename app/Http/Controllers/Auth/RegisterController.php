<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * ユーザ登録画面の表示
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getRegister()
    {
      if (Auth::check()){
        $user = Auth::user();
        $staff_list = DB::table('users')->get();
        return view('auth.register', ['staff_list' => $staff_list,'users' => $user]);
      }else{
        return view('auth/login');
      }
    }

    /**
     * ユーザ登録機能
     * @param array $data
     * @return unknown
     */
    public function postRegister(Request $data)
    {
        if ($data['authority_name'] == "nomal"){
          $authority = 0;
        }else if($data['authority_name'] == "administrator"){
          $authority = 10;
        }
        // ユーザ登録処理
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'authority' => $authority,
        ]);

        // ホーム画面へリダイレクト
        return redirect('/register');
    }
}
