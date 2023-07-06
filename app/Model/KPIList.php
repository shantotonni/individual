<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KPIList extends Model
{
    protected $connection= 'FactoryDashBoard';

    protected $table = 'FactoryKPI';

    protected $primaryKey = 'KPICode';

    protected $keyType = 'string';

    protected $guarded = [];
    public $timestamps = false;
}
