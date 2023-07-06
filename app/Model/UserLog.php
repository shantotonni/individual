<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $table = 'UserLog';

    protected $primaryKey = 'LogId';

    protected $guarded = [];

    public $timestamps = false;
}
