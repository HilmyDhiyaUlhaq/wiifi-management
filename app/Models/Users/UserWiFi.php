<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class UserWiFi extends Model
{
    use HasUuids, SoftDeletes;
    protected $table = 'users_wifis';
    protected $guarded = [];

    public function newUniqueId()
    {
        return Uuid::uuid4();
    }

}
