<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTahunAjaran extends Model
{
    public $table = 'M_TA_TBL';

    protected $primaryKey = 'ID_TA';

    public $timestamps = false;

    protected $fillable = [];
}
