<?php

namespace App\Http\Controllers;

use App\Helper\CustomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends CustomController
{
    //

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login()
    {
       if (\request()->isMethod('POST')){
           $credentials = [
               'username' => \request('username'),
               'password' => \request('password'),
           ];
           if ($this->isAuth($credentials)) {
               $redirect = '/';
               if (Auth::user()->roles === 'admin') {
                   return redirect('/admin');
               }elseif (Auth::user()->roles === 'siswa'){
                   return redirect('/siswa');
               }else{
                   return redirect('/dudi');
               }
           }
           return Redirect::back()->withErrors(['failed', 'Periksa Kembali Username dan Password Anda']);
       }
       return view('login');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
