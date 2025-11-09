<?php
namespace App\Exports;

use App\Models\Package;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PackageExport implements FromCollection, WithHeadings, WithMapping
{
    public function headings(): array
    {
        return [
            'name',
            'price',
            'description',
            'quota',
        ];
    }

    public function collection()
    {
        return Package::all();
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->price,
            $item->description,
            $item->quota,
        ];
    }
}
