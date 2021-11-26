<?php


namespace App\Http\Controllers\Nilai;


use App\Helper\CustomController;
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
            $siswa = User::with(['siswa', 'pilihanmagang' => function ($query){
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
        }catch (\Exception $e) {
            return $this->jsonResponse($e->getMessage(), 500);
        }
    }
}
