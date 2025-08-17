<?php
namespace App\Repositories\Transactions;

use App\Models\Transaction\TransactionUserPackage;

class TransactionUserPackageRepository
{

    public function getAllTransactionUserPackageByParams($params)
    {
        return TransactionUserPackage::
            where(function ($query) use ($params) {
                $query->when(isset($params['userId']), function ($query) use ($params) {
                    $query->where('user_id', $params['userId']);
                });
            })->orderBy('created_at', 'desc')
            ->where(function ($query) use ($params) {
                $query->when(isset($params['startDate']) && isset($params['endDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['startDate'], $params['endDate']]);
                });
            })
            ->paginate($params['perPage'] ?? 10)
            ->appends([
                'search' => $params['search'] ?? null,
                'perPage' => $params['perPage'] ?? 10
            ]);
    }
    public function getCountTransactionUserPackageByParams($params)
    {
        return TransactionUserPackage::
            where(function ($query) use ($params) {
                $query->when(isset($params['userId']), function ($query) use ($params) {
                    $query->where('user_id', $params['userId']);
                });
            })->orderBy('created_at', 'desc')
            ->where(function ($query) use ($params) {
                $query->when(isset($params['startDate']) && isset($params['endDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['startDate'], $params['endDate']]);
                });
            })->sum('price');

    }

    public function createTransactionUserPackage($data)
    {
        return TransactionUserPackage::create($data);
    }

    public function updateTransactionUserPackageById($id, $data)
    {
        return TransactionUserPackage::where('id', $id)->update($data);
    }

    public function deleteTransactionUserPackageById($id)
    {
        return TransactionUserPackage::where('id', $id)->delete();
    }

    public function getTransactionUserPackageById($id)
    {
        return TransactionUserPackage::with(['user'])->find($id);
    }
}