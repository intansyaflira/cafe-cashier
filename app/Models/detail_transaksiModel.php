<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detail_transaksiModel extends Model
{
    use HasFactory;
    protected $table = 'detail_transaksi_tabel';
    protected $primaryKey = 'id_detail_transaksi';
    public $timestamps = true;
    public $fillable = [
        'id_transaksi',
        'id_menu',
        'qty'
    ];

    public function menu() {
        return $this->belongsTo('App\Models\menuModel','id_menu','id_menu');
    }
}
