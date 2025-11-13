<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupItem;
use App\Support\AjaxResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Js;

class GroupsItemsManagementController extends Controller
{
    //
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'group_id'      => 'required|exists:groups,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:255',
        ]);
        try {
            $groupItem = GroupItem::create($validated);
            return  redirect()->back()->withSuccess('Group item created successfully')->send();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create group item: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $id)
    {
        //
        $groupItem = GroupItem::find($id);

        if (!$groupItem) {
            return redirect()->back()->withError('Group item not found');
        }
        $validated = $req->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|max:255',
        ]);

        try {
            $groupItem->update($validated);
            return redirect()->back()->withSuccess('Group item updated successfully')->send();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update group item: ' . $e->getMessage());
        }
    }

    /* 
    * 
    */
    public function destroy(GroupItem $groupItem)
    {
        //
        if (!$groupItem) {
            return redirect()->back()->withError('Group item not found');
        }
        try {
            $groupItem->delete();
            return redirect()->back()->withSuccess('Group item deleted successfully')->send();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to delete group item: ' . $e->getMessage());
        }
    }
}
