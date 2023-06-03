<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TransaksiModel;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function getAllData()
    {
        $data = TransaksiModel::with('product')->first();
        return response()->json([
            'code' => 200 ,
            'message' => 'success',
            'data' => $data
        ]);
    }
}
