<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempatDudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class DudiController extends Controller
{
    //
    public function index()
    {
        if (\request()->isMethod('POST')) {
            return $this->store();
        }

        $dudi = User::with('dudi')->where('roles', '=', 'dudi')->paginate(10);

        return view('admin.dudi')->with(['data' => $dudi]);
    }

    public function store()
    {
        $field = \request()->validate(
            [
                'username' => 'required',
                'alamat'   => 'required',
                'nama'     => 'required',
            ]
        );
        if (\request('id')) {
            $nim = User::where([['id', '!=', \request('id')], ['username', '=', $field['username']]])->first();
            if ($nim) {
                return response()->json(
                    [
                        "msg" => "The email has already been taken.",
                    ],
                    202
                );
            }
            if (\request('password') && strpos(\request('password'), '*') === false) {
                Arr::set($field, 'password', Hash::make(\request('password')));
            }
            $user = User::find(\request('id'));
            $user->update($field);
            Arr::forget($field, 'username');
            if (\request('password')){
                Arr::forget($field, 'password');
            }
            $user->dudi()->update($field);
        } else {
            Arr::set($field, 'password', Hash::make(\request('password')));
            Arr::set($field, 'roles', 'dudi');
            $user = User::create($field);
            $user->dudi()->create($field);
        }

        return 'berhasil';

    }
}
