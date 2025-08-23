<?php
namespace App\Exports;

use App\Models\Package;
use App\Models\Transaction\TransactionUserPackage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionUserPackageExport implements FromCollection, WithHeadings, WithMapping
{
    private $request;
    public function __construct($request)
    {
        //
    }
    public function headings(): array
    {
        return [
            'User Name',
            'Package Name',
            'price',
            'description',
            'quota'
        ];
    }

    public function collection()
    {
        $params = $this->request;
        return TransactionUserPackage::where(function ($query) use ($params) {
            $query->when(isset($params['userId']), function ($query) use ($params) {
                $query->where('user_id', $params['userId']);
            });
        })->orderBy('created_at', 'desc')
            ->where(function ($query) use ($params) {
                $query->when(isset($params['startDate']) && isset($params['endDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['startDate'], $params['endDate']]);
                });
            })
            ->get();
    }

    public function map($item): array
    {
        return [
            $item->user_name,
            $item->package_name,
            $item->price,
            $item->description,
            $item->quota
        ];
    }
}
