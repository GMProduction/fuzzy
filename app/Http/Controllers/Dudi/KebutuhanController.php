<?php

namespace App\Http\Controllers\Dudi;

use App\Http\Controllers\Controller;
use App\Models\KebutuhanNilai;
use App\Models\Mapel;
use App\Models\Rules;
use App\Models\RulesIndicator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KebutuhanController extends Controller
{
    //
    public function index()
    {
        if (\request()->isMethod('POST')) {
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

    public function store()
    {
        $mapel = \request('mapel');
        $nilai = \request('nilai');
        foreach ($mapel as $key => $m) {
            $kebutuhan = KebutuhanNilai::where([['id_dudi', '=', Auth::id()], ['id_mapel', '=', $m]])->first();
            if ($kebutuhan) {
                $kebutuhan->update(['nilai' => str_replace(',', '.', $nilai[$key])]);
            } else {
                KebutuhanNilai::create(
                    [
                        'id_dudi' => Auth::id(),
                        'id_mapel' => $m,
                        'nilai' => str_replace(',', '.', $nilai[$key]),
                    ]
                );
            }
        }

        return 'berhasil';
    }

    public function rule()
    {
        $request = \request()->all();

        $persentase = \request()->request->get('persentase');
        $user = User::with('dudi')->where('id', '=', Auth::id())
            ->first();
        $ruleName = $user->dudi->nama;
        foreach ($persentase as $key => $p) {
            $rules = new Rules();
            $rules->dudi_id = Auth::id();
            $rules->name = $ruleName . '-' . ($key + 1);
            $rules->percentage = $p;
            $rules->save();

            $reqMapel = \request()->request->get('rule-' . $key);
            $reqNilai = \request()->request->get('nilai-' . $key);
            $mapelCount = count($reqMapel);
            for ($i = 0; $i < $mapelCount; $i++) {
                $rulesIndicator = new RulesIndicator();
                $rulesIndicator->rule_id = $rules->id;
                $rulesIndicator->mapel_id = $reqMapel[$i];
                $rulesIndicator->value = $reqNilai[$i];
                $rulesIndicator->save();
            }
        }
        dump($user->toArray());
        dump($request);
        die();
    }
}
