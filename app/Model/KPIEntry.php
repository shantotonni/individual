<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class KPIEntry extends Model
{
    protected $connection= 'FactoryDashBoard';

    protected $table = 'KPIEntryMaster';

    protected $primaryKey = 'KPIEntryMasterCode';

    protected $guarded = [];

    public $timestamps = false;

    public function kpi_details(){
        return $this->hasOne(KPIEntryDetails::class,'KPIEntryMasterCode','KPIEntryMasterCode');
    }
    public function user(){
        return $this->hasOne(User::class,'EmpCode','EntryBy');
    }
}
