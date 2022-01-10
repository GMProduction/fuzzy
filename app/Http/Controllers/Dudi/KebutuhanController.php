<?php

namespace App\Http\Controllers\Dudi;

use App\Http\Controllers\Controller;
use App\Models\KebutuhanNilai;
use App\Models\Mapel;
use App\Models\MapelIndicator;
use App\Models\Nilai;
use App\Models\PilihanMagang;
use App\Models\Rules;
use App\Models\RulesIndicator;
use App\Models\Siswa;
use App\Models\TempatDudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KebutuhanController extends Controller
{
    //
    public function index()
    {
        if (\request()->isMethod('POST')) {
            return $this->store();
        }
        $dudi = TempatDudi::where('id_user', Auth::id())->first();
        $user = User::with('dudi')->find(Auth::id());
        $mapel = Mapel::with('kebutuhan')->get();
        $rules = Rules::with(['indicator.mapel'])->where('dudi_id', $dudi->id)->get();
//        return $rules->toArray();
        $data = [
            'data' => $user,
            'mapel' => $mapel,
            'rules' => $rules
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

        $existRules = Rules::with(['indicator'])->where('dudi_id', $user->dudi->id)
            ->get();
        foreach ($existRules as $v) {
            $v->delete();
        }
        foreach ($persentase as $key => $p) {
            $rules = new Rules();
            $rules->dudi_id = $user->dudi->id;
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
        return redirect()->back();
//        dump($user->toArray());
//        dump($request);
//        die();
    }

    public function pembagianDudi()
    {
        $siswa = Siswa::all();
        $result = [];
        foreach ($siswa as $s) {
            $nilai = Nilai::with(['user:id,username', 'user.siswa:id_user,nama', 'mapel:id,nama,alias'])
                ->where('id_user', $s->id_user)->get([
                    'nilai', 'id_mapel', 'id_user'
                ]);


            $tmpScores = [];
            foreach ($nilai as $value) {
                $tmpScore['nama'] = $value->user->siswa->nama;
                $tmpScore['mapel'] = $value->mapel->nama;
                $tmpScore['nilai'] = $value->nilai;
                array_push($tmpScores, $tmpScore);
            }

            $interest = PilihanMagang::with(['user', 'dudi.dudi'])
                ->where('id_user', $s->id_user)
                ->orderBy('urutan', 'ASC')
                ->get();
//            return [$nilai->toArray(), $interest->toArray()];
            $tmpInterests = [];
            foreach ($interest as $value) {
                $id = $value->dudi->dudi->id;
                $tmpInterest['id'] = $value->dudi->dudi->id;
                $tmpInterest['nama'] = $value->dudi->dudi->nama;
                $tmpInterest['urutan'] = $value->urutan;
                $tmpAvailableSubjects = RulesIndicator::with(['mapel:id,nama', 'rules:id,dudi_id'])
                    ->whereHas('rules', function ($q) use ($id) {
                        $q->where('dudi_id', '=', $id);
                    })
                    ->groupBy('mapel_id')
                    ->get([
                        'id', 'rule_id', 'mapel_id'
                    ]);

                $availableSubjects = [];
                //getting available subjects in interest
                $area_sum = 0;
                $area_div = 0;
                foreach ($tmpAvailableSubjects as $valueSubject) {
                    $subject_id = $valueSubject->mapel->id;
                    $tmpAvailableSubject['id'] = $subject_id;
                    $tmpAvailableSubject['name'] = $valueSubject->mapel->nama;
                    $single_score = Nilai::where('id_user', $s->id_user)
                        ->where('id_mapel', $subject_id)
                        ->first();
                    $score = 0;
                    $score_indicator = 'rendah';
//                    $score_index = [];
                    $score_index = 0;
                    $score_formula = [];
                    $defuzz = [];

                    if ($single_score) {
                        $score = $single_score->nilai;
                    }
                    $indicators = ['rendah', 'cukup', 'tinggi'];
                    foreach ($indicators as $indicator) {
                        $subject_indicator = MapelIndicator::with('mapel')
                            ->where('indikator', $indicator)
                            ->where('id_mapel', $subject_id)
                            ->first();
                        if (!$subject_indicator) {
                            return $this->jsonResponse('Indikator Batas Mapel Belum Di Tentukan', 202);
                        }
                        $bawah = $subject_indicator->bawah;
                        $tengah = $subject_indicator->tengah;
                        $atas = $subject_indicator->atas;
                        if ($indicator === 'rendah' && $score <= $tengah) {
                            $score_indicator = 'rendah';
                            $score_index = 0;
                            $L1 = ($atas - $tengah) * $score_index / 2;
                            $L2 = ($tengah - $bawah) * $score_index;
                            $area_sum += (($score * $L1) + ($score * $L2));
                            $area_div += $L1 + $L2;
                            $defuzz['luas'] = [$L1, $L2];
                            $defuzz['index'] = 'bottom';
                            break;
                        } else if ($indicator === 'cukup' && ($score > $bawah && $score <= $atas)) {
                            $score_indicator = 'cukup';
                            if ($score > $bawah && $score < $tengah) {
                                $middle_indicator = round(($atas - $score) / ($atas - $bawah), 1, PHP_ROUND_HALF_UP);
                                $score_index = $middle_indicator;
                                $L1 = ($atas - $tengah) * $score_index / 2;
                                $L2 = ($tengah - $bawah) * $score_index;
                                $defuzz['luas'] = [$L1, $L2];
                                $defuzz['index'] = 'bottom';
                                $area_sum += (($score * $L1) + ($score * $L2));
                                $area_div += $L1 + $L2;
                            } else if ($score > $tengah && $score < $atas) {
                                $top_indicator = round(($score - $bawah) / ($atas - $bawah), 1, PHP_ROUND_HALF_UP);
                                $score_index = $top_indicator;
                                $L1 = ($tengah - $bawah) * $score_index / 2;
                                $L2 = ($atas - $tengah) * $score_index;
                                $defuzz['luas'] = [$L1, $L2];
                                $defuzz['index'] = 'top';
                                $area_sum += (($score * $L1) + ($score + $L2));
                                $area_div += $L1 + $L2;
                            } else {
                                $score_index = 0.5;
                                $L1 = ($tengah - $bawah) * $score_index / 2;
                                $L2 = ($atas - $tengah) * $score_index;
                                $defuzz['luas'] = [$L1, $L2];
                                $defuzz['index'] = 'top';
                                $area_sum += (($score * $L1) + ($score * $L2));
                                $area_div += $L1 + $L2;
                            }
                            break;
                        } else if ($indicator === 'tinggi' && $score > $tengah) {
                            $score_indicator = 'tinggi';
                            $score_index = 1;
                            $L1 = ($tengah - $bawah) * 1 / 2;
                            $L2 = ($atas - $tengah) * 1;
                            $defuzz['luas'] = [$L1, $L2];
                            $defuzz['index'] = 'top';
                            $area_sum += (($score * $L1) + ($score * $L2));
                            $area_div += $L1 + $L2;
                            break;
                        }
                    }
                    $tmpAvailableSubject['score'] = $score;
                    $tmpAvailableSubject['score_indicator'] = $score_indicator;
                    $tmpAvailableSubject['score_index'] = $score_index;
                    $tmpAvailableSubject['defuzzifikazi'] = $defuzz;

                    array_push($availableSubjects, $tmpAvailableSubject);
                }

                $tmpInterest['subjects'] = $availableSubjects;
                $tmpQueries = [];
                foreach ($availableSubjects as $avaSub) {
                    $tmpQuery['subject'] = $avaSub['id'];
                    $tmpQuery['indicator'] = $avaSub['score_indicator'];
                    array_push($tmpQueries, $tmpQuery);
                }
                $countSubject = count($availableSubjects) - 1;

                //get rule
                $rule = DB::table('rules_indicator')
                    ->select('rules_indicator.id',
                        'rules_indicator.rule_id',
                        'rules_indicator.mapel_id',
                        'rules.dudi_id',
                        'rules.name',
                        'rules.id',
                        'rules.percentage',
                        DB::raw('COUNT(rules_indicator.rule_id) as v_r'))
                    ->join('rules', function ($join) use ($id) {
                        $join->on('rules_indicator.rule_id', '=', 'rules.id')
                            ->where('rules.dudi_id', $id);
                    })
                    ->where(function ($q) use ($tmpQueries) {
                        foreach ($tmpQueries as $qr) {
                            $q->orWhere(function ($qs) use ($qr) {
                                $qs->where('rules_indicator.mapel_id', '=', $qr['subject'])
                                    ->where('rules_indicator.value', '=', $qr['indicator']);
                            });
                        }
                    })
                    ->groupBy('rules_indicator.rule_id')
                    ->having('v_r', '>', $countSubject)
                    ->first();
                if (!$rule) {
                    return $this->jsonResponse('Rule Tidak Di Temukan Di ' . $value->dudi->dudi->nama, 500);
                }

                $tmpInterest['rule']['name'] = $rule->name;
                $tmpInterest['rule']['percentage'] = $rule->percentage;

                $tmpInterest['defuzzifikasi']['summary'] = $area_sum;
                $tmpInterest['defuzzifikasi']['divider'] = $area_div;
                $tmpInterest['defuzzifikasi']['total'] = $area_div === 0 ? 0 : round(($area_sum / $area_div), 2, PHP_ROUND_HALF_UP);
                array_push($tmpInterests, $tmpInterest);
            }

            usort($tmpInterests, function ($a, $b) {
                return $a['defuzzifikasi']['total'] < $b['defuzzifikasi']['total'];
            });
            usort($tmpInterests, function ($a, $b) {
                return $a['urutan'] > $b['urutan'];
            });
            $sorted = [];
            foreach ($tmpInterests as $tmp) {
                array_push($sorted, $tmp);
            }

            $tmpResult['id'] = $s->id;
            $tmpResult['nama'] = $s->nama;
            $tmpResult['nim'] = $s->nim;
            $tmpResult['user'] = $s->id_user;
            $tmpResult['pilihan'] = $sorted;
            array_push($result, $tmpResult);
        }

        $dudi = TempatDudi::where('id_user', Auth::id())->first();
        $newArray = array_filter($result, function ($obj) use ($dudi) {
            if (count($obj['pilihan']) > 0) {
                if ($obj['pilihan'][0]['id'] === $dudi->id) {
                    return true;
                }
            }
            return false;
        });
        $newResult = [];
        foreach ($newArray as $new) {
            array_push($newResult, $new);
        }
        return view('dudi.hasilperhitungan')->with(['result' => $newResult]);
//        dump($newResult);
//        die();
    }
}
