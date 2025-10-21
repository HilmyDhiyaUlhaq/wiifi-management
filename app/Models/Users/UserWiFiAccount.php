<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class UserWiFiAccount extends Model
{
    use HasUuids, SoftDeletes;
    protected $table = 'users_wifis_accounts';
    protected $guarded = [];

    public function newUniqueId()
    {
        return Uuid::uuid4();
    }

    public function userWifi()
    {
        return $this->belongsTo(UserWiFi::class, 'user_wifi_id', 'id');
    }
}
