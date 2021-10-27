<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(){
        $user = User::with('siswa')->find(Auth::id());
        return view('siswa.dudi')->with(['data' => $user]);
    }

    public function updateProfile(){
//        $field = \request()->validate([
//            'username'
//        ])
    }
}
