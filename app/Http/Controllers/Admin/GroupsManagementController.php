<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Support\AjaxResponse;
use App\Services\ServicePackageResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GroupsManagementController extends Controller
{
    //
    public function index()
    {
        //
        $groups = Group::where([])->with('group_items')->get();
        return view('admin.settings.groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        try {
            $group = Group::create($validated);
            return redirect()->back()->with('success', 'Group created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create group: ' . $e->getMessage());
        }
    }

    public function show(Group $group)
    {
        return $group ?  AjaxResponse::success($group->with('group_items')->first())->withMessage('Group fetched successfully')->send() : AjaxResponse::notFound('Group not found')->withMessage('Group not found')->send();
    }

    /* 
    * Update a group
    */
    public function update(Request $request)
    {
        //
        $group = Group::find($request->group_id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:255',
        ]);
        //return $validated;
        try {
            $group->update($validated);
            return redirect()->back()->with('success', 'تم تحديث بيانات المجموعة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'فشات عملية التحديث بسبب: ' . $e->getMessage());
        }
    }

    /**
     * Delete a group and related group items
     */
    public function destroy(Group $group)
    {
        if (!$group) {
            return redirect()->back()->with('error', 'Group not found');
        }
        try {
            $group->delete();
            return redirect()->back()->with('success', 'Group & related group items has been deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete group: ' . $e->getMessage());
        }
    }
}
