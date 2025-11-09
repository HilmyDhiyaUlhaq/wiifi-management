<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Package extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'packages';
    protected $guarded = [];

    public function newUniqueId()
    {
        return Uuid::uuid4();
    }

    const KIND_GOLD = 'GOLD';
    const KIND_SILVER = 'SILVER';
    const KIND_BRONZE = 'BRONZE';
    const AVAILABLE_KINDS = [self::KIND_GOLD, self::KIND_SILVER, self::KIND_BRONZE];
}
