<?php

namespace App\Http\Controllers\Class;

use App\Exports\ClassExport;
use App\Http\Controllers\Controller;
use App\Repositories\Packges\PackageRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClassController extends Controller
{
    public function __construct(
        private PackageRepository $packageRepository,
    ) {
        //
    }

    public function index(Request $request)
    {
        $data = $request->validate([
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'perPage' => 'integer|nullable'
        ]);
        $data['perPage'] = $data['perPage'] ?? 10;
        $data['type'] = 'class';

        return view('pages.class.index', [
            'classes' => $this->packageRepository->getAllPackagesByParams($data),
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('pages.class.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);
        $data['quota'] = 0;
        $data['type'] = 'class';

        $this->packageRepository->createPackage($data);

        return redirect()->route('class.index');
    }

    public function edit($id)
    {
        return view('pages.class.edit', [
            'class' => $this->packageRepository->getPackageById($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        $data = $request->validate([
            'id' => 'required|exists:packages,id,deleted_at,NULL',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $this->packageRepository->updatePackageById($id, $data);

        return redirect()->route('class.index');
    }

    public function destroy(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => 'required|exists:packages,id,deleted_at,NULL'
        ]);

        $this->packageRepository->deletePackageById($id);

        return redirect()->route('packages.index');
    }

    public function export()
    {
        return Excel::download(new ClassExport, 'class.xlsx', \Maatwebsite\Excel\Excel::XLSX);

    }
}
