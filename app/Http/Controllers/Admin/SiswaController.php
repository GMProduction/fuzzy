<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    //
    public function index()
    {

        if (\request()->isMethod('POST')) {
            return $this->store();
        }

        $user = User::with('siswa')->where('roles','=','siswa')->paginate(10);
        return view('admin.siswa')->with(['data' => $user]);
    }

    public function store()
    {
        $field = \request()->validate(
            [
                'username' => 'required',
                'nama'     => 'required',
                'alamat'   => 'required',
                'hp'       => 'required',
            ]
        );
        if (\request('id')) {
            $nim = User::where([['id', '!=', \request('id')], ['username', '=', $field['username']]])->first();
            if ($nim){
                return response()->json(
                    [
                        "msg" => "The username has already been taken.",
                    ],
                    202
                );
            }
//            Arr::set($field, 'password', Hash::make($field['username']));

            Arr::set($field, 'nim', $field['username']);
            $user = User::find(\request('id'));
            $user->update([
                'username' => $field['username']
            ]);
            Arr::forget($field,'username');
            $user->siswa()->update($field);
        } else {
            Arr::set($field, 'nim', $field['username']);
            Arr::set($field, 'roles', 'siswa');
            Arr::set($field, 'password', Hash::make($field['username']));
            $user = User::create($field);
            $user->siswa()->create($field);
        }

        return response()->json(
            [
                "msg" => "Berhasil.",
            ],
            200
        );
    }
}
