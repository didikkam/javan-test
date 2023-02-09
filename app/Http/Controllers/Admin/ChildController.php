<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Family;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class ChildController extends Controller
{

   public function index($parent_id)
   {
      return view('admin.family.child.index')->with(['parent_id' => $parent_id]);
   }

   public function create($parent_id)
   {
      return view('admin.family.child.create')->with(['parent_id' => $parent_id]);
   }

   public function edit($parent_id, $id)
   {
      $data = Family::with('parent')->findOrFail($id);
      return view('admin.family.child.edit')->with([
         'data' => $data,
         'parent_id' => $parent_id
      ]);
   }

   public function destroy($parent_id, $id)
   {
      $item = Family::findOrFail($id);
      $item->delete();
      return redirect()->route('admin.family.child.index', $parent_id)->withFlashSuccess(__('Data Deleted Successfully'));
   }

   public function store($parent_id, Request $request)
   {
      $request->validate([
         'name'         => 'required|max:200',
         'sex'        => 'in:l,p',
         'parent_id'   => 'exists:families,id',
      ]);
      $data = [
         'name' => $request->name,
         'sex' => $request->sex,
         'parent_id' => $request->parent_id,
      ];
      if (isset($request->id)) {
         $item = Family::findOrFail($request->id);
         $query = $item->update($data);
      } else {
         $query = Family::create($data);
      }
      if ($query) {
         return redirect()->route('admin.family.child.index', $parent_id)->withFlashSuccess(__('Data Saved Successfully'));
      } else {
         return redirect()->route('admin.family.child.index', $parent_id)->withFlashDanger(__('Data Failed To Save'));
      }
   }

   public function datatable(Request $request)
   {
      $data = Family::query();
      $data = $data->withCount('child');
      $data = $data->where('parent_id', $request->form['parent_id']);
      if (!$request->order[0]['column']) {
         $data = $data->orderBy('created_at', 'desc');
      }
      return DataTables::of($data)
         ->make(true);
   }

   public function selectSearch($id, Request $request)
   {
      return
         Family::where("name", "like", "%{$request->term}%")
         ->where('id', $id)
         ->select("id", "name")
         ->limit(5)
         ->get();
   }
}
