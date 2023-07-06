<?php

namespace App;

use App\Model\Employee;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection= 'PIMS';

    protected $table = 'UserManagerOnlineApp';

    protected $primaryKey = 'EmpCode';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $guarded = [];

    public function employee(){
        return $this->hasOne(Employee::class,'EmpCode','EmpCode');
    }

}
