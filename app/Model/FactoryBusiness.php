<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FactoryBusiness extends Model
{
    protected $connection= 'FactoryDashBoard';

    protected $table = 'Business';

    protected $primaryKey = 'KPICode';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
}
