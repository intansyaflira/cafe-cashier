<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksiModel extends Model
{
    use HasFactory;
    protected $table = 'transaksi_tabel';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = true;
    public $fillable = [
        'tgl_transaksi',
        'id_user',
        'id_meja',
        'nama_pelanggan',
        'status'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User','id_user','id_user');
    }

    public function meja() {
        return $this->belongsTo('App\Models\mejaModel','id_meja','id_meja');
    }

}
