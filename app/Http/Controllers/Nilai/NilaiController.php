<?php


namespace App\Http\Controllers\Nilai;


use App\Helper\CustomController;
use App\Models\Nilai;
use App\Models\PilihanMagang;
use App\Models\Rules;
use App\Models\User;
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
            $nilai = Nilai::with(['user:id,username', 'user.siswa:id_user,nama', 'mapel:id,nama,alias'])
                ->where('id_user', 2)->get([
                    'nilai', 'id_mapel',  'id_user'
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
                $tmpInterest['id'] = $value->dudi->dudi->id;
                $tmpInterest['nama'] = $value->dudi->dudi->nama;
                array_push($tmpInterests, $tmpInterest);
            }
            return $this->jsonResponse('success', 200, [
                'score' => $tmpScores,
                'interest' => $tmpInterests
            ]);
        }catch (\Exception $e) {
            return $this->jsonResponse($e->getMessage(), 500);
        }
    }
}
