<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TSiswa extends Model
{
    public $table = 'T_SISWA_TBL';

    protected $primaryKey = 'ID_SISWAKELAS';

    protected $fillable = [];
 
    public function siswa()
    {
        return $this->belongsTo(MSiswa::class, 'ID_SISWA', 'ID_SISWA');
    }

    public function tahunAjaran() {
        return $this->belongsTo(MTahunAjaran::class, 'ID_TA', 'ID_TA');
    }
}
