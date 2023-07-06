<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KPIEntryDetails extends Model
{
    protected $connection= 'FactoryDashBoard';

    protected $table = 'KPIEntryDetails';

    protected $primaryKey = 'KPIEntryMasterCode';

    protected $guarded = [];

    public $timestamps = false;

    public function kpi(){
        return $this->hasOne(KPIList::class,'KPICode','KPICode');
    }
}
