<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TBulan extends Model
{
    public $table = 'T_BULAN_TBL';

    protected $primaryKey = 'ID_TRANSBULAN';

    public $timestamps = false;

    protected $fillable = [
        'ID_TRANSBULAN',
        'ID_TA',
        'ID_SISWA',
        'TGL_BYR',
        'PETUGAS',
        'BULAN',
        'SPP',
        'NOTES',
        'CLOSED',
    ];

    protected $casts = [
        'TGL_BYR' => 'date',
        'CLOSED' => 'boolean',
    ];

    public function siswa()
    {
        return $this->belongsTo(MSiswa::class, 'ID_SISWA', 'ID_SISWA');
    }
}
