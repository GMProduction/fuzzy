<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\MapelIndicator;
use Illuminate\Http\Request;

/**
 * Class MapelController
 * @package App\Http\Controllers\Admin
 */
class MapelController extends Controller
{
    //

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (\request()->isMethod('POST')) {
            return $this->store();
        }
        $mapel = Mapel::with(['indicator'])->paginate(10);
//        return $mapel->toarray();
        return view('admin.mapel')->with(['data' => $mapel]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        if (\request('id')) {
            $mapel = Mapel::find(\request('id'));
            $mapel->update(\request()->all());
        } else {
            $mapel = Mapel::create(\request()->all());
        }

        return response()->json(['msg' => 'berhasil'], 200);
    }

    public function getAll()
    {
        $mapel = Mapel::all();
        return $mapel;
    }

    public function getIndicator($id)
    {
        try {
            $indicator = MapelIndicator::with('mapel')->where('id_mapel', $id)->get();
            return response()->json(['msg' => 'success', 'data' => $indicator], 200);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'gagal ' . $e->getMessage()], 500);
        }
    }

    public function storeIndicator()
    {
        try {
            $id = \request()->request->get('id');
            $indicator = ['rendah', 'cukup', 'tinggi'];
            foreach ($indicator as $value) {
                $mapel_indicator = new MapelIndicator();
                $mapel_indicator->id_mapel = $id;
                $mapel_indicator->indikator = $value;
                $mapel_indicator->bawah = \request()->request->get($value.'_bawah');
                $mapel_indicator->tengah = \request()->request->get($value.'_tengah');
                $mapel_indicator->atas = \request()->request->get($value.'_atas');
                $mapel_indicator->save();
            }
        }catch (\Exception $e) {
            return response()->json(['msg' => 'gagal ' . $e->getMessage()], 500);
        }
    }
}
