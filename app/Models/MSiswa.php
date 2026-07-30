<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MSiswa extends Model
{
    public $table = 'M_SISWA_TBL';

    protected $primaryKey = 'ID_SISWA';

    public $timestamps = false;

    protected $fillable = [];
 
    public function bulans()
    {
        return $this->hasMany(TBulan::class, 'ID_SISWA', 'ID_SISWA');
    }

    public function history() {
        return $this->hasMany(TSiswa::class, 'ID_SISWA', 'ID_SISWA');
    }

    public function tahunAjaran() {
        return $this->belongsTo(MTahunAjaran::class, 'ID_TA', 'ID_TA');
    }
}
