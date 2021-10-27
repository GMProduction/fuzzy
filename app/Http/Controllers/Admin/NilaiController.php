<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NilaiController extends Controller
{
    //
    public function index()
    {

        if (\request()->isMethod('POST')) {
            return $this->store();
        }

        $siswa = User::with(['siswa'])->where('roles', '=', 'siswa')->paginate(10);
        $data  = [];
        foreach ($siswa as $key => $d) {
            $data[$key] = $d;
            $nilai      = Nilai::where('id_user', '=', $d->id)->avg('nilai');
            Arr::set($data[$key], 'avg', $nilai);
        }

        return view('admin.nilai')->with(['data' => $siswa]);
    }

    public function store()
    {
        $user  = \request('user');
        $mapel = \request('mapel');
        $nilai = \request('nilai');
        foreach ($user as $key => $u) {
            $nilaiData = Nilai::where([['id_user', '=', $u], ['id_mapel', '=', $mapel[$key]]])->first();
            if ($nilaiData) {
                $nilaiData->update(['nilai' => str_replace(',','.',$nilai[$key])]);
            } else {
                Nilai::create(
                    [
                        'id_user'  => $user[$key],
                        'id_mapel' => $mapel[$key],
                        'nilai'    => str_replace(',','.',$nilai[$key]),
                    ]
                );
            }
        }

        return 'berhasil';

    }

    /**
     * @return mixed
     */
    public function BySiswa()
    {
        $nilai = Nilai::where([['id_user', '=', \request('user')], ['id_mapel', '=', \request('mapel')]])->get();

        return $nilai;
    }

}
