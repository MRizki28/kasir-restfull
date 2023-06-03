<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    public function getAllData()
    {
        $data = ProductModel::all();
        if ($data->isEmpty()) {
            return response()->json([
                'product not found'
            ]);
        } else {
            return response()->json([
                'code' =>  200,
                'message' => 'success',
                'data' => $data
            ]);
        }
    }


    public function createData(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'kode_product' => 'required',
            'nama_product' => 'required',
            'harga_product' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'check your validation',
                'errors' => $validation->errors()
            ]);
        }

        try {
            $data = new ProductModel;
            $data->kode_product = $request->input('kode_product');
            $data->nama_product = $request->input('nama_product');
            $data->harga_product = $request->input('harga_product');
            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'sfailed',
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ]);
    }
}
