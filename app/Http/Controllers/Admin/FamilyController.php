<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Family;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class FamilyController extends Controller
{

   public function index()
   {
      return view('admin.family.index');
   }
  
   function getChildren($query, $depth = 0) {
      if ($depth > 10) {
          return;
      }
  
      $query->with(['child' => function($query) use ($depth) {
          $this->getChildren($query, $depth + 1);
      }]);
  }

   public function tree()
   {
      $families = Family::with(['child' => function ($query) {
         $this->getChildren($query, 0);
      }])->whereNull('parent_id')->get();
      return view('admin.family.tree.index')->with(['families' => $families]);
   }

   // cucu
   public function grandson($parent_id)
   {
      return view('admin.family.view')->with(['grandson_parent_id' => $parent_id]);
   }

   // cucu perempuan
   public function granddaughter($parent_id)
   {
      return view('admin.family.view')->with(['granddaughter_parent_id' => $parent_id]);
   }

   // bibi
   public function aunt($parent_id)
   {
      return view('admin.family.view')->with(['aunt_parent_id' => $parent_id]);
   }
   // sepupu laki-laki
   public function malecousin($parent_id)
   {
      return view('admin.family.view')->with(['malecousin_parent_id' => $parent_id]);
   }

   public function create()
   {
      return view('admin.family.create');
   }

   public function edit($id)
   {
      $data = Family::with('parent')->findOrFail($id);
      return view('admin.family.edit')->with([
         'data' => $data
      ]);
   }

   public function destroy($id)
   {
      $item = Family::findOrFail($id);
      $item->delete();
      return redirect()->route('admin.family.index')->withFlashSuccess(__('Data Deleted Successfully'));
   }

   public function store(Request $request)
   {
      $request->validate([
         'name'         => 'required|max:200',
         'sex'        => 'in:l,p',
      ]);
      $data = [
         'name' => $request->name,
         'sex' => $request->sex,
      ];
      if (isset($request->id)) {
         $item = Family::findOrFail($request->id);
         $query = $item->update($data);
      } else {
         $query = Family::create($data);
      }
      if ($query) {
         return redirect()->route('admin.family.index')->withFlashSuccess(__('Data Saved Successfully'));
      } else {
         return redirect()->route('admin.family.index')->withFlashDanger(__('Data Failed To Save'));
      }
   }

   public function datatable(Request $request)
   {
      $data = Family::query();
      $data = $data->withCount('child');
      $data = $data->whereNull('parent_id');
      if (!$request->order[0]['column']) {
         $data = $data->orderBy('created_at', 'desc');
      }
      return DataTables::of($data)
         ->make(true);
   }

   public function datatable_view(Request $request)
   {
      $data = Family::query();
      if (isset($request->form['grandson_parent_id'])) {
         $data = $data->join('families as anak', 'families.id', '=', 'anak.parent_id')
            ->join('families as cucu', 'anak.id', '=', 'cucu.parent_id')->select('cucu.id as id', 'cucu.name as name', 'cucu.sex as sex');
         $data = $data->where('families.id', $request->form['grandson_parent_id']);
      }
      if (isset($request->form['granddaughter_parent_id'])) {
         $data = $data->join('families as anak', 'families.id', '=', 'anak.parent_id')
            ->join('families as cucu', 'anak.id', '=', 'cucu.parent_id')->select('cucu.id as id', 'cucu.name as name', 'cucu.sex as sex');
         $data = $data->where('cucu.sex', 'p');
         $data = $data->where('families.id', $request->form['granddaughter_parent_id']);
      }
      if (isset($request->form['aunt_parent_id'])) {
         $data = $data->join('families as orang_tua', 'families.parent_id', '=', 'orang_tua.id')
            ->join('families as saudara_orang_tua', 'orang_tua.parent_id', '=', 'saudara_orang_tua.parent_id')
            ->select('saudara_orang_tua.id as id', 'saudara_orang_tua.name as name', 'saudara_orang_tua.sex as sex')
            ->where('saudara_orang_tua.sex', 'p')
            ->where('families.id', $request->form['aunt_parent_id']);
      }
      if (isset($request->form['malecousin_parent_id'])) {
         $data = $data->join('families as orang_tua', 'families.parent_id', '=', 'orang_tua.id')
            ->join('families as saudara_orang_tua', 'orang_tua.parent_id', '=', 'saudara_orang_tua.parent_id')
            ->join('families as sepupu', 'saudara_orang_tua.id', '=', 'sepupu.parent_id')
            ->select('sepupu.id as id', 'sepupu.name as name', 'sepupu.sex as sex')
            ->where('sepupu.sex', 'l')
            ->where('families.id', $request->form['malecousin_parent_id']);
      }
      if (!$request->order[0]['column']) {
         $data = $data->orderBy('families.created_at', 'desc');
      }
      return DataTables::of($data)
         ->make(true);
   }
}
