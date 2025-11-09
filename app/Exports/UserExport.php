<?php
namespace App\Exports;

use App\Models\Users\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    public function headings(): array
    {
        return [
            'name',
            'email',
            'role'
        ];
    }

    public function collection()
    {
        return User::all();
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->role
        ];
    }
}
