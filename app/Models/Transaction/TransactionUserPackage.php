<?php

namespace App\Models\Transaction;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class TransactionUserPackage extends Model
{
    use HasUuids, SoftDeletes;
    protected $table = 'transactions_users_packages';
    protected $guarded = [];

    public function newUniqueId()
    {
        return Uuid::uuid4();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
