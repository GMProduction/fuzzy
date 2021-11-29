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
                foreach ($tmpAvailableSubjects as $valueSubject) {
                    $subject_id = $valueSubject->mapel->id;
                    $tmpAvailableSubject['id'] = $subject_id;
                    $tmpAvailableSubject['name'] = $valueSubject->mapel->nama;
                    $single_score = Nilai::where('id_user', $siswa_id)
                        ->where('id_mapel', $subject_id)
                        ->first();
                    $score = 0;
                    $score_index = 'rendah';
                    if ($single_score) {
                        $score = $single_score->nilai;
                    }
                    $indicators = ['rendah', 'cukup', 'tinggi'];
                    foreach ($indicators as $indicator) {
                        $subject_indicator = MapelIndicator::with('mapel')
                            ->where('indikator', $indicator)
                            ->where('id_mapel', $subject_id)
                            ->first();
                        if(!$subject_indicator) {
                            return $this->jsonResponse('Indikator Batas Mapel Belum Di Tentukan', 202);
                        }
                        $bawah = $subject_indicator->bawah;
                        $tengah = $subject_indicator->tengah;
                        $atas = $subject_indicator->atas;
                        if($indicator === 'rendah' && $score < $tengah) {
                            $score_index = 'rendah';
                            break;
                        } else if($indicator === 'cukup' && ($score >= $bawah && $score <= $atas) ) {
                            $score_index = 'cukup';
                            break;
                        }else if($indicator === 'tinggi' && $score > $tengah) {
                            $score_index = 'tinggi';
                            break;
                        }
                    }
                    $tmpAvailableSubject['score'] = $score;
                    $tmpAvailableSubject['score_index'] = $score_index;
                    array_push($availableSubjects, $tmpAvailableSubject);
                }
                $tmpInterest['subjects'] = $availableSubjects;

                array_push($tmpInterests, $tmpInterest);
            }

//            $availableMapel = RulesIndicator::with(['mapel:id,nama', 'rules:id,dudi_id'])
//                ->whereHas('rules', function ($q) {
//                    $q->where('dudi_id', '=', 8);
//                })
//                ->groupBy('mapel_id')
//                ->get([
//                    'id', 'rule_id', 'mapel_id'
//                ]);
//            $tmpAvailableSubjects = [];
//            foreach ($availableMapel as $value) {
//                $tmpAvailableSubject['id'] = $value->mapel->id;
//                $tmpAvailableSubject['name'] = $value->mapel->nama;
//                array_push($tmpAvailableSubjects, $tmpAvailableSubject);
//            }
            $rules = Rules::with(['indicator:rule_id,mapel_id', 'indicator.mapel:id,nama,alias', 'dudi' => function ($query) {
                $query->select('id', 'nama');
            }])
                ->where('dudi_id', 8)
                ->get([
                    'id',
                    'dudi_id',
                    'name',
                    'percentage'
                ]);
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
