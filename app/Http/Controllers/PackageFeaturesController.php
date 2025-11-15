<?php

namespace App\Http\Controllers;

use App\Models\PackageFeature;
use App\Models\PackageFeatureValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageFeaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $features = PackageFeature::orderBy('display_order')->get();
        return view('admin.settings.packages-features.index', compact('features'));
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
        $vdt = $request->validate([
            'name' => 'required|string|unique:package_features,name',
            'description' => 'nullable|string',
        ]);
        $vdt['display_order'] = PackageFeature::max('display_order') + 1;
        try {
            $pf = PackageFeature::create($vdt);
            return redirect()->back()->with('success', 'Feature created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create feature: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PackageFeature $packageFeature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PackageFeature $packageFeature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PackageFeature $packageFeature)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PackageFeature $packageFeature)
    {
        //
    }

    public function setFeatureValue($packageId, $featureId, $value)
    {

        $pfv = PackageFeatureValue::create([
            'service_package_id' => $packageId,
            'package_feature_id' => $featureId,
            'value' => $value,
        ]);
    }

    public function customizeFeatures(Request $request)
    {
        //
        $features = PackageFeature::all();
        $inputFeatures = [];
        DB::beginTransaction();
        try {
            foreach ($request->all() as $key => $val) {
                $inputFeatures[explode('_', $key)[1]] = $val;
            }
            foreach ($features as $feature) {
                if (array_key_exists($feature->id, $inputFeatures)) {
                    $this->setFeatureValue($request->package_id, $feature->id, $inputFeatures[$feature->id]);
                    DB::commit();
                }
            }
            return redirect()
                ->back()
                ->with('success', 'Features customized successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to customize features: ' . $th->getMessage());
        }
    }
}
