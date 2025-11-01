<?php

namespace App\Http\Controllers;

use App\Models\PackageFeature;
use App\Models\ServicePackage;
use Illuminate\Http\Request;

class ServicePackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $packages = ServicePackage::all();
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name'          => ['required', 'string', 'unique:service_packages,name', 'max:60'],
            'description'   => ['required', 'string', 'max:255'],
            'price'         => ['required', 'numeric'],
            'duration'      => ['required', 'numeric'],
            'duration_unit' => ['required', 'in:days,weeks,months,years'],
        ]);

        try {
            $package = ServicePackage::create($validated);
            return redirect()->back()->with('success', 'Package created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create package: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeFeature(Request $request)
    {
        //
        $validated = $request->validate([
            'name'          => ['required', 'string', 'unique:package_features,name', 'max:60'],
        ]);

        try {
            $feature = PackageFeature::create($validated);
            return redirect()->back()->with('success', 'Feature created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create feature: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ServicePackage $servicePackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServicePackage $servicePackage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServicePackage $servicePackage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServicePackage $servicePackage)
    {
        //
    }
}
