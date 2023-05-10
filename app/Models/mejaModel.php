<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mejaModel extends Model
{
    use HasFactory;
    protected $table = 'meja';
    protected $primaryKey = 'id_meja';
    public $timestamps = true;
    public $fillable = [
        'nomor_meja', 'status'
    ];
}
