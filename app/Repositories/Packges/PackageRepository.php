<?php
namespace App\Repositories\Packges;

use App\Models\Package;

class PackageRepository
{
    public function getAllPackagesByParams($params)
    {
        return Package::where(function ($query) use ($params) {
            $query->when(isset($params['search']), function ($query) use ($params) {
                $query->whereRaw('lower(name) like lower(?)', ["%{$params['search']}%"]);
            });
        })->orderBy('name', 'asc')
            ->paginate($params['perPage'] ?? 10)
            ->appends([
                'search' => $params['search'] ?? null,
                'perPage' => $params['perPage'] ?? 10
            ]);
    }

    public function getAllPackages()
    {
        return Package::orderBy('name', 'asc')->get();
    }

    public function createPackage($data)
    {
        return Package::create($data);
    }

    public function getPackageById($id)
    {
        return Package::find($id);
    }
    public function updatePackageById($id, $data)
    {
        return Package::where('id', $id)->update($data);
    }

    public function deletePackageById($id)
    {
        return Package::where('id', $id)->delete();
    }
}