<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
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
        if (\request()->isMethod('POST')){
            return $this->store();
        }
        $mapel = Mapel::paginate(10);

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

    public function getAll(){
        $mapel = Mapel::all();
        return $mapel;
    }
}
