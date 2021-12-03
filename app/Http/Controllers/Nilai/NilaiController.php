<?php


namespace App\Http\Controllers\Nilai;


use App\Helper\CustomController;
use App\Models\MapelIndicator;
use App\Models\Nilai;
use App\Models\PilihanMagang;
use App\Models\Rules;
use App\Models\RulesIndicator;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NilaiController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function nilai()
    {
        try {
            $siswa = User::with(['siswa', 'pilihanmagang' => function ($query) {
                $query->orderBy('urutan', 'ASC');
            }, 'pilihanmagang.dudi'])
                ->where('roles', 'siswa')
                ->get();

            $dudi = User::with('dudi')->where('roles', 'dudi')
                ->get([
                    'id',
                    'username',
                    DB::raw('(SELECT COUNT(tb_pilihan_magang.id) FROM tb_pilihan_magang WHERE tb_pilihan_magang.urutan = 1 AND tb_pilihan_magang.id_dudi = tb_user.id) as pilihan_1'),
                    DB::raw('(SELECT COUNT(tb_pilihan_magang.id) FROM tb_pilihan_magang WHERE tb_pilihan_magang.urutan = 2 AND tb_pilihan_magang.id_dudi = tb_user.id) as pilihan_2'),
                    DB::raw('(SELECT COUNT(tb_pilihan_magang.id) FROM tb_pilihan_magang WHERE tb_pilihan_magang.urutan = 3 AND tb_pilihan_magang.id_dudi = tb_user.id) as pilihan_3'),
                ]);
            $data = [
                'minat_siswa' => $siswa,
                'akumulasi_minat_dudi' => $dudi
            ];
            return $this->jsonResponse('success', 200, $data);
        } catch (\Exception $e) {
            return $this->jsonResponse($e->getMessage(), 500);
        }
    }

    public function rules()
    {
        try {
            $rules = Rules::with(['indicator', 'dudi'])
                ->get();
            $data = [
                'rules' => $rules
            ];
            return $this->jsonResponse('success', 200, $data);
        } catch (\Exception $e) {
            return $this->jsonResponse($e->getMessage(), 500);
        }
    }

    public function nilaiSiswa()
    {
        try {
            $siswa_id = 2;
            $nilai = Nilai::with(['user:id,username', 'user.siswa:id_user,nama', 'mapel:id,nama,alias'])
                ->where('id_user', $siswa_id)->get([
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
                ->where('id_user', 2)
                ->orderBy('urutan', 'ASC')
                ->get();

            $tmpInterests = [];
            foreach ($interest as $value) {
                $id = $value->dudi->dudi->id;
                $tmpInterest['id'] = $value->dudi->dudi->id;
                $tmpInterest['nama'] = $value->dudi->dudi->nama;

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
                foreach ($tmpAvailableSubjects as $valueSubject) {
                    $subject_id = $valueSubject->mapel->id;
                    $tmpAvailableSubject['id'] = $subject_id;
                    $tmpAvailableSubject['name'] = $valueSubject->mapel->nama;
                    $single_score = Nilai::where('id_user', $siswa_id)
                        ->where('id_mapel', $subject_id)
                        ->first();
                    $score = 0;
                    $score_indicator = 'rendah';
                    $score_index = [];
                    $score_formula = [];
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
                        if ($indicator === 'rendah' && $score < $tengah) {
                            $score_indicator = 'rendah';
                            array_push($score_index, 0);
                            array_push($score_formula, 0);
                            break;
                        } else if ($indicator === 'cukup' && ($score >= $bawah && $score <= $atas)) {
                            $score_indicator = 'cukup';
                            $middle_indicator = round(($atas - $score) / ($atas - $bawah), 1, PHP_ROUND_HALF_UP);
                            $top_indicator = round(($score - $bawah) / ($atas - $bawah), 1, PHP_ROUND_HALF_UP);
                            array_push($score_index, $top_indicator);
                            array_push($score_index, $middle_indicator);
                            array_push($score_formula, '(' . $score . ' - ' . $bawah . ') / (' . $atas . ' - ' . $bawah . ') = (' . ($score - $bawah) . ' / ' . ($atas - $bawah) . ') = ' . $top_indicator);
                            array_push($score_formula, '(' . $atas . ' - ' . $score . ') / (' . $atas . ' - ' . $bawah . ') = (' . ($atas - $score) . ' / ' . ($atas - $bawah) . ') = ' . $middle_indicator);
                            break;
                        } else if ($indicator === 'tinggi' && $score > $tengah) {
                            $score_indicator = 'tinggi';
                            array_push($score_index, 1);
                            array_push($score_formula, 1);
                            break;
                        }
                    }
                    $tmpAvailableSubject['score'] = $score;
                    $tmpAvailableSubject['score_indicator'] = $score_indicator;
                    $tmpAvailableSubject['score_index'] = $score_index;
                    $tmpAvailableSubject['score_formula'] = $score_formula;
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
                $tmpInterest['rule']['name'] = $rule->name;
                $tmpInterest['rule']['percentage'] = $rule->percentage;
                if (!$rule) {
                    return $this->jsonResponse('Rule Tidak Di Temukan Di ' . $value->dudi->dudi->nama, 500);
                }
                $indexRule = $rule->percentage;
//                $subjects_limit = [];
                $defuzzifikasi = [];
                //trapesium
                if($indexRule === 'tinggi') {
                    foreach ($availableSubjects as $avaSub) {
                        $subject_limit  = MapelIndicator::where('id_mapel', $avaSub['id'])
                            ->where('indikator', $indexRule)
                            ->first();
                        $bawah = $subject_limit->bawah;
                        $tengah = $subject_limit->tengah;
                        $atas = $subject_limit->atas;
                        $scoreRule = $avaSub['score_index'][0];

                        //segitga
                        $L1 = ($tengah - $bawah) * $scoreRule / 2;
                        $L2 = ($atas - $tengah) * $scoreRule;
                        $tmpLuas['luas'] = [$L1, $L2];
                        $tmpLuas['nilai'] = $avaSub['score'];
                        array_push($defuzzifikasi, $tmpLuas);
                    }
                }else if($indexRule === 'rendah') {
                    foreach ($availableSubjects as $avaSub) {
                        $subject_limit  = MapelIndicator::where('id_mapel', $avaSub['id'])
                            ->where('indikator', $indexRule)
                            ->first();
                        $bawah = $subject_limit->bawah;
                        $tengah = $subject_limit->tengah;
                        $atas = $subject_limit->atas;
                        $scoreRule = $avaSub['score_index'][0];

                        //segitga
                        $L1 = ($atas - $tengah) * $scoreRule / 2;
                        $L2 = ($tengah - $bawah) * $scoreRule;
                        $tmpLuas['luas'] = [$L1, $L2];
                        $tmpLuas['nilai'] = $avaSub['score'];
                        array_push($defuzzifikasi, $tmpLuas);
                    }
                }
                $tmpInterest['defuzzifikasi'] = $defuzzifikasi;
                $summary_score = 0;
                $summary_divider = 0;
                foreach ($defuzzifikasi as $defuzzi) {
                    $nilai = $defuzzi['nilai'];
                    foreach ($defuzzi['luas'] as $dl) {
                        $summary_score += $nilai * $dl;
                        $summary_divider += $dl;
                    }
                }
                $luasan = 0;
                if($summary_divider > 0) {
                    $luasan = $summary_score / $summary_divider;
                }
                $tmpInterest['Luasan'] = round($luasan, 2, PHP_ROUND_HALF_UP);
                array_push($tmpInterests, $tmpInterest);
            }
            return $this->jsonResponse('success', 200, [
//                'score' => $tmpScores,
                'interest' => $tmpInterests,
//                'rules' => $rules,
//                'available_mapel' => $tmpAvailableSubjects,
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse($e->getMessage(), 500);
        }
    }
}
