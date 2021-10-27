<?php

namespace App\Http\Controllers\Dudi;

use App\Http\Controllers\Controller;
use App\Models\KebutuhanNilai;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KebutuhanController extends Controller
{
    //
    public function index(){
        if (\request()->isMethod('POST')){
            return $this->store();
        }
        $user = User::with('dudi')->find(Auth::id());
        $mapel = Mapel::with('kebutuhan')->get();
        $data = [
          'data' => $user,
          'mapel' => $mapel
        ];
        return view('dudi.kebutuhannilai')->with($data);
    }

    public function store(){
        $mapel = \request('mapel');
        $nilai = \request('nilai');
        foreach ($mapel as $key => $m){
            $kebutuhan = KebutuhanNilai::where([['id_dudi','=',Auth::id()],['id_mapel','=',$m]])->first();
            if ($kebutuhan){
                $kebutuhan->update(['nilai' => str_replace(',','.',$nilai[$key])]);
            }else{
                KebutuhanNilai::create(
                    [
                        'id_dudi'  => Auth::id(),
                        'id_mapel' => $m,
                        'nilai'    => str_replace(',','.',$nilai[$key]),
                    ]
                );
            }
        }

        return 'berhasil';
    }
}
