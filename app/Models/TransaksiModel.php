<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    use HasFactory;
    protected $table = 'tb_transaksi';
    protected $fillable = [
        'id' , 'id_product' , 'nama_pembeli' , 'qty' , 'created_at' , 'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'id_product');
    }

    public function getProduct($id_product)
    {
        $data = $this->join('tb_product' , 'tb_transaksi.id_product' , '=' , 'tb_product.id')
        ->select('tb_product.kode_product' , 'tb_product.nama_product' , 'tb_product.harga_product')
        ->where('tb_transaksi.id_product' , '=' , $id_product)
        ->first();
        return $data;
    }
}
